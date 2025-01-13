<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isFullDay' => $this->is_full_day,
            'type' => $this->type,
            'startDate' => $this->start_date->toIso8601String(),
            'endDate' => $this->end_date ? $this->end_date->toIso8601String() : null,
            'isRecurring' => $this->is_recurring,
            'recurrence' => $this->whenLoaded( 'recurrence', fn() => new RecurrenceResource( $this->recurrence ) ),
            'pets' => PetResource::collection( $this->whenLoaded( 'pets' ) ),
            'notes' => $this->notes,
            'createdAt' => $this->created_at->toIso8601String(),
        ];
    }
}

