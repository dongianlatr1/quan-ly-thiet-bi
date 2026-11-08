<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        if (! array_key_exists($status, Booking::STATUSES)) {
            $status = 'pending';
        }

        $bookings = Booking::with(['user', 'room', 'devices'])
            ->where('status', $status)
            ->orderBy('booking_date')
            ->orderBy('start_period')
            ->paginate(10)
            ->withQueryString();

        $counts = Booking::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.bookings.index', compact('bookings', 'status', 'counts'));
    }

    public function approve(Booking $booking)
    {
        $message = DB::transaction(function () use ($booking) {
            $booking = Booking::whereKey($booking->id)->lockForUpdate()->first();

            if ($booking->status !== 'pending') {
                return ['error', 'Yêu cầu này đã được xử lý trước đó.'];
            }

            if ($booking->room->status !== 'active') {
                return ['error', 'Phòng đang bảo trì hoặc bị khóa, không thể duyệt.'];
            }

            // Kiểm tra lại: chỉ so với các lịch ĐÃ DUYỆT (bỏ qua chính nó)
            $errors = Booking::conflicts(
                $booking->room_id,
                $booking->devices()->pluck('devices.id')->all(),
                $booking->booking_date->toDateString(),
                $booking->start_period,
                $booking->end_period,
                ['approved'],
                $booking->id,
            );

            if ($errors) {
                return ['error', 'Không thể duyệt vì trùng lịch: ' . implode(' ', $errors)];
            }

            $booking->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'reject_reason' => null,
            ]);

            return ['success', 'Đã duyệt yêu cầu đặt lịch.'];
        });

        return back()->with($message[0], $message[1]);
    }

    public function reject(Request $request, Booking $booking)
    {
        $data = $request->validate(
            ['reject_reason' => ['required', 'string', 'max:255']],
            [
                'reject_reason.required' => 'Vui lòng nhập lý do từ chối.',
                'reject_reason.max' => 'Lý do quá dài (tối đa 255 ký tự).',
            ]
        );

        $message = DB::transaction(function () use ($booking, $data) {
            $booking = Booking::whereKey($booking->id)->lockForUpdate()->first();

            if ($booking->status !== 'pending') {
                return ['error', 'Yêu cầu này đã được xử lý trước đó.'];
            }

            $booking->update([
                'status' => 'rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'reject_reason' => $data['reject_reason'],
            ]);

            return ['success', 'Đã từ chối yêu cầu đặt lịch.'];
        });

        return back()->with($message[0], $message[1]);
    }
}