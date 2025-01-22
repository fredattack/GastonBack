<?php


// EventRepository.php
namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Event;
use App\Models\EventOccurrence;
use Carbon\Carbon;

class EventRepository implements RepositoryInterface
{

    public function getForPeriodeOrWithRecurrence($periode)
    {
        return Event::with( ['recurrence', 'pets', 'occurrences'] )
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
        $petsDetails = \Arr::pull( $data, 'pets' );

        $event = Event::create( $data );

        if ( $data['is_recurring'] && $recurrence ){
            $event->recurrence()->create( $recurrence );
        }

        foreach ( $petsDetails as $petDetail ) {

            $formattedPetsDetails[$petDetail['pivot']['pet_id']] = [
                'detail_type' => $data['type'] == 'medical' ? 'medic' : 'food',
                'item' => $petDetail['pivot']['item'],
                'quantity' => $petDetail['pivot']['quantity'],
                'notes' => $petDetail['pivot']['notes']
            ];
        }

        if ( isset( $formattedPetsDetails ) ){
            $event->pets()->attach( $formattedPetsDetails );
        } else {
            $event->pets()->attach( $petIds );
        }

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

    public function delete($id,$withRecurrence)
    {
        ray()->clearScreen();
        try {
            $event = $this->find( $id );
            if ( $withRecurrence || !$event->has( 'recurrence' ) ){
                $event->delete();
            } else {
                ray(request()->date)->orange();
//                $cleanDateString = str_replace(' ', 'T', request()->date);
                $date = substr( request()->date, 0,10);
                ray( $date )->purple();
                EventOccurrence::updateOrCreate( ['event_id' => $event->id, 'occurrence_date' => $date], ['is_deleted' => true] );
            }
            return true;
        } catch ( \Exception $e ) {
            throw $e;
        }
    }
}
