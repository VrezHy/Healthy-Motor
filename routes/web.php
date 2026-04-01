<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KerusakanController;

// Route::get('/', function () {
//     return view('home');
// });


Route::get('/', function () {
    return redirect()->route('admin.kerusakan');
});

Route::get('/admin/kerusakan', [KerusakanController::class, 'index'])->name('admin.kerusakan');
Route::post('/admin/kerusakan', [KerusakanController::class, 'store'])->name('admin.kerusakan.store');
Route::put('/admin/kerusakan/{kerusakan}', [KerusakanController::class, 'update'])->name('admin.kerusakan.update');
Route::delete('/admin/kerusakan/{kerusakan}', [KerusakanController::class, 'destroy'])->name('admin.kerusakan.destroy');