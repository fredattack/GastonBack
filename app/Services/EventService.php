<?php

namespace App\Services;

use App\Http\Resources\EventResource;
use App\Http\Resources\EventResourceCollection;
use App\Models\Event;
use App\Models\EventOccurrence;
use App\Models\Recurrence;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use DB;
use Exception;
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

            if ( $event->start_date->between( $startDate, $endDate ) ){
                $finalEvents->push( $event );
            } elseif ($event->is_recurring && $event->recurrence) {
                    $finalEvents = $finalEvents->merge(
                        $this->generateRecurrences( $event, $startDate, $endDate )
                    );
            }
        }

        return new EventResourceCollection( $finalEvents->sortBy( 'start_date' ) );
    }


    private function generateRecurrences(Event $event, $startDate, $endDate): Collection
    {
        $occurrences = collect();

        $recurrence = $event->recurrence;
        /** @var \App\Models\Recurrence $recurrence */
        $currentDate = Carbon::parse( $event->start_date );
        $count = 0;

        while ( true ) {
            // Check if the current date is greater than the end date. If true, exit the loop.
            if ( $currentDate->gt( $endDate ) ){
                break;
            }

            // Check if the recurrence has an end date and if the current date is greater than the recurrence end date or the end date. If true, exit the loop.
            if (
                ($recurrence->end_date && $currentDate->gt(Carbon::parse($recurrence->end_date)))
                || $currentDate->gt($endDate)
            ) {
                break;
            }

            // Check if the number of generated occurrences has reached the allowed maximum. If true, exit the loop.
            if ( $recurrence->occurrences && $count >= $recurrence->occurrences ){
                break;
            }

            // Check if there is an occurrence with the current date marked as deleted
            // and if the current date is within the start and end date range. If true, exit the loop.
            if ( $event->occurrences->where( 'occurrence_date', $currentDate->toDateString() )->where( 'is_deleted', true )->count() && $currentDate->between( $startDate, $endDate ) ){
                break;
            }

            if ( $currentDate->between( $startDate, $endDate ) ){
                $startTime = $event->start_date->format( 'H:i:s' );

                $occurrence = clone $event;
                $occurrence->id = null;
                $occurrence->master_id = $event->id;

                $occurrence->start_date = $currentDate->setTimeFromTimeString( $startTime );
                $occurrence->is_done = $event->occurrences->where( 'occurrence_date', $currentDate->toDateString() )->where( 'is_done', true )->count();

                if ( $event->end_date ){
                    $occurrence->end_date = $currentDate->setTimeFrom( $event->end_date );
                }

                if ( !$occurrences->where( 'id', $event->id )->count() ){
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
        DB::beginTransaction();

        try {
            $event = Event::find($id);
            $targetDate = request()->input('date');
            $withRecurrences = request()->input('with_recurrences', false);
            if ($event->is_recurring && $withRecurrences) {

                $event->recurrence()->update([
                    'end_date' => Carbon::parse($targetDate)->toDateString(),
                ]);

                $newEvent = $event->create([
                    ...\Arr::except( $data, Event::EXCEPTED_UPDATE_FIELDS),
                    'start_date' => Carbon::parse($targetDate)->setTimeFromTimeString($event->start_date->toTimeString()),
                    ]);

                if (!empty($data['recurrence'])) {
                    $newEvent->recurrence()->create($data['recurrence']);
                }

            } elseif ($event->is_recurring && !$withRecurrences) {
                $this->eventRepository->updateSingleOccurrence($id, $targetDate, [
                    'custom_title' => $data['title'] ?? $event->title,
                    'custom_notes' => $data['notes'] ?? $event->notes,
                    'custom_start_time' => Carbon::parse($data['start_date'])->format('H:i:s'),
                    'custom_end_time' => Carbon::parse($data['end_date'])->format('H:i:s'),
                ]);
            } else {
                // Mise à jour d'un événement non récurrent
                $event->update(\Arr::except( $data, EVENT::EXCEPTED_UPDATE_FIELDS));
            }
            DB::commit();

            return $event->load(['recurrence', 'pets', 'occurrences']);
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Update failed: " . $e->getMessage());
        }
    }


    public function delete($id,$withRecurrence = false)
    {


        $this->eventRepository->delete( $id ,$withRecurrence);
    }

    public function changeDoneStatus(array $payload)
    {
        if(is_null( $payload['master_id'])){
            $event = $this->eventRepository->find( $payload['id'] );
            $event->is_done = !$event->is_done;
            $event->save();
            return new EventResource( $event );
        } else {
            $event = $this->eventRepository->find( $payload['master_id'] );
            $date = Carbon::parse( $payload['date'] )->toDateString();
            ray($date);
            EventOccurrence::updateOrCreate( ['event_id'=>$event->id,'occurrence_date'=> $date],['is_done' => $payload['is_done']] );
            return new EventResource( $event );
        }
    }
}
