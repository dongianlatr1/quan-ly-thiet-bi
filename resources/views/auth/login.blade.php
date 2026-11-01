<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập hệ thống</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen grid md:grid-cols-2 bg-slate-100">

    <section class="hidden md:flex flex-col justify-between bg-[#1e3a5f] text-white p-12">
        <div class="text-xs font-semibold tracking-wider">TRƯỜNG ĐẠI HỌC VINH</div>
        <div>
            <span class="inline-block text-xs bg-white/10 rounded-md px-3 py-1 mb-6">KẾT NỐI NGUỒN LỰC GIẢNG DẠY</span>
            <h1 class="text-5xl font-semibold leading-tight mb-5">Sẵn sàng cho mỗi<br>giờ lên lớp.</h1>
            <p class="text-slate-300 max-w-md leading-relaxed">
                Một nơi để tra cứu thiết bị, đặt phòng học và quản lý lịch mượn giảng dạy.
                Thuận tiện, minh bạch và đồng bộ.
            </p>
        </div>
        <div class="text-xs text-slate-400">© {{ date('Y') }} Trường Đại học Vinh</div>
    </section>

    <section class="flex items-center justify-center p-6">
        <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
            <p class="text-xs font-semibold text-blue-600 tracking-wide">CHÀO MỪNG TRỞ LẠI</p>
            <h2 class="text-2xl font-bold text-slate-900 mt-1">Hệ thống Quản lý Thiết bị Giảng dạy</h2>
            <p class="text-sm text-slate-500 mt-2 mb-6">Đăng nhập để sử dụng hệ thống.</p>

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">
                    <p class="font-semibold">Đăng nhập không thành công</p>
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="username" class="text-sm font-medium text-slate-700">Tên đăng nhập</label>
                    <input id="username" name="username" value="{{ old('username') }}" autofocus autocomplete="username"
                           class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password" class="text-sm font-medium text-slate-700">Mật khẩu</label>
                    <div class="relative mt-1">
                        <input id="password" type="password" name="password" autocomplete="current-password"
                               class="w-full border rounded-lg px-3 py-2.5 pr-16 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('login') ? 'border-red-400' : 'border-slate-300' }}">
                        <button type="button" id="togglePw" class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">Hiện</button>
                    </div>
                </div>

                <div class="text-sm">
                    <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">Đăng ký tài khoản</a>
                </div>

                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg py-2.5 transition">
                    Đăng nhập →
                </button>
            </form>

            <p class="text-xs text-slate-400 mt-5">Không chia sẻ mật khẩu để bảo vệ thông tin cá nhân.</p>
        </div>
    </section>

    <script>
        const pw = document.getElementById('password');
        const btn = document.getElementById('togglePw');
        btn.addEventListener('click', () => {
            const show = pw.type === 'password';
            pw.type = show ? 'text' : 'password';
            btn.textContent = show ? 'Ẩn' : 'Hiện';
        });
    </script>
</body>
</html>