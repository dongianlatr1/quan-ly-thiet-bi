@extends('layouts.app')

@section('title', 'Thiết bị')

@section('content')
@php
    $badge = [
        'good' => 'bg-green-100 text-green-700',
        'broken' => 'bg-red-100 text-red-700',
        'maintenance' => 'bg-amber-100 text-amber-700',
        'locked' => 'bg-slate-200 text-slate-600',
    ];
@endphp

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Quản lý thiết bị</h1>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('devices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">+ Thêm thiết bị</a>
    @endif
</div>

<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <input name="q" value="{{ request('q') }}" placeholder="Tìm theo mã, tên hoặc loại..."
           class="border border-slate-300 rounded-lg px-3 py-2 text-sm w-72">
    <select name="room_id" class="border border-slate-300 rounded-lg px-3 py-2 text-sm">
        <option value="">Tất cả phòng</option>
        @foreach ($rooms as $r)
            <option value="{{ $r->id }}" @selected((string) request('room_id') === (string) $r->id)>{{ $r->code }} - {{ $r->name }}</option>
        @endforeach
    </select>
    <select name="status" class="border border-slate-300 rounded-lg px-3 py-2 text-sm">
        <option value="">Tất cả trạng thái</option>
        @foreach (\App\Models\Device::STATUSES as $key => $label)
            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <button class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm">Lọc</button>
</form>

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-100 text-left">
            <tr>
                <th class="p-3">Mã thiết bị</th>
                <th class="p-3">Tên thiết bị</th>
                <th class="p-3">Loại</th>
                <th class="p-3">Vị trí</th>
                <th class="p-3">Trạng thái</th>
                <th class="p-3">Ngày nhập</th>
                <th class="p-3"></th>
            </tr>
        </thead>
        <tbody>
        @forelse ($devices as $device)
            <tr class="border-t">
                <td class="p-3 font-medium">{{ $device->code }}</td>
                <td class="p-3">{{ $device->name }}</td>
                <td class="p-3">{{ $device->category }}</td>
                <td class="p-3">{{ $device->room ? $device->room->code . ' - ' . $device->room->name : 'Kho' }}</td>
                <td class="p-3">
                    <span class="text-xs rounded px-2 py-1 {{ $badge[$device->status] ?? '' }}">
                        {{ \App\Models\Device::STATUSES[$device->status] ?? $device->status }}
                    </span>
                </td>
                <td class="p-3">{{ $device->imported_at?->format('d/m/Y') }}</td>
                <td class="p-3 text-right whitespace-nowrap">
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('devices.edit', $device) }}" class="text-blue-600 mr-3">Sửa</a>

                        <form method="POST" action="{{ route('devices.lock', $device) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button class="text-amber-600 mr-3">{{ $device->status === 'locked' ? 'Mở khóa' : 'Khóa' }}</button>
                        </form>

                        <form method="POST" action="{{ route('devices.destroy', $device) }}" class="inline"
                              onsubmit="return confirm('Xóa thiết bị {{ $device->code }}?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Xóa</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="p-6 text-center text-slate-500">Chưa có thiết bị nào.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $devices->links() }}</div>
@endsection