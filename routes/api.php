<?php

use App\Http\Controllers\AdvertiserController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('advertisers')->name('advertisers.')->group(function () {
    Route::get('/', [AdvertiserController::class, 'index'])->name('index');
    Route::get('/{uuid}', [AdvertiserController::class, 'show'])->name('show');
    Route::get('/{uuid}/notifications', [AdvertiserController::class, 'notifications'])->name('appointments.notifications');
    Route::get('/{uuid}/availability', [AdvertiserController::class, 'availability'])->name('availability');
    Route::get('/{uuid}/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/{uuid}/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});

Route::prefix('appointments')->name('appointments.')->group(function () {
    Route::put('/{uuid}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
    Route::put('/{uuid}/start', [AppointmentController::class, 'start'])->name('start');
    Route::put('/{uuid}/finish', [AppointmentController::class, 'finish'])->name('finish');
});
