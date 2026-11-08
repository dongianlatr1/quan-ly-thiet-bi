<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Device;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    private const ATTRIBUTES = [
        'room_id' => 'Phòng học',
        'booking_date' => 'Ngày mượn',
        'start_period' => 'Tiết bắt đầu',
        'end_period' => 'Tiết kết thúc',
        'purpose' => 'Mục đích sử dụng',
        'device_ids' => 'Thiết bị',
        'device_ids.*' => 'Thiết bị',
    ];

    private const MESSAGES = [
        'required' => 'Vui lòng nhập :attribute.',
        'exists' => ':attribute không tồn tại.',
        'date' => ':attribute không hợp lệ.',
        'after_or_equal' => ':attribute phải từ hôm nay trở đi.',
        'between' => ':attribute phải từ :min đến :max.',
        'gte' => 'Tiết kết thúc không được nhỏ hơn tiết bắt đầu.',
        'integer' => ':attribute không hợp lệ.',
        'max' => ':attribute quá dài (tối đa :max).',
    ];

    public function index()
    {
        $isAdmin = auth()->user()->isAdmin();

        $bookings = Booking::with(['user', 'room', 'devices'])
            ->when(! $isAdmin, fn ($query) => $query->where('user_id', auth()->id()))
            ->orderByDesc('booking_date')
            ->orderByDesc('id')
            ->paginate(10);

        return view('booking.index', compact('bookings', 'isAdmin'));
    }

    public function create()
    {
        return view('booking.create', [
            'rooms' => Room::where('status', 'active')->orderBy('code')->get(),
            'devices' => Device::with('room')->where('status', 'good')->orderBy('code')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $max = Booking::PERIOD_COUNT;

        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_period' => ['required', 'integer', "between:1,$max"],
            'end_period' => ['required', 'integer', "between:1,$max", 'gte:start_period'],
            'purpose' => ['required', 'string', 'max:255'],
            'device_ids' => ['nullable', 'array'],
            'device_ids.*' => ['integer', 'exists:devices,id'],
        ], self::MESSAGES, self::ATTRIBUTES);

        $deviceIds = array_values(array_unique($data['device_ids'] ?? []));

        $room = Room::findOrFail($data['room_id']);
        if ($room->status !== 'active') {
            throw ValidationException::withMessages(['room_id' => 'Phòng này đang bảo trì hoặc đã bị khóa.']);
        }

        $badDevices = Device::whereIn('id', $deviceIds)->where('status', '!=', 'good')->pluck('code');
        if ($badDevices->isNotEmpty()) {
            throw ValidationException::withMessages([
                'device_ids' => 'Thiết bị không sẵn sàng: ' . $badDevices->implode(', ') . '.',
            ]);
        }

        DB::transaction(function () use ($data, $deviceIds, $room) {
            // Khóa phòng và thiết bị để 2 người gửi cùng lúc không cùng lọt qua kiểm tra
            Room::whereKey($room->id)->lockForUpdate()->first();
            if ($deviceIds) {
                Device::whereIn('id', $deviceIds)->orderBy('id')->lockForUpdate()->get();
            }

            $errors = Booking::conflicts(
                $room->id,
                $deviceIds,
                $data['booking_date'],
                (int) $data['start_period'],
                (int) $data['end_period'],
                ['pending', 'approved'],
            );

            if ($errors) {
                throw ValidationException::withMessages(['conflict' => $errors]);
            }

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'room_id' => $room->id,
                'booking_date' => $data['booking_date'],
                'start_period' => $data['start_period'],
                'end_period' => $data['end_period'],
                'purpose' => $data['purpose'],
                'status' => 'pending',
            ]);

            foreach ($deviceIds as $deviceId) {
                BookingDetail::create(['booking_id' => $booking->id, 'device_id' => $deviceId]);
            }
        });

        return redirect()->route('bookings.index')
            ->with('success', 'Đã gửi yêu cầu đặt lịch. Vui lòng chờ quản trị viên duyệt.');
    }

    public function cancel(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403, 'Bạn chỉ được hủy lịch của chính mình.');

        if (! in_array($booking->status, ['pending', 'approved'], true)) {
            return back()->with('error', 'Lịch này không thể hủy.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Đã hủy lịch đặt.');
    }
}