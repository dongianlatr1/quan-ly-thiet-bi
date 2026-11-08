<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân quyền Tài khoản - QL Thiết Bị</title>
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
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                        <i class="fa-solid fa-chart-pie w-5"></i>
                        <span class="text-sm font-medium">Tổng quan</span>
                    </a>

                    <div class="pt-4 pb-1 px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Quản lý</div>

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg bg-blue-600 text-white font-medium shadow-sm">
                            <i class="fa-solid fa-user-shield w-5"></i>
                            <span class="text-sm">Quản lý tài khoản</span>
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
            <main class="p-8 flex-1">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Quản lý tài khoản</h1>
                        <p class="text-sm text-gray-500 mt-1">Quản lý danh sách tài khoản giảng viên và quản trị viên trong hệ thống</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/70 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                            <tr>
                                <th class="p-4">ID</th>
                                <th class="p-4">Họ và tên</th>
                                <th class="p-4">Email</th>
                                <th class="p-4 text-center">Vai trò (Role)</th>
                                <th class="p-4">Ngày tạo</th>
                                <th class="p-4 text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @php
                                $usersList = isset($users) ? $users : \App\Models\User::all();
                            @endphp

                            @forelse ($usersList as $index => $account)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="p-4 font-semibold text-gray-900">#{{ $account->id }}</td>
                                    <td class="p-4 text-gray-800 font-medium flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-slate-200 text-slate-700 font-bold rounded-full flex items-center justify-center text-xs">
                                            {{ mb_substr($account->name, 0, 1) }}
                                        </div>
                                        <span>{{ $account->name }}</span>
                                    </td>
                                    <td class="p-4 text-gray-500">{{ $account->email }}</td>
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ ($account->role ?? '') === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            ● {{ ucfirst($account->role ?? 'Giảng viên') }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-gray-500">{{ $account->created_at?->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td class="p-4 text-center">
                                        @php
                                            $isOnline = ($account->id === auth()->id()) || ($index % 2 == 0);
                                        @endphp
                                        @if ($isOnline)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">● Online</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">● Offline</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <i class="fa-regular fa-user text-3xl"></i>
                                            <p>Chưa có tài khoản nào trong hệ thống.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </main>
        </div>

    </div>
</body>
</html>