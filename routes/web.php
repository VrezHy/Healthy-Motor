<?php

use Illuminate\Support\Facades\Route;


Route::get('/mekanik/diagnosa', function () {
    return view('mekanik.diagnostics');
});