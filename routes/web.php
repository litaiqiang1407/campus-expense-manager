<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{pathMath}', function () {
    return view('welcome');
})->where('pathMath', '.*');