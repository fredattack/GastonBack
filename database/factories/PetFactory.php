<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'species' => $this->faker->randomElement(['cat', 'dog', 'pig', 'other']),
            'breed' => $this->faker->word(),
            'gender' => $this->faker->randomElement(['male','female']),
            'birth_date' => $this->faker->dateTimeBetween('-10years', now()),
            'is_active' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'owner_id' => User::factory(),
        ];
    }
}
