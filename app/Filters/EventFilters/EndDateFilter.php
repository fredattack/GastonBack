<?php



namespace App\Filters\EventFilters;

    use Illuminate\Database\Eloquent\Builder;

    class EndDateFilter
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
            return $query->where( 'end_date', '>=', $this->date )
                ->orWhereNull();
        }
}

