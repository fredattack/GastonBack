<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Recurrence;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RecurrenceFactory extends Factory
{
    protected $model = Recurrence::class;

    public function definition(): array
    {
        return [
            'frequency_type' => $this->faker->randomElement(['daily', 'weekly', 'monthly']),
            'frequency' => $this->faker->randomNumber(1,7),
            'days' => [],
            'occurrences' => $this->faker->randomNumber(),
            'end_date' => null,
            'event_id' => Event::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
