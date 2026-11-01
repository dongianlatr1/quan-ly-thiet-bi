@extends('layouts.app')

@section('title', 'Phòng học')

@section('content')
@php
    $badge = [
        'active' => 'bg-green-100 text-green-700',
        'maintenance' => 'bg-amber-100 text-amber-700',
        'locked' => 'bg-slate-200 text-slate-600',
    ];
@endphp

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Quản lý phòng học</h1>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('rooms.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">+ Thêm phòng</a>
    @endif
</div>

<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <input name="q" value="{{ request('q') }}" placeholder="Tìm theo mã hoặc tên phòng..."
           class="border border-slate-300 rounded-lg px-3 py-2 text-sm w-72">
    <select name="status" class="border border-slate-300 rounded-lg px-3 py-2 text-sm">
        <option value="">Tất cả trạng thái</option>
        @foreach (\App\Models\Room::STATUSES as $key => $label)
            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <button class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm">Lọc</button>
</form>

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-100 text-left">
            <tr>
                <th class="p-3">Mã phòng</th>
                <th class="p-3">Tên phòng</th>
                <th class="p-3">Tòa nhà</th>
                <th class="p-3">Sức chứa</th>
                <th class="p-3">Số thiết bị</th>
                <th class="p-3">Trạng thái</th>
                <th class="p-3"></th>
            </tr>
        </thead>
        <tbody>
        @forelse ($rooms as $room)
            <tr class="border-t">
                <td class="p-3 font-medium">{{ $room->code }}</td>
                <td class="p-3">{{ $room->name }}</td>
                <td class="p-3">{{ $room->building }}</td>
                <td class="p-3">{{ $room->capacity }}</td>
                <td class="p-3">{{ $room->devices_count }}</td>
                <td class="p-3">
                    <span class="text-xs rounded px-2 py-1 {{ $badge[$room->status] ?? '' }}">
                        {{ \App\Models\Room::STATUSES[$room->status] ?? $room->status }}
                    </span>
                </td>
                <td class="p-3 text-right whitespace-nowrap">
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('rooms.edit', $room) }}" class="text-blue-600 mr-3">Sửa</a>

                        <form method="POST" action="{{ route('rooms.lock', $room) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button class="text-amber-600 mr-3">{{ $room->status === 'locked' ? 'Mở khóa' : 'Khóa' }}</button>
                        </form>

                        <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="inline"
                              onsubmit="return confirm('Xóa phòng {{ $room->code }}? Thiết bị trong phòng sẽ chuyển về kho.')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Xóa</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="p-6 text-center text-slate-500">Chưa có phòng học nào.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $rooms->links() }}</div>
@endsection