<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\CustomerAuthController;

// customer auth
Route::post('login', [CustomerAuthController::class, 'login']);
Route::post('register', [CustomerAuthController::class, 'register']);

Route::middleware(['auth.customer'])->group(function () {
    // log out
    Route::post('logout', [CustomerAuthController::class, 'customerLogout']);
});

// admin auth
Route::group(['prefix' => 'admin'], function () {
    // login register
    Route::post('login', [AdminAuthController::class, 'login']);
    // admin auth info
    Route::middleware(['auth.admin'])->group(function () {
        // log out
        Route::post('logout', [AdminAuthController::class, 'logout']);
    });
});
