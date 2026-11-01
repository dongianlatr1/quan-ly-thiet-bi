<?php

use App\Http\Controllers\AuthController;
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

    Route::middleware('role:admin')->group(function () {
        Route::view('/admin', 'admin')->name('admin');

        Route::patch('/rooms/{room}/lock', [RoomController::class, 'toggleLock'])->name('rooms.lock');
        Route::resource('rooms', RoomController::class)->except(['index', 'show']);

        Route::patch('/devices/{device}/lock', [DeviceController::class, 'toggleLock'])->name('devices.lock');
        Route::resource('devices', DeviceController::class)->except(['index', 'show']);
    });
});