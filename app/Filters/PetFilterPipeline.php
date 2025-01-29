<?php

namespace App\Filters;

use Illuminate\Pipeline\Pipeline;

class PetFilterPipeline extends Pipeline
{

    public function __construct(protected array $filters = [])
    {
        parent::__construct(app());
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
