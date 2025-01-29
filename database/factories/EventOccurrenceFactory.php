<?php

namespace Database\Factories;

use App\Models\EventOccurrence;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventOccurrenceFactory extends Factory
{
    protected $model = EventOccurrence::class;

    public function definition(): array
    {
        $customStartDate = $this->faker->dateTime;
        return [
            'event_id' => $this->faker->randomNumber(),
            'occurrence_date' => $this->faker->word(),
            'is_done' => $this->faker->boolean(),
            'is_deleted' => $this->faker->boolean(),
            'custom_title' => $this->faker->word(),
            'custom_notes' => $this->faker->word(),
            'custom_start_time' => $customStartDate,
            'custom_end_time' =>$customStartDate->modify('+1 hour'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
