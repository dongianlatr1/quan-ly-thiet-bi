@extends('layouts.panel')

@section('title', 'Duyệt đặt lịch')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Duyệt yêu cầu đặt lịch</h1>
    <p class="text-sm text-gray-500 mt-1">Phê duyệt hoặc từ chối các yêu cầu mượn phòng và thiết bị</p>
</div>

<div class="flex flex-wrap gap-2 mb-4">
    @foreach (\App\Models\Booking::STATUSES as $key => $label)
        <a href="{{ route('admin.bookings.index', ['status' => $key]) }}"
           class="px-4 py-2 rounded-lg text-sm border {{ $status === $key ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            {{ $label }} ({{ $counts[$key] ?? 0 }})
        </a>
    @endforeach
</div>

@error('reject_reason')
    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
@enderror

<div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="p-4">Người đặt</th>
                <th class="p-4">Ngày</th>
                <th class="p-4">Tiết</th>
                <th class="p-4">Phòng</th>
                <th class="p-4">Thiết bị</th>
                <th class="p-4">Mục đích</th>
                <th class="p-4">Xử lý</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse ($bookings as $b)
            <tr class="align-top">
                <td class="p-4 font-medium">{{ $b->user->name }}</td>
                <td class="p-4">{{ $b->booking_date->format('d/m/Y') }}</td>
                <td class="p-4">{{ $b->periodText() }}</td>
                <td class="p-4">{{ $b->room->code }}</td>
                <td class="p-4">{{ $b->devices->pluck('code')->implode(', ') ?: '—' }}</td>
                <td class="p-4">{{ $b->purpose }}</td>
                <td class="p-4">
                    @if ($b->status === 'pending')
                        <div class="flex flex-col gap-2 min-w-[220px]">
                            <form method="POST" action="{{ route('admin.bookings.approve', $b) }}">
                                @csrf
                                @method('PATCH')
                                <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium">Duyệt</button>
                            </form>
                            <form method="POST" action="{{ route('admin.bookings.reject', $b) }}" class="flex gap-1">
                                @csrf
                                @method('PATCH')
                                <input name="reject_reason" placeholder="Lý do từ chối" required maxlength="255"
                                       class="flex-1 border border-gray-300 rounded-lg px-2 py-1.5 text-xs">
                                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium">Từ chối</button>
                            </form>
                        </div>
                    @else
                        <span class="text-xs rounded-full px-2.5 py-1 font-medium {{ $b->badgeClass() }}">{{ $b->statusLabel() }}</span>
                        @if ($b->reject_reason)
                            <p class="text-xs text-gray-500 mt-1">Lý do: {{ $b->reject_reason }}</p>
                        @endif
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="p-8 text-center text-gray-400">Không có yêu cầu nào.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $bookings->links() }}</div>
@endsection