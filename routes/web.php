<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.regis');
// Route::get('/login', function () {
//     return view('login');
// });

Route::post('/login', [RegisterController::class, 'login'])->name('login.login');


Route::get('/admin/dashboard_admin', function () {
    return view('admin.dashboard_admin');
})->name('dashboard.admin');

Route::get('/mekanik/dashboard_mekanik', function () {
    return view('mekanik.dashboard_mekanik');
})->name('dashboard.mekanik');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.login');
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
