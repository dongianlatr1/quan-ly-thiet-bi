@extends('layouts.panel')

@section('title', 'Sự cố thiết bị')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Sự cố thiết bị</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $isAdmin ? 'Tất cả báo cáo sự cố do giảng viên gửi' : 'Các báo cáo sự cố bạn đã gửi' }}
        </p>
    </div>

    @unless ($isAdmin)
        <a href="{{ route('issues.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium">
            <i class="fa-solid fa-plus mr-1"></i> Báo cáo sự cố
        </a>
    @endunless
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="p-4">Ngày báo</th>
                <th class="p-4">Thiết bị</th>
                <th class="p-4">Sự cố</th>
                @if ($isAdmin)
                    <th class="p-4">Người báo</th>
                @endif
                <th class="p-4">Hình ảnh</th>
                <th class="p-4">Trạng thái</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse ($incidents as $i)
            <tr>
                <td class="p-4 whitespace-nowrap">{{ $i->created_at->format('d/m/Y H:i') }}</td>
                <td class="p-4">
                    @if ($i->device)
                        <p class="font-medium text-gray-800">{{ $i->device->name }}</p>
                        <p class="text-xs text-gray-400">{{ $i->device->code }}</p>
                    @else
                        <span class="text-gray-400">Thiết bị đã bị xóa</span>
                    @endif
                </td>
                <td class="p-4 max-w-xs">
                    <p class="font-medium text-gray-800">{{ $i->title }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ \Illuminate\Support\Str::limit($i->description, 90) }}</p>
                </td>
                @if ($isAdmin)
                    <td class="p-4">{{ $i->user->name ?? '—' }}</td>
                @endif
                <td class="p-4">
                    @if ($i->imageUrl())
                        <a href="{{ $i->imageUrl() }}" target="_blank" title="Bấm để xem ảnh lớn">
                            <img src="{{ $i->imageUrl() }}" alt="Ảnh sự cố" class="w-14 h-14 object-cover rounded-lg border border-gray-200">
                        </a>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="p-4">
                    <span class="text-xs rounded-full px-2.5 py-1 font-medium {{ $i->badgeClass() }}">{{ $i->statusLabel() }}</span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $isAdmin ? 6 : 5 }}" class="p-8 text-center text-gray-400">
                    {{ $isAdmin ? 'Chưa có báo cáo sự cố nào.' : 'Bạn chưa gửi báo cáo sự cố nào.' }}
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $incidents->links() }}</div>
@endsection