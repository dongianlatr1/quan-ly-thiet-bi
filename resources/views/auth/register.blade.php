<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký tài khoản</title>
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
            <p class="text-xs font-semibold text-blue-600 tracking-wide">TẠO TÀI KHOẢN MỚI</p>
            <h2 class="text-2xl font-bold text-slate-900 mt-1">Đăng ký tài khoản</h2>
            <p class="text-sm text-slate-500 mt-2 mb-6">Điền thông tin bên dưới để bắt đầu sử dụng hệ thống.</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="text-sm font-medium text-slate-700">Họ và tên</label>
                    <input id="name" name="name" value="{{ old('name') }}" autofocus autocomplete="name"
                           class="mt-1 w-full border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }}">
                    @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="username" class="text-sm font-medium text-slate-700">Tên đăng nhập</label>
                    <input id="username" name="username" value="{{ old('username') }}" autocomplete="username"
                           class="mt-1 w-full border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('username') ? 'border-red-400' : 'border-slate-300' }}">
                    @error('username')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                           class="mt-1 w-full border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }}">
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="text-sm font-medium text-slate-700">Mật khẩu</label>
                    <input id="password" type="password" name="password" autocomplete="new-password"
                           class="mt-1 w-full border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('password') ? 'border-red-400' : 'border-slate-300' }}">
                    @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="text-sm font-medium text-slate-700">Nhập lại mật khẩu</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
                           class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg py-2.5 transition">
                    Đăng ký →
                </button>
            </form>

            <p class="text-sm text-slate-600 mt-5">
                Đã có tài khoản?
                <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Đăng nhập</a>
            </p>
        </div>
    </section>
</body>
</html>