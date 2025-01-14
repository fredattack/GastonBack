<?php



namespace App\Filters\EventFilters;

    use Illuminate\Database\Eloquent\Builder;

    class StartDateFilter
{

    public function __construct(protected array $filters)
    {

    }

    public function __invoke(Builder $query)
    {
        if (!isset($this->filters['start_date']) || !isset($this->filters['end_date'])) {
            return $query;
        }

        //start_date is equal of after date
        return $query->whereBetween( 'start_date', [$this->filters['start_date'], $this->filters['end_date']] );
    }
}

