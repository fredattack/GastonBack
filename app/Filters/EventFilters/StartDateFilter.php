<?php



namespace App\Filters\EventFilters;

    use Illuminate\Database\Eloquent\Builder;

    class StartDateFilter
{

    public function __construct(protected $date)
    {

    }

    public function __invoke(Builder $query)
    {
        if(!$this->date) {
            return $query;
        }
        //start_date is equal of after date
        return $query->where( 'start_date', '>=', $this->date );
    }
}

