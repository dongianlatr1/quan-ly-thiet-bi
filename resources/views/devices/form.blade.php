@extends('layouts.app')

@section('title', $device->exists ? 'Sửa thiết bị' : 'Thêm thiết bị')

@section('content')
@php
    $importedAt = $device->exists
        ? $device->imported_at?->format('Y-m-d')
        : now()->format('Y-m-d');
@endphp

<h1 class="text-2xl font-bold mb-4">{{ $device->exists ? 'Sửa thiết bị' : 'Thêm thiết bị' }}</h1>

<form method="POST" action="{{ $device->exists ? route('devices.update', $device) : route('devices.store') }}"
      class="bg-white rounded-xl shadow p-6 max-w-xl space-y-4">
    @csrf
    @if ($device->exists)
        @method('PUT')
    @endif

    <div>
        <label class="text-sm font-medium">Mã thiết bị</label>
        <input name="code" value="{{ old('code', $device->code) }}"
               class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
        @error('code')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Tên thiết bị</label>
        <input name="name" value="{{ old('name', $device->name) }}"
               class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Loại (máy chiếu, micro, loa...)</label>
        <input name="category" value="{{ old('category', $device->category) }}"
               class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
        @error('category')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Vị trí (phòng)</label>
        <select name="room_id" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
            <option value="">Kho (chưa gắn phòng)</option>
            @foreach ($rooms as $r)
                <option value="{{ $r->id }}" @selected((string) old('room_id', $device->room_id) === (string) $r->id)>
                    {{ $r->code }} - {{ $r->name }}
                </option>
            @endforeach
        </select>
        @error('room_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Trạng thái</label>
        <select name="status" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
            @foreach (\App\Models\Device::STATUSES as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $device->status ?? 'good') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Ngày nhập</label>
        <input type="date" name="imported_at" value="{{ old('imported_at', $importedAt) }}"
               class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
        @error('imported_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex gap-3">
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Lưu</button>
        <a href="{{ route('devices.index') }}" class="px-4 py-2 rounded-lg border border-slate-300">Hủy</a>
    </div>
</form>
@endsection