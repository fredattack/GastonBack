<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PetResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'data' => PetResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count(),
                'generated_at' => now()->toIso8601String(),
            ],
        ];
    }
}
