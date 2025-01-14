<?php


// EventRepository.php
namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Event;

class EventRepository implements RepositoryInterface
{

    public function getForPeriodeOrWithRecurrence($periode)
    {
        return Event::with( ['recurrence','pets'] )
            ->where( function($q) use ($periode) {
                $q->whereBetween( 'start_date', $periode );
            } )->orWhereHas( 'recurrence' )
            ->get();
    }

    public function all()
    {
        $collection = Event::filter()->with( 'recurrence' )->get();
        return $collection;
    }

    public function find($id)
    {
        return Event::with( 'recurrence' )->findOrFail( $id );
    }

    public function create(array $data)
    {

        $petIds = \Arr::wrap( \Arr::pull( $data, 'petId' ) );
        $recurrence = \Arr::pull( $data, 'recurrence' );
        $event = Event::create( $data );

        if ( $data['is_recurring'] && $recurrence ){
            $event->recurrence()->create( $recurrence );
        }
        $event->pets()->attach( $petIds );
        $event->load( ['recurrence', 'pets'] );
        return $event;
    }

    public function update($id, array $data)
    {
        $event = $this->find( $id );
        $event->update( $data );
        if ( isset( $data['recurrence'] ) ){
            $event->recurrence()->updateOrCreate( [], $data['recurrence'] );
        }
        return $event;
    }

    public function delete($id)
    {
        $event = $this->find( $id );
        $event->delete();
    }
}
