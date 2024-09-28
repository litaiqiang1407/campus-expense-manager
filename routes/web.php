<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/welcome', [WelcomeController::class, 'index'])->name('index');

Route::get('/{pathMath}', function () {
    return view('welcome');
})->where('pathMath', '.*');