<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tổng quan - QL Thiết Bị</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-800">
    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between p-4 flex-shrink-0">
            <div>
                <div class="flex items-center space-x-3 text-white font-bold text-lg px-3 py-3 mb-4">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <span class="tracking-wide">QL Thiết Bị</span>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg bg-blue-600 text-white font-medium shadow-sm">
                        <i class="fa-solid fa-chart-pie w-5"></i>
                        <span class="text-sm">Tổng quan</span>
                    </a>

                    <div class="pt-4 pb-1 px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Quản lý</div>

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                            <i class="fa-solid fa-user-shield w-5"></i>
                            <span class="text-sm font-medium">Quản lý tài khoản</span>
                        </a>
                    @endif
                    <a href="{{ route('rooms.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                        <i class="fa-solid fa-door-open w-5"></i>
                        <span class="text-sm font-medium">Phòng học</span>
                    </a>
                    <a href="{{ route('devices.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                        <i class="fa-solid fa-desktop w-5"></i>
                        <span class="text-sm font-medium">Thiết bị</span>
                    </a>

                    <div class="pt-4 pb-1 px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Đặt lịch</div>

                    <a href="{{ route('bookings.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                        <i class="fa-solid fa-calendar-check w-5"></i>
                        <span class="text-sm font-medium">   {{ auth()->user()->isAdmin() ? 'Tổng hợp lịch mượn phòng' : 'Lịch mượn của tôi' }}</span>
                    </a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.bookings.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                            <i class="fa-solid fa-clipboard-check w-5"></i>
                            <span class="text-sm font-medium">Duyệt đặt lịch</span>
                        </a>
                    @endif

                    <div class="pt-4 pb-1 px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Hệ thống</div>

                    <a href="{{ route('issues.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                        <i class="fa-solid fa-triangle-exclamation w-5"></i>
                        <span class="text-sm font-medium">Sự cố</span>
                    </a>
                    <a href="{{ route('statistics.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                        <i class="fa-solid fa-chart-column w-5"></i>
                        <span class="text-sm font-medium">Thống kê</span>
                    </a>
                </nav>
            </div>

            <div class="border-t border-slate-800 pt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-lg text-red-400 hover:bg-slate-800 transition">
                        <i class="fa-solid fa-right-from-bracket w-5"></i>
                        <span class="text-sm font-medium">Đăng xuất</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- HEADER -->
            <header class="bg-white border-b border-gray-200 px-8 py-3.5 flex items-center justify-between sticky top-0 z-10 shadow-xs">
                <div class="relative w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" placeholder="Tìm kiếm nhanh..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                </div>

                <div class="flex items-center space-x-5">
                    <button class="relative text-gray-400 hover:text-gray-600 transition">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <div class="flex items-center space-x-3 border-l pl-5 border-gray-200">
                        <div class="text-right">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ auth()->user()->role ?? 'Thành viên' }}</p>
                            <p class="text-sm font-bold text-gray-700">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        </div>
                        <div class="w-9 h-9 bg-blue-600 text-white font-bold rounded-full flex items-center justify-center text-sm shadow-sm">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- NỘI DUNG -->
            <main class="p-8 flex-1 flex items-center justify-center">
                <div class="text-center text-gray-400 space-y-2">
                    <i class="fa-solid fa-chart-pie text-4xl text-gray-300"></i>
                    <p class="text-lg font-medium text-gray-600">Trang tổng quan hệ thống</p>
                    <p class="text-sm">Chọn các mục quản lý ở thanh bên trái để bắt đầu thao tác.</p>
                </div>
            </main>
        </div>

    </div>
</body>
</html>