<?php



namespace App\Filters\EventFilters;

    use Illuminate\Database\Eloquent\Builder;

class OwnerFilter
{

    public function __construct(protected $user_id)
    {

    }

    public function __invoke(Builder $query)
    {
        if(!$this->user_id) {
            return $query;
        }
        return $query->where( 'owner_id', '=', $this->user_id );
    }
}

