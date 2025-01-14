<?php

use App\Http\Controllers\Api\Event\GetEventsForCalendarController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\ConsumeAiController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::prefix( 'v1-0-0' )->middleware( [
    EnsureFrontendRequestsAreStateful::class,
//    'auth:sanctum'
])->group( function() {
    Route::resource( '/pets', PetController::class );

    Route::resource( '/events', EventController::class );

    Route::get( '/events', [EventController::class,'getForCalendar']);

    Route::post( '/ai', ConsumeAiController::class,);
});
