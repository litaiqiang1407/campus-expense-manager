<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\MyWalletController;
use App\Http\Controllers\NotFoundController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;


Route::get('/welcome', [WelcomeController::class, 'index'])->name('Welcome');


Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('Home');
    Route::get('/transaction', [TransactionController::class, 'index'])->name('Transaction');
    Route::get('/notification', [NotificationController::class, 'index'])->name('Notification');
    Route::get('/account', [AccountController::class, 'index'])->name('Account');

    Route::group(['prefix' => 'my-wallet'], function () {
        Route::get('/', [MyWalletController::class, 'index'])->name('MyWallet');
        Route::get('/{walletTypeId}/create', [MyWalletController::class, 'create'])->name('CreateWallet');
        Route::get('/edit/{walletId}', [MyWalletController::class, 'edit'])->name('EditWallet');
    });

        Route::get('/icon', [IconController::class, 'index'])->name('Icon');
});


require __DIR__ . '/auth.php';

Route::get('/{pathMath}', [NotFoundController::class, 'index'])->where('pathMath', '.*');