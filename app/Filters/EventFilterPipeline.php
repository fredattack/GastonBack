<?php

namespace App\Filters;

use App\Filters\EventFilters\EndDateFilter;
use App\Filters\EventFilters\StartDateFilter;
use Illuminate\Pipeline\Pipeline;

class EventFilterPipeline extends Pipeline
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
            'start_date' => StartDateFilter::class,
            'end_date' => EndDateFilter::class,
        ];

        return collect($this->filters)
            ->filter(fn($value, $key) => isset($availableFilters[$key]) && $value !== null)
            ->map(fn($value, $key) => new $availableFilters[$key]($value))
            ->toArray();
    }
}
