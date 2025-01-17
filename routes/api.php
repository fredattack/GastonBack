<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\ConsumeAiController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::prefix('v1-0-0')->middleware([
    EnsureFrontendRequestsAreStateful::class,
//    'auth:sanctum',
])->group(function () {
    Route::resource('/pets', PetController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
    ]);

    Route::get('/events/for-calendar', [EventController::class, 'getForCalendar']);
    Route::post('/events/change-done-status', [EventController::class, 'changeDoneStatus']);
    Route::resource('/events', EventController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
    ]);

    Route::post('/ai', ConsumeAiController::class);
});
