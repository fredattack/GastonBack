<?php

namespace App\Filters;

use App\Filters\EventFilters\EndDateFilter;
use App\Filters\EventFilters\OwnerFilter;
use App\Filters\EventFilters\StartDateFilter;
use Illuminate\Pipeline\Pipeline;

class EventFilterPipeline extends Pipeline
{

    public function __construct(protected array $filters = []){}

    public function pipes(): array
    {
        ray($this->filters)->blue();

        $availableFilters = [
            'owner_id' => OwnerFilter::class,
            'start_date' => StartDateFilter::class,
            'end_date' => EndDateFilter::class,
        ];

        return collect($this->filters)
            ->filter(fn($value, $key) => isset($availableFilters[$key]) && $value !== null)
            ->map(fn($value, $key) => new $availableFilters[$key]($this->filters))
            ->toArray();
    }
}
