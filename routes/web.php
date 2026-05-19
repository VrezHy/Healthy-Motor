<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController; // Tambahan import AdminController
use App\Http\Controllers\LoginController; // Tambahan import LoginController
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [RegisterController::class, 'register']) -> name('register.regis');

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', [RegisterController::class, 'login']) -> name('login.login');

// UBAH: Menggunakan AdminController agar bisa mengirim data motor ke tampilan
Route::get('/admin/dashboard_admin', [AdminController::class, 'index'])->name('admin.dashboard');

Route::get('/mekanik/dashboard_mekanik', function () {
    return view('mekanik.dashboard_mekanik');
})->name('mekanik.dashboard');

// TAMBAHAN: Route untuk memproses Logout
Route::post('/logout', [LoginController::class, 'logout']) -> name('logout');