@extends('layouts.panel')

@section('title', 'Đặt lịch mượn')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Đặt lịch mượn phòng và thiết bị</h1>
    <p class="text-sm text-gray-500 mt-1">Chọn phòng, ngày, tiết học và thiết bị cần mượn. Yêu cầu sẽ được quản trị viên duyệt.</p>
</div>

@if ($errors->has('conflict'))
    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 max-w-2xl">
        <p class="font-semibold mb-1">Không thể đặt lịch vì bị trùng:</p>
        <ul class="list-disc pl-5 space-y-0.5">
            @foreach ($errors->get('conflict') as $msg)
                <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('bookings.store') }}"
      class="bg-white rounded-xl border border-gray-200 p-6 max-w-2xl space-y-5">
    @csrf

    <div>
        <label class="text-sm font-medium text-gray-700">Phòng học</label>
        <select name="room_id" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">
            <option value="">-- Chọn phòng --</option>
            @foreach ($rooms as $r)
                <option value="{{ $r->id }}" @selected((string) old('room_id') === (string) $r->id)>
                    {{ $r->code }} - {{ $r->name }} ({{ $r->capacity }} chỗ)
                </option>
            @endforeach
        </select>
        @error('room_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700">Ngày mượn</label>
        <input type="date" name="booking_date" min="{{ now()->toDateString() }}"
               value="{{ old('booking_date', now()->addDay()->toDateString()) }}"
               class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">
        @error('booking_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium text-gray-700">Từ tiết</label>
            <select name="start_period" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">
                @for ($i = 1; $i <= \App\Models\Booking::PERIOD_COUNT; $i++)
                    <option value="{{ $i }}" @selected((int) old('start_period', 1) === $i)>Tiết {{ $i }}</option>
                @endfor
            </select>
            @error('start_period')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Đến tiết</label>
            <select name="end_period" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">
                @for ($i = 1; $i <= \App\Models\Booking::PERIOD_COUNT; $i++)
                    <option value="{{ $i }}" @selected((int) old('end_period', 3) === $i)>Tiết {{ $i }}</option>
                @endfor
            </select>
            @error('end_period')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700">Mục đích sử dụng</label>
        <input name="purpose" value="{{ old('purpose') }}" placeholder="Ví dụ: Dạy học phần Lập trình web"
               class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">
        @error('purpose')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700">Thiết bị cần mượn (không bắt buộc)</label>
        <div class="mt-2 grid sm:grid-cols-2 gap-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-3">
            @forelse ($devices as $d)
                <label class="flex items-start gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="device_ids[]" value="{{ $d->id }}" class="mt-1 rounded border-gray-300"
                           @checked(in_array($d->id, array_map('intval', old('device_ids', []))))>
                    <span>
                        {{ $d->code }} - {{ $d->name }}
                        <span class="block text-xs text-gray-400">{{ $d->room ? 'Đang ở ' . $d->room->code : 'Trong kho' }}</span>
                    </span>
                </label>
            @empty
                <p class="text-sm text-gray-400">Chưa có thiết bị sẵn sàng.</p>
            @endforelse
        </div>
        @error('device_ids')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        @error('device_ids.*')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex gap-3">
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium">Gửi yêu cầu</button>
        <a href="{{ route('bookings.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm">Hủy</a>
    </div>
</form>
@endsection