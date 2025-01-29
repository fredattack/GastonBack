<?php

namespace Database\Factories;

use App\Enums\EventType;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween( "-1year", "1year" );
        return [
            'type' => $this->faker->randomElement(EventType::getValues()),
            'title' => $this->faker->word(),
            'is_full_day' => $this->faker->boolean(),
            'start_date' => $startDate,
            'end_date' => $this->faker->dateTimeBetween($startDate,$startDate->modify('+1 month')),
            'is_recurring' => $this->faker->boolean(),
            'notes' => $this->faker->sentence(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
