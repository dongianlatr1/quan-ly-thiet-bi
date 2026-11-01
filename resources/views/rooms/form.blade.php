@extends('layouts.app')

@section('title', $room->exists ? 'Sửa phòng học' : 'Thêm phòng học')

@section('content')
<h1 class="text-2xl font-bold mb-4">{{ $room->exists ? 'Sửa phòng học' : 'Thêm phòng học' }}</h1>

<form method="POST" action="{{ $room->exists ? route('rooms.update', $room) : route('rooms.store') }}"
      class="bg-white rounded-xl shadow p-6 max-w-xl space-y-4">
    @csrf
    @if ($room->exists)
        @method('PUT')
    @endif

    <div>
        <label class="text-sm font-medium">Mã phòng</label>
        <input name="code" value="{{ old('code', $room->code) }}"
               class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
        @error('code')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Tên phòng</label>
        <input name="name" value="{{ old('name', $room->name) }}"
               class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Tòa nhà</label>
        <input name="building" value="{{ old('building', $room->building) }}"
               class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
        @error('building')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Sức chứa</label>
        <input type="number" min="0" name="capacity" value="{{ old('capacity', $room->capacity ?? 0) }}"
               class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
        @error('capacity')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Trạng thái</label>
        <select name="status" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2">
            @foreach (\App\Models\Room::STATUSES as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $room->status ?? 'active') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex gap-3">
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Lưu</button>
        <a href="{{ route('rooms.index') }}" class="px-4 py-2 rounded-lg border border-slate-300">Hủy</a>
    </div>
</form>
@endsection