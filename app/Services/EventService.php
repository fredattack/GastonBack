<?php

namespace App\Services;

use App\Http\Resources\EventResource;
use App\Http\Resources\EventResourceCollection;
use App\Repositories\EventRepository;

class EventService {
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository) {
        $this->eventRepository = $eventRepository;
    }

    public function getAllEvents() {
        return new EventResourceCollection($this->eventRepository->all());
    }

    public function getEventById($id) {
        return $this->eventRepository->find($id);
    }

    public function createEvent(array $data) {
        return new EventResource( $this->eventRepository->create($data));
    }

    public function updateEvent($id, array $data) {
        return $this->eventRepository->update($id, $data);
    }

    public function deleteEvent($id) {
        $this->eventRepository->delete($id);
    }
}
