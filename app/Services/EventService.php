<?php

namespace App\Services;

use App\Http\Resources\EventResource;
use App\Http\Resources\EventResourceCollection;
use App\Models\Event;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EventService
{

    public function __construct(private EventRepository $eventRepository)
    {
    }


    public function getEventsWithOccurrences(array $filter)
    {
        $startDate = Carbon::parse( $filter['start_date'] );
        $endDate = Carbon::parse( $filter['end_date'] );

        $events = $this->eventRepository->getForPeriodeOrWithRecurrence( [$startDate, $endDate] );

        $finalEvents = collect();

        foreach ( $events as $event ) {
            // Ajouter les événements simples dans la période
            if ( $event->start_date->between( $startDate, $endDate ) ){
                $finalEvents->push( $event );
            }else{


            if ( $event->is_recurring && $event->recurrence ){
                $finalEvents = $finalEvents->merge(
                    $this->generateRecurrences( $event, $startDate, $endDate )
                );
            }
            }
        }

        return new EventResourceCollection( $finalEvents->sortBy( 'start_date' ) );
    }

    /**
     * Génère les occurrences d'un événement récurrent dans une période donnée
     */
    private function generateRecurrences(Event $event, $startDate, $endDate): Collection
    {
        $occurrences = collect();

        $recurrence = $event->recurrence;
        /** @var \App\Models\Recurrence $recurrence */
        $currentDate = Carbon::parse( $event->start_date );
        $count = 0;
        ray( $currentDate)->red();
        ray($endDate)->blue();
        while ( true ) {
            if ( $currentDate->gt( $endDate ) ){
                break;
            } if ( ($recurrence->end_date && $currentDate->gt( Carbon::parse( $recurrence->end_date ) || $currentDate->gt( $endDate ) ))){
                break;
            }

            if ( $recurrence->occurrences && $count >= $recurrence->occurrences ){
                break;
            }

            if ( $currentDate->between( $startDate, $endDate ) ){
                $startTime = $event->start_date->format('H:i:s');

                $occurrence = clone $event;
                $occurrence->id = null; // Pour éviter la duplication d'ID
                $occurrence->master_id = $event->id;

                $occurrence->start_date = $currentDate->setTimeFromTimeString( $startTime );


                if( $event->end_date ){
                    $occurrence->end_date = $currentDate->setTimeFrom( $event->end_date );
                }
                //if ! $occurences conatin the event
                if(!$occurrences->where('id', $event->id)->count()){
                    $occurrences->push( $occurrence );
                }
            }

            $count++;
            $currentDate = $this->incrementRecurrence( $currentDate, $recurrence );
        }

        return $occurrences;
    }

    /**
     * Incrémente la date selon la fréquence de récurrence
     */
    private function incrementRecurrence(Carbon $date, $recurrence): Carbon
    {
        switch ( $recurrence->frequency_type ) {
            case 'daily':
                return $date->addDays( $recurrence->frequency );
            case 'weekly':
                return $date->addWeeks( $recurrence->frequency );
            case 'monthly':
                return $date->addMonths( $recurrence->frequency );
            default:
                return $date;
        }
    }


    public function getAllEvents()
    {
        return new EventResourceCollection( $this->eventRepository->all() );
    }

    public function getById($id)
    {
        return $this->eventRepository->find( $id );
    }

    public function create(array $data)
    {
        return new EventResource( $this->eventRepository->create( $data ) );
    }

    public function update($id, array $data)
    {
        return $this->eventRepository->update( $id, $data );
    }

    public function delete($id)
    {
        $this->eventRepository->delete( $id );
    }
}
