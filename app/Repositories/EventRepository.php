<?php


// EventRepository.php
namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Event;
use App\Models\EventOccurrence;
use Arr;
use Carbon\Carbon;
use DB;
use Exception;

class EventRepository implements RepositoryInterface
{



    public function getForPeriodeOrWithRecurrence($periode)
    {
        return Event::with( ['recurrence', 'pets', 'occurrences'] )->where( function($q) use ($periode) {
            $q->whereBetween( 'start_date', $periode );
        } )->orWhereHas( 'recurrence' )->get();
    }

    public function all()
    {
        $collection = Event::filter()->with( 'recurrence' )->get();
        return $collection;
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {

            $petIds = Arr::wrap( Arr::pull( $data, 'petId' ) );
            $recurrence = Arr::pull( $data, 'recurrence' );
            $petsDetails = Arr::pull( $data, 'pets' );
            $event = Event::create( Arr::except($data, Event::EXCEPTED_UPDATE_FIELDS) );

            if ( $data['is_recurring'] && $recurrence ){
                $event->recurrence()->create( $recurrence );
            }
            foreach ( $petsDetails as $petDetail ) {
                if(!isset(  $formattedPetsDetails[$petDetail['id']])){
                    throw new Exception( "Pet not added to details" );
                }
                $formattedPetsDetails[$petDetail['id']] = ['detail_type' => $data['type'] == 'medical' ? 'medic' : 'food', 'item' => $petDetail['pivot']['item'], 'quantity' => $petDetail['pivot']['quantity'], 'notes' => $petDetail['pivot']['notes']];
            }
            if ( isset( $formattedPetsDetails ) ){
                $event->pets()->attach( $formattedPetsDetails );
            } else {
                $event->pets()->attach( $petIds );
            }
            DB::commit();

            return $event->load( ['recurrence', 'pets'] );

        } catch ( Exception $e ) {
            DB::rollBack();
            throw new Exception( "Creation failed: " . $e->getMessage() );
        }
    }
    public function update($id, array $data)
    {
        ray('update')->red();
        return DB::transaction(function () use ($id, $data) {

            $event = Event::findOrFail($id);
            $event->update(Arr::except($data, Event::EXCEPTED_UPDATE_FIELDS));

            // Mettre à jour les relations avec les animaux
            if (isset($data['petId'])) {
                $event->pets()->sync(Arr::wrap($data['petId']));
            }

            return $event;
        });
    }

    public function updateRecurrence($eventId, array $recurrenceData)
    {
        $event = Event::findOrFail($eventId);
        return $event->recurrence()->updateOrCreate([], $recurrenceData);
    }

    public function updateOccurrences($eventId, $targetDate, array $updateData)
    {
        return EventOccurrence::where('event_id', $eventId)
            ->where('occurrence_date', '>=', $targetDate)
            ->update($updateData);
    }

    public function updateSingleOccurrence($eventId, $targetDate, array $updateData)
    {
        return EventOccurrence::where('event_id', $eventId)
            ->where('occurrence_date', $targetDate)
            ->update($updateData);
    }

  /*  public function update($id, array $data)
    {
        DB::beginTransaction();
        try {
            $event = Event::findOrFail($id);

            // Vérifier si l'événement est récurrent et si on doit appliquer les changements à toutes les occurrences
            $withRecurrences = request()->input('with_recurrences', false);
            $targetDate = request()->input('date');

            if ($event->is_recurring && $withRecurrences) {
                // Mettre à jour l'événement principal et toutes les occurrences futures
                $event->update(Arr::except( $data, self::EXCEPTED_UPDATE_FiELDS ));

                // Mettre à jour la récurrence si elle existe, sinon la créer
                if (!empty($data['recurrence'])) {
                    $event->recurrence()->updateOrCreate([], $data['recurrence']);
                } else {
                    $event->recurrence()->delete();
                }

                // Mettre à jour les relations avec les animaux
                $event->pets()->sync(Arr::wrap($data['petId']));

                // Appliquer les modifications à toutes les occurrences futures
                EventOccurrence::where('event_id', $event->id)
                    ->where('occurrence_date', '>=', $targetDate)
                    ->update([
                        'custom_title' => $data['title'] ?? null,
                        'custom_notes' => $data['notes'] ?? null,
                        'custom_start_time' => $data['start_date'] ? Carbon::parse($data['start_date'])->format('H:i:s') : null,
                        'custom_end_time' => $data['end_date'] ? Carbon::parse($data['end_date'])->format('H:i:s') : null,
                    ]);

            } elseif ($event->is_recurring && !$withRecurrences) {
                // Modification d'une seule occurrence spécifique
                $occurrence = EventOccurrence::where('event_id', $event->id)
                    ->where('occurrence_date', $targetDate)
                    ->firstOrFail();

                $occurrence->update([
                    'custom_title' => $data['title'] ?? $event->title,
                    'custom_notes' => $data['notes'] ?? $event->notes,
                    'custom_start_time' => $data['start_date'] ? Carbon::parse($data['start_date'])->format('H:i:s') : $event->start_date->format('H:i:s'),
                    'custom_end_time' => $data['end_date'] ? Carbon::parse($data['end_date'])->format('H:i:s') : optional($event->end_date)->format('H:i:s'),
                ]);
            } else {
                // Mise à jour d'un événement non récurrent
                $event->update(Arr::except( $data, self::EXCEPTED_UPDATE_FiELDS ));

                // Mise à jour des relations avec les animaux
                $event->pets()->sync(Arr::wrap($data['petId']));
            }

            DB::commit();

            return $event->load(['recurrence', 'pets', 'occurrences']);
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Update failed: " . $e->getMessage());
        }
    }*/


    public function delete($id, $withRecurrence)
    {

        try {
            $event = $this->find( $id );
            if ( $withRecurrence || !$event->has( 'recurrence' ) ){
                $event->delete();
            } else {
                $date = substr( request()->date, 0, 10 );
                EventOccurrence::updateOrCreate( ['event_id' => $event->id, 'occurrence_date' => $date], ['is_deleted' => true] );
            }
            return true;
        } catch ( Exception $e ) {
            throw $e;
        }
    }

    public function find($id)
    {
        return Event::with( 'recurrence' )->findOrFail( $id );
    }
}
