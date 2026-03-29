<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KerusakanController;

// Route::get('/', function () {
//     return view('home');
// });


Route::get('/', function () {
    return redirect()->route('admin.kerusakan');
});

Route::resource('admin/kerusakan', KerusakanController::class)
    ->names([
        'index'   => 'admin.kerusakan',
        'store'   => 'admin.kerusakan.store',
        'update'  => 'admin.kerusakan.update',
        'destroy' => 'admin.kerusakan.destroy',
    ])
    ->only(['index', 'store', 'update', 'destroy']);