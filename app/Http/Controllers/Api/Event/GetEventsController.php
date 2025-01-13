<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Services\EventService;

class GetEventsController extends Controller
{
    public function __invoke(EventService $eventService)
    {
        return $eventService->getAllEvents();
    }
}
