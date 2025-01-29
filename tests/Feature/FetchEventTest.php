<?php

namespace Tests\Feature;

use App\Models\Event;
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


        $toDateTimeString = Carbon::now()->addDays( 5 )->toIso8601String();
        $response = $this->actingAs($this->user)
            ->json('GET', '/api/v1-0-0/events/for-calendar', ['filters'=>[
            'start_date' => Carbon::now()->subDays(5)->toIso8601String(),
            'end_date' => $toDateTimeString,
        ]]);


        $response->assertStatus(200);

//        ray($response->json())->die();
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

        // Vérifie que l'événement simple et au moins une occurrence de l'événement récurrent sont retournés
        $this->assertGreaterThanOrEqual(2, count($response->json('data')));
    }
}
