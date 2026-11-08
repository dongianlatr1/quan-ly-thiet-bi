@extends('layouts.panel')

@section('title', $isAdmin ? 'Tổng hợp lịch mượn phòng' : 'Lịch mượn của tôi')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $isAdmin ? 'Tổng hợp lịch mượn phòng' : 'Lịch mượn của tôi' }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $isAdmin ? 'Tất cả lịch mượn phòng của giảng viên trong hệ thống' : 'Theo dõi trạng thái các yêu cầu đặt phòng và thiết bị' }}
        </p>
    </div>

    @unless ($isAdmin)
        <a href="{{ route('bookings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium">
            <i class="fa-solid fa-plus mr-1"></i> Đặt lịch mới
        </a>
    @endunless
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="p-4">Ngày</th>
                @if ($isAdmin)
                    <th class="p-4">Tên phòng</th>
                    <th class="p-4">Tên giảng viên</th>
                    <th class="p-4">Tiết</th>
                    <th class="p-4">Mục đích</th>
                    <th class="p-4">Trạng thái</th>
                @else
                    <th class="p-4">Tiết</th>
                    <th class="p-4">Phòng</th>
                    <th class="p-4">Thiết bị</th>
                    <th class="p-4">Mục đích</th>
                    <th class="p-4">Trạng thái</th>
                    <th class="p-4"></th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse ($bookings as $b)
            <tr>
                <td class="p-4">{{ $b->booking_date->format('d/m/Y') }}</td>

                @if ($isAdmin)
                    <td class="p-4">
                        <p class="font-medium text-gray-800">{{ $b->room->name }}</p>
                        <p class="text-xs text-gray-400">{{ $b->room->code }}</p>
                    </td>
                    <td class="p-4">{{ $b->user->name ?? '—' }}</td>
                    <td class="p-4">{{ $b->periodText() }}</td>
                    <td class="p-4">{{ $b->purpose }}</td>
                    <td class="p-4">
                        <span class="text-xs rounded-full px-2.5 py-1 font-medium {{ $b->badgeClass() }}">{{ $b->statusLabel() }}</span>
                        @if ($b->status === 'rejected' && $b->reject_reason)
                            <p class="text-xs text-red-600 mt-1">Lý do: {{ $b->reject_reason }}</p>
                        @endif
                    </td>
                @else
                    <td class="p-4">{{ $b->periodText() }}</td>
                    <td class="p-4">{{ $b->room->code }}</td>
                    <td class="p-4">{{ $b->devices->pluck('code')->implode(', ') ?: '—' }}</td>
                    <td class="p-4">{{ $b->purpose }}</td>
                    <td class="p-4">
                        <span class="text-xs rounded-full px-2.5 py-1 font-medium {{ $b->badgeClass() }}">{{ $b->statusLabel() }}</span>
                        @if ($b->status === 'rejected' && $b->reject_reason)
                            <p class="text-xs text-red-600 mt-1">Lý do: {{ $b->reject_reason }}</p>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        @if (in_array($b->status, ['pending', 'approved'], true))
                            <form method="POST" action="{{ route('bookings.cancel', $b) }}" onsubmit="return confirm('Hủy lịch này?')">
                                @csrf
                                @method('PATCH')
                                <button class="text-red-600 hover:underline">Hủy lịch</button>
                            </form>
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ $isAdmin ? 6 : 7 }}" class="p-8 text-center text-gray-400">
                    {{ $isAdmin ? 'Chưa có lịch mượn phòng nào.' : 'Bạn chưa có yêu cầu đặt lịch nào.' }}
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $bookings->links() }}</div>
@endsection