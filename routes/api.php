<?php

use App\Http\Controllers\Api\Event\GetEventsController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\ConsumeAiController;
use App\Http\Controllers\CreateEventsController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::prefix( 'v1-0-0' )->middleware( [
    EnsureFrontendRequestsAreStateful::class,
//    'auth:sanctum'
])->group( function() {
    Route::get( '/pets', [PetController::class, 'index'] );
    Route::post( '/pets', [PetController::class, 'store'] );
    Route::get( '/events', GetEventsController::class);
    Route::post( '/events', CreateEventsController::class);

    Route::post( '/ai', ConsumeAiController::class,);
});
