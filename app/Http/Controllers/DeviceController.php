<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Room;
use App\Support\ValidationText;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
    private const ATTRIBUTES = [
        'code' => 'Mã thiết bị',
        'name' => 'Tên thiết bị',
        'category' => 'Loại',
        'room_id' => 'Phòng',
        'status' => 'Trạng thái',
        'imported_at' => 'Ngày nhập',
    ];

    private function rules(?Device $device = null): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('devices', 'code')->ignore($device?->id)],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'status' => ['required', Rule::in(array_keys(Device::STATUSES))],
            'imported_at' => ['nullable', 'date'],
        ];
    }

    public function index(Request $request)
    {
        $devices = Device::with('room')
            ->when($request->q, fn ($query, $kw) => $query->where(
                fn ($w) => $w->where('code', 'like', "%$kw%")
                    ->orWhere('name', 'like', "%$kw%")
                    ->orWhere('category', 'like', "%$kw%")
            ))
            ->when($request->status, fn ($query, $st) => $query->where('status', $st))
            ->when($request->room_id, fn ($query, $id) => $query->where('room_id', $id))
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        $rooms = Room::orderBy('code')->get();

        return view('devices.index', compact('devices', 'rooms'));
    }

    public function create()
    {
        return view('devices.form', [
            'device' => new Device,
            'rooms' => Room::orderBy('code')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Device::create($request->validate($this->rules(), ValidationText::MESSAGES, self::ATTRIBUTES));

        return redirect()->route('devices.index')->with('success', 'Đã thêm thiết bị.');
    }

    public function edit(Device $device)
    {
        return view('devices.form', [
            'device' => $device,
            'rooms' => Room::orderBy('code')->get(),
        ]);
    }

    public function update(Request $request, Device $device)
    {
        $device->update($request->validate($this->rules($device), ValidationText::MESSAGES, self::ATTRIBUTES));

        return redirect()->route('devices.index')->with('success', 'Đã cập nhật thiết bị.');
    }

    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('devices.index')->with('success', "Đã xóa thiết bị {$device->code}.");
    }

    public function toggleLock(Device $device)
    {
        $locked = $device->status === 'locked';
        $device->update(['status' => $locked ? 'good' : 'locked']);

        return back()->with('success', ($locked ? 'Đã mở khóa' : 'Đã khóa') . " thiết bị {$device->code}.");
    }
}