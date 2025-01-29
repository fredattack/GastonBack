<?php

namespace Tests\Feature;

use App\Enums\EventType;
use App\Models\Event;
use App\Models\EventOccurrence;
use App\Models\Pet;
use App\Models\Recurrence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[Test]
    /*
     * the event is not recurring so we update directly the event
     * */
    public function it_can_update_a_non_recurring_event()
    {

        $event = Event::factory()->create(['is_recurring' => false]);

        $pets = Pet::factory()->count(2)->create();
        $response = $this->actingAs( $this->user)->putJson(route('events.update', $event->id), [
            'type' => EventType::Feeding,
            'title' => 'Updated Event',
            'notes' => 'Updated Notes',
            'is_recurring' => false,
            'is_full_day' => false,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
            'petId' => $pets->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Updated Event']);
    }

    #[Test]
    /*
     * the event as a recurrence, if we will update a specific occurrence, we will create the occurrence for this date
     * */
    public function it_can_update_a_single_occurrence_of_a_recurring_event()
    {

        $event = Event::factory()->create(['is_recurring' => true]);
        $occurrence = EventOccurrence::factory()->create([
            'event_id' => $event->id,
            'occurrence_date' => Carbon::today(),
        ]);

        $this->assertDatabaseCount( 'events', 1);
        $date = Carbon::today()->toDateString();
        $response =$this->actingAs( $this->user)->putJson(route('events.update', $event->id), [
            'title' => 'Updated Occurrence Title',
            'type' => EventType::Feeding,
            'notes' => 'Updated Occurrence Notes',
            'is_recurring' => true,
            'is_full_day' => false,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
            'date' => $date,
            'with_recurrences' => false,
            'recurrence'=>[
                'frequency' => '1',
                'frequency_type' => 'daily',
                'end_date' => Carbon::now()->addDays(5),
            ]
        ]);
        $this->assertDatabaseCount( 'events', 1);


        $response->assertStatus(200);
        $this->assertDatabaseHas('event_occurrences', [
            'id' => $occurrence->id,
            'custom_title' => 'Updated Occurrence Title',
            'occurrence_date' => $date,
        ]);
    }

    #[Test]
    /*
     * the event as a recurrence, if we will update all future occurrences, we will update the event recurrence to finish at a specific date and create a new event starting from this date
     * */
    public function it_can_update_all_future_occurrences_of_a_recurring_event()
    {

        $event = Event::factory()->create([
            'is_recurring' => true,
            'start_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'end_date' => null,
        ]);
        Recurrence::factory()->create([
            'event_id' => $event->id,
        ]);

        $this->assertDatabaseCount( 'events', 1);

        $response = $this->actingAs($this->user)->putJson(route('events.update', $event->id), [
            'type' => EventType::Feeding,
            'title' => 'Updated Future Events',
            'notes' => 'Updated Notes',
            'is_recurring' => true,
            'is_full_day' => true,
            'start_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'date' => Carbon::now()->format('Y-m-d'),
            'with_recurrences' => true,
            'recurrence' => [
                'frequency' => '1',
                'frequency_type' => 'daily',
                'end_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            ],
        ]);

        $this->assertDatabaseCount( 'events', 2);

        $response->assertStatus(200);

        $this->assertDatabaseHas('recurrences', [
            'event_id' => $event->id,
            'end_date' => Carbon::now()->format('Y-m-d'),
        ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Updated Future Events',
            'start_date' => Carbon::now()->format('Y-m-d'),
            'is_recurring' => true,
        ]);

    }

    #[Test]
    public function it_can_stop_recurrence_and_create_a_new_event()
    {
        $event = Event::factory()
            ->has( Recurrence::factory())
            ->create(
                [
                    'is_recurring' => true,
                    'start_date' => Carbon::now()->subDays(5)
                ]);

        $response = $this->actingAs( $this->user)
            ->putJson(route('events.update', $event->id), [
            'title' => 'Stop Recurrence',
            'type' => EventType::Feeding,
            'is_recurring' => false,
            'is_full_day' => false,
            'with_recurrences' => true,
            'date' => Carbon::now()->toDateString(),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
            'petId' => [Pet::factory()->create()->id],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount( 'events', 2);
        $this->assertDatabaseHas('events', [
//            'id' => $event->id,
            'title' => 'Stop Recurrence',
            'is_recurring' => false,
        ]);
    }

    #[Test]
    public function it_can_delete_a_single_occurrence_of_a_recurring_event()
    {
        $event = Event::factory()->create(['is_recurring' => true]);
        $occurrence = EventOccurrence::factory()->create([
            'event_id' => $event->id,
            'occurrence_date' => Carbon::today(),
        ]);

        $response = $this->deleteJson(route('events.update', $event->id), [
            'date' => Carbon::today()->toDateString(),
            'with_recurrences' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('event_occurrences', [
            'id' => $occurrence->id,
            'is_deleted' => 1,
        ]);
    }

}
