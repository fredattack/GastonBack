<?php

namespace Database\Factories;

use App\Models\Familly;
use Illuminate\Database\Eloquent\Factories\Factory;

class FamillyFactory extends Factory
{
    protected $model = Familly::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail(),
            'local' => 'fr_FR',
        ];
    }
}
