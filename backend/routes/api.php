<?php

use App\Http\Controllers\Api\CalendarController;
use Illuminate\Support\Facades\Route;

Route::controller(CalendarController::class)->group(function () {
    Route::name('calendar.')->prefix('/calendar')->group(function () {
        Route::get('/month', 'month')->name('get-month');
        Route::get('/absence-types', 'getAbsenceTypes')->name('get-absence-types');
        Route::get('/absences', 'getAbsences')->name('get-absences');
        Route::post('/create-absence', 'createAbsence')->name('create-absence');
        Route::get('/send-to-archive', 'sendToArchive')->name('send-to-archive');
        Route::get('/get-archive', 'getArchive')->name('get-archive');     
    });
});
