<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Admin\AdminServiceController;

Route::middleware(['auth.admin'])->group(function () {
    // booking
    Route::get('admin/booking', [AdminServiceController::class, 'bookingList']);
    // service
    Route::get('services', [AdminServiceController::class, 'index']);
    Route::post('services', [AdminServiceController::class, 'store']);
    Route::put('services/{id}', [AdminServiceController::class, 'update']);
    Route::delete('services/{id}', [AdminServiceController::class, 'delete']);
});

Route::get('service', [BookingController::class, 'serviceList']);

Route::middleware(['auth.customer'])->group(function () {
    Route::post('bookings', [BookingController::class, 'bookings']);
});
