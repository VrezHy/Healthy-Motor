<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/register', function () {
    return view('register');
});

Route::POST('/register', [RegisterController::class, 'regis']) -> name('register.regis');

Route::get('/login', function () {
    return view('login');
});

Route::POST('/login', [RegisterController::class, 'login']) -> name('login.login');

