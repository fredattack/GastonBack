<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'species'    => $this->species,
            'breed'      => $this->breed,
            'birthDate'  => $this->birth_date,
            'isActive'   => $this->is_active,
            'order'      => $this->order,
            'ownerId'    => $this->owner_id,
            'photo'      => '$this->photo',
            'galerie'    => 'json_decode($this->galerie, true) ?? []',
            'pivot' => $this->when(isset($this->pivot), function () {
                return [
                    'detail_type' => $this->pivot->detail_type ?? null,
                    'item' => $this->pivot->item ?? null,
                    'quantity' => $this->pivot->quantity ?? null,
                    'notes' => $this->pivot->notes ?? null,
                ];
            }),
            'createdAt'  => $this->created_at->toIso8601String(),
            'updatedAt'  => $this->created_at->toIso8601String(),
        ];
    }
}
