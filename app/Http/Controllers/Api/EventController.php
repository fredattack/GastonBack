<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventFormRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(public EventService $eventService){

    }

    public function index()
    {
        return response()->json($this->eventService->getAllEvents());
    }

    public function store(EventFormRequest $request, EventService $eventService)
    {
        return response()->json($eventService->create($request->validated()));
    }

    public function show($id)
    {
        return response()->json($this->eventService->getById($id));
    }


    public function update(EventFormRequest $request, Event $event)
    {
        return response()->json($this->eventService->update($request->validated(), $event));
    }

    public function destroy(Event $event)
    {
    }

    public function getForCalendar()
    {

        if(!request()->has('filters')) {
            abort( 400, 'Filters are required' );
        }

        return $this->eventService->getEventsWithOccurrences(request()->all()['filters']);
    }

    public function changeDoneStatus(Request $request)
    {
        ray()->clearScreen();
        if(!request()->has('id') && !request()->has('master_id')) {
            abort( 400, 'Event ID is required' );
        }

        $validated = $request->validate( [
            'id' => 'sometimes|nullable|integer',
            'master_id' => 'sometimes|nullable|integer',
            'is_done' => 'required|boolean',
            'date'=> 'required|string',
        ]);

        ray($validated)->orange();
        return response()->json($this->eventService->changeDoneStatus($validated));
    }
}
