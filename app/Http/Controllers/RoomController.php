<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Support\ValidationText;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    private const ATTRIBUTES = [
        'code' => 'Mã phòng',
        'name' => 'Tên phòng',
        'building' => 'Tòa nhà',
        'capacity' => 'Sức chứa',
        'status' => 'Trạng thái',
    ];

    private function rules(?Room $room = null): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('rooms', 'code')->ignore($room?->id)],
            'name' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(array_keys(Room::STATUSES))],
        ];
    }

    public function index(Request $request)
    {
        $rooms = Room::withCount('devices')
            ->when($request->q, fn ($query, $kw) => $query->where(
                fn ($w) => $w->where('code', 'like', "%$kw%")->orWhere('name', 'like', "%$kw%")
            ))
            ->when($request->status, fn ($query, $st) => $query->where('status', $st))
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.form', ['room' => new Room]);
    }

    public function store(Request $request)
    {
        Room::create($request->validate($this->rules(), ValidationText::MESSAGES, self::ATTRIBUTES));

        return redirect()->route('rooms.index')->with('success', 'Đã thêm phòng học.');
    }

    public function edit(Room $room)
    {
        return view('rooms.form', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $room->update($request->validate($this->rules($room), ValidationText::MESSAGES, self::ATTRIBUTES));

        return redirect()->route('rooms.index')->with('success', 'Đã cập nhật phòng học.');
    }

    public function destroy(Room $room)
    {
        $count = $room->devices()->count();
        $room->delete();

        $msg = "Đã xóa phòng {$room->code}.";
        if ($count > 0) {
            $msg .= " $count thiết bị trong phòng đã chuyển về kho.";
        }

        return redirect()->route('rooms.index')->with('success', $msg);
    }

    public function toggleLock(Room $room)
    {
        $locked = $room->status === 'locked';
        $room->update(['status' => $locked ? 'active' : 'locked']);

        return back()->with('success', ($locked ? 'Đã mở khóa' : 'Đã khóa') . " phòng {$room->code}.");
    }
}