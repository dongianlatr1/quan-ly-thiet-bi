<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Quản lý thiết bị')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">
    <nav class="bg-[#1e3a5f] text-white">
        <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="font-bold">Quản lý thiết bị</a>
                <a href="{{ route('rooms.index') }}" class="text-sm hover:underline">Phòng học</a>
                <a href="{{ route('devices.index') }}" class="text-sm hover:underline">Thiết bị</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin') }}" class="text-sm hover:underline">Quản trị</a>
                @endif
            </div>
            <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-3 text-sm">
                @csrf
                <span>{{ auth()->user()->name }}
                    <span class="ml-1 text-xs bg-white/15 rounded px-2 py-0.5">{{ auth()->user()->roleLabel() }}</span>
                </span>
                <button class="bg-white/10 hover:bg-white/20 px-3 py-1 rounded">Đăng xuất</button>
            </form>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto p-4">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>