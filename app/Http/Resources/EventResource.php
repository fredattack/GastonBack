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
            'petId' => $this->pets?->pluck('id'),
            'title' => $this->title,
            'is_full_day' => $this->is_full_day,
            'type' => $this->type,
            'start_date' => $this->start_date->toIso8601String(),
            'end_date' => $this->end_date ? $this->end_date->toIso8601String() : null,
            'is_recurring' => $this->is_recurring,
            'recurrence' => $this->whenLoaded( 'recurrence', fn() => new RecurrenceResource( $this->recurrence ) ),
            'pets' => PetResource::collection( $this->whenLoaded( 'pets' ) ),
            'notes' => $this->notes,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}

