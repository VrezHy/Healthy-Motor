<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DiagnosticQuestionController;

Route::get('/diagnostic-questions', [DiagnosticQuestionController::class, 'index']);