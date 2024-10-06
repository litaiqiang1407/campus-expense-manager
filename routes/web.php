<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotFoundController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\SignInController;

Route::get('/', [WelcomeController::class, 'index'])->name('Welcome');
Route::get('/signup', [SignUpController::class, 'index'])->name('SignUp');
Route::get('/signin', [SignInController::class, 'index'])->name('SignIn');


Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('Home');
    Route::get('/transaction', [TransactionController::class, 'index'])->name('Transaction');
    Route::get('/notification', [NotificationController::class, 'index'])->name('Notification');
    Route::get('/account', [AccountController::class, 'index'])->name('Account');
});


require __DIR__ . '/auth.php';

Route::get('/{pathMath}', [NotFoundController::class, 'index'])->where('pathMath', '.*');