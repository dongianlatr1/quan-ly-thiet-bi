<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');

    // Đặt lịch mượn phòng, thiết bị (giảng viên và admin)
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Sự cố
    Route::get('/issues', function () {
        return view('su-co');
    })->name('issues.index');

    // Thống kê
    Route::get('/statistics', function () {
        return view('thong-ke');
    })->name('statistics.index');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', function () {
            $users = \App\Models\User::all();

            return view('admin', compact('users'));
        })->name('admin');

        // Duyệt, từ chối đặt lịch
        Route::get('/admin/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
        Route::patch('/admin/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('admin.bookings.approve');
        Route::patch('/admin/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('admin.bookings.reject');

        Route::patch('/rooms/{room}/lock', [RoomController::class, 'toggleLock'])->name('rooms.lock');
        Route::resource('rooms', RoomController::class)->except(['index', 'show']);

        Route::patch('/devices/{device}/lock', [DeviceController::class, 'toggleLock'])->name('devices.lock');
        Route::resource('devices', DeviceController::class)->except(['index', 'show']);
    });
});