<?php

namespace App\Filters;

use Illuminate\Pipeline\Pipeline;

class PetFilterPipeline extends Pipeline
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function pipes(): array
    {
        $availableFilters = [
            'owner_id' => \App\Filters\OwnerFilter::class,
        ];

        return collect($this->filters)
            ->filter(fn($value, $key) => isset($availableFilters[$key]) && $value !== null)
            ->map(fn($value, $key) => new $availableFilters[$key]($value))
            ->toArray();
    }
}
