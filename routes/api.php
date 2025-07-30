<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    Route::middleware(['auth.admin'])->group(function () {
        //
    });
});

Route::group(['prefix' => 'customer'], function () {
    Route::middleware(['auth.customer'])->group(function () {
        //
    });
});
