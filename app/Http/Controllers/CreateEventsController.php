<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventFormRequest;
use App\Services\EventService;
use Illuminate\Http\Request;

class CreateEventsController extends Controller
{
    public function __invoke(EventFormRequest $request, EventService $eventService)
    {
        return $eventService->createEvent($request->validated());
    }
}
