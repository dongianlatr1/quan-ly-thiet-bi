@extends('layouts.app')

@section('title', 'Không có quyền')

@section('content')
    <div class="text-center py-20">
        <p class="text-6xl font-bold text-slate-300">403</p>
        <h1 class="text-xl font-semibold mt-4">Bạn không có quyền truy cập</h1>
        <p class="text-slate-500 mt-2">{{ $exception->getMessage() ?: 'Vui lòng liên hệ quản trị viên.' }}</p>
        <a href="{{ route('dashboard') }}" class="inline-block mt-6 bg-blue-600 text-white px-4 py-2 rounded-lg">Về trang chủ</a>
    </div>
@endsection