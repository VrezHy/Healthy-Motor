<?php

use App\Http\Controllers\RegisterController;
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


Route::get('/admin/dashboard_admin', function () {
    return view('admin.dashboard_admin');
})->name('admin.dashboard');

Route::get('/mekanik/dashboard_mekanik', function () {
    return view('mekanik.dashboard_mekanik');
})->name('mekanik.dashboard');

