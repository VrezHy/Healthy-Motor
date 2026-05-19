<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController; // Tambahan import AdminController
use App\Http\Controllers\LoginController; // Tambahan import LoginController
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KerusakanController;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\SolusiController;


Route::get('/', function () {
    return view('home');
});




Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.regis');

Route::post('/login', [RegisterController::class, 'login'])->name('login.login');

// UBAH: Menggunakan AdminController agar bisa mengirim data motor ke tampilan
Route::get('/admin/dashboard_admin', [AdminController::class, 'index'])->name('admin.dashboard');

Route::get('/mekanik/dashboard_mekanik', function () {
    return view('mekanik.dashboard_mekanik');
})->name('dashboard.mekanik');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.login');
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/admin/kerusakan', [KerusakanController::class, 'index'])->name('admin.kerusakan');
Route::post('/admin/kerusakan', [KerusakanController::class, 'store'])->name('admin.kerusakan.store');
Route::put('/admin/kerusakan/{kerusakan}', [KerusakanController::class, 'update'])->name('admin.kerusakan.update');
Route::delete('/admin/kerusakan/{kerusakan}', [KerusakanController::class, 'destroy'])->name('admin.kerusakan.destroy');

// Gejala
Route::get('/admin/gejala', [GejalaController::class, 'index'])->name('admin.gejala');
Route::post('/admin/gejala', [GejalaController::class, 'store'])->name('admin.gejala.store');
Route::put('/admin/gejala/{gejala}', [GejalaController::class, 'update'])->name('admin.gejala.update');
Route::delete('/admin/gejala/{gejala}', [GejalaController::class, 'destroy'])->name('admin.gejala.destroy');

// Solusi
Route::get('/admin/solusi', [SolusiController::class, 'index'])->name('admin.solusi');
Route::post('/admin/solusi', [SolusiController::class, 'store'])->name('admin.solusi.store');
Route::put('/admin/solusi/{solusi}', [SolusiController::class, 'update'])->name('admin.solusi.update');
Route::delete('/admin/solusi/{solusi}', [SolusiController::class, 'destroy'])->name('admin.solusi.destroy');
// TAMBAHAN: Route untuk memproses Logout
Route::post('/logout', [LoginController::class, 'logout']) -> name('logout');
