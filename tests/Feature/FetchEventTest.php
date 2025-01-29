<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventOccurrence;
use App\Models\Recurrence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FetchEventTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_returns_events_for_calendar()
    {
       Event::factory()->create([
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(2),
            'is_recurring' => false,
        ]);


        $recurringEvent = Event::factory()->create([
            'start_date' => Carbon::now()->subDays(10),
            'is_recurring' => true,
        ]);


        Recurrence::factory()->create([
            'event_id' => $recurringEvent->id,
            'frequency_type' => 'daily',
            'frequency' => 1,
            'end_date' => Carbon::now()->addDays(10),
        ]);

        $response = $this->actingAs($this->user)
            ->json('GET', '/api/v1-0-0/events/for-calendar', ['filters'=>[
            'start_date' => Carbon::now()->subDays(5)->toIso8601String(),
            'end_date' => Carbon::now()->addDays( 5 )->toIso8601String(),
        ]]);


        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'start_date',
                    'end_date',
                    'is_recurring',
                    'recurrence' => [
                        'id',
                    ],
                ],
            ],
        ]);

        $this->assertGreaterThanOrEqual(2, count($response->json('data')));
    }

    #[Test]
    public function it_can_have_an_occurence_of_daily_event_without_end_date()
    {
        Event::factory()
            ->has(Recurrence::factory([
                'frequency_type' => 'daily',
                'frequency' => 1,
            ]))
            ->create([
            'start_date' => Carbon::now()->subDays(10),
            'is_recurring' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->json('GET', '/api/v1-0-0/events/for-calendar', ['filters'=>[
                'start_date' => Carbon::now()->addWeek()->startOfDay()->toIso8601String(),
                'end_date' => Carbon::now()->addWeek()->endOfDay()->toIso8601String(),
            ]]);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'start_date',
                    'end_date',
                    'is_recurring',
                    'recurrence' => [
                        'id',
                    ],
                ],
            ],
        ]);

        $this->assertCount(1, $response->json('data'));
    }

    #[Test]
    public function it_will_not_return_an_occurence_of_daily_event_if_this_occurence_is_deleted(){
      $event =   Event::factory()
            ->has(Recurrence::factory([
                'frequency_type' => 'daily',
                'frequency' => 1,
            ]))
            ->create([
                'start_date' => Carbon::now()->subDays(10),
                'is_recurring' => true,
            ]);

        EventOccurrence::factory()->create(
            [
                'event_id' =>$event->id,
                'occurrence_date' => Carbon::now()->addDay()->toDateString(),
                'is_deleted' => true,
            ]
        );

        $response = $this->actingAs($this->user)
            ->json('GET', '/api/v1-0-0/events/for-calendar', ['filters'=>[
                'start_date' => Carbon::now()->addDay()->startOfDay()->toIso8601String(),
                'end_date' => Carbon::now()->addDay()->endOfDay()->toIso8601String(),
            ]]);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [],
        ]);

        $this->assertCount(0, $response->json('data'));
    }

    #[Test]
    public function it_will_not_return_an_occurence_of_daily_event_after_end_date()
    {
        Event::factory()
            ->has(Recurrence::factory([
                'frequency_type' => 'daily',
                'end_date' => Carbon::now()->addDays(5),
                'frequency' => 1,
            ]))
            ->create([
                'start_date' => Carbon::now()->subDays(10),
                'is_recurring' => true,
            ]);

        $response = $this->actingAs($this->user)
            ->json('GET', '/api/v1-0-0/events/for-calendar', ['filters'=>[
                'start_date' => Carbon::now()->addDays(6)->startOfDay()->toIso8601String(),
                'end_date' => Carbon::now()->addDays(6)->endOfDay()->toIso8601String(),
            ]]);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [    ],
        ]);

        $this->assertCount(0, $response->json('data'));
    }
}
