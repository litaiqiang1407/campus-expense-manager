<?php

use App\Http\Controllers\Auth\FacebookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\SignInController;

Route::middleware('guest')->group(function () {
    Route::get('/signup', [SignUpController::class, 'index'])->name('SignUp');
    Route::get('/signin', [SignInController::class, 'index'])->name('login');
    Route::post('/register', [SignUpController::class, 'store'])->name('signup.store');
    Route::post('/signin', [SignInController::class, 'store'])->name('login.store');
    Route::post('/check-email', [SignInController::class, 'checkEmail']);

});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('authorized/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('authorized/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

Route::post('logout', [LoginController::class, 'logout'])->name('Logout');
