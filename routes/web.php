<?php

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Trang chính
Route::get('/', function () {
    return view('welcome');
});

Route::get('/transaction', [TransactionController::class, 'index'])->name('Transaction');
Route::get('/{pathMath}', function () {
    return view('welcome');
})->where('pathMath', '.*');
