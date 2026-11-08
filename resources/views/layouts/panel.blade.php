<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - QL Thiết Bị</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-800">
@php
    $link = fn ($pattern) => request()->routeIs($pattern)
        ? 'bg-blue-600 text-white font-medium shadow-sm'
        : 'text-slate-400 hover:bg-slate-800 hover:text-white transition';
@endphp
<div class="flex h-screen overflow-hidden">

    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between p-4 flex-shrink-0">
        <div>
            <div class="flex items-center space-x-3 text-white font-bold text-lg px-3 py-3 mb-4">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <span class="tracking-wide">QL Thiết Bị</span>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ $link('dashboard') }}">
                    <i class="fa-solid fa-chart-pie w-5"></i><span class="text-sm">Tổng quan</span>
                </a>

                <div class="pt-4 pb-1 px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Quản lý</div>

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ $link('admin') }}">
                        <i class="fa-solid fa-user-shield w-5"></i><span class="text-sm">Quản lý tài khoản</span>
                    </a>
                @endif
                <a href="{{ route('rooms.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ $link('rooms.*') }}">
                    <i class="fa-solid fa-door-open w-5"></i><span class="text-sm">Phòng học</span>
                </a>
                <a href="{{ route('devices.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ $link('devices.*') }}">
                    <i class="fa-solid fa-desktop w-5"></i><span class="text-sm">Thiết bị</span>
                </a>

                <div class="pt-4 pb-1 px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Đặt lịch</div>

                <a href="{{ route('bookings.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ $link('bookings.*') }}">
                    <i class="fa-solid fa-calendar-check w-5"></i><span class="text-sm">   {{ auth()->user()->isAdmin() ? 'Tổng hợp lịch mượn phòng' : 'Lịch mượn của tôi' }}</span>
                </a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.bookings.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ $link('admin.bookings.*') }}">
                        <i class="fa-solid fa-clipboard-check w-5"></i><span class="text-sm">Duyệt đặt lịch</span>
                    </a>
                @endif

                <div class="pt-4 pb-1 px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Hệ thống</div>

                <a href="{{ route('issues.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ $link('issues.*') }}">
                    <i class="fa-solid fa-triangle-exclamation w-5"></i><span class="text-sm">Sự cố</span>
                </a>
                <a href="{{ route('statistics.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ $link('statistics.*') }}">
                    <i class="fa-solid fa-chart-column w-5"></i><span class="text-sm">Thống kê</span>
                </a>
            </nav>
        </div>

        <div class="border-t border-slate-800 pt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-lg text-red-400 hover:bg-slate-800 transition">
                    <i class="fa-solid fa-right-from-bracket w-5"></i><span class="text-sm font-medium">Đăng xuất</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-y-auto">
        <header class="bg-white border-b border-gray-200 px-8 py-3.5 flex items-center justify-end sticky top-0 z-10">
            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ auth()->user()->roleLabel() }}</p>
                    <p class="text-sm font-bold text-gray-700">{{ auth()->user()->name }}</p>
                </div>
                <div class="w-9 h-9 bg-blue-600 text-white font-bold rounded-full flex items-center justify-center text-sm shadow-sm">
                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="p-8">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>