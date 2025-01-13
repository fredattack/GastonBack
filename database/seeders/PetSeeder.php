<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // On suppose qu'un utilisateur existe
        $ownerId = User::first()->id ?? User::factory()->create()->id;

        $pets = [
            // 🐶 Chiens
            [
                'name' => 'Lewis',
                'species' => 'dog',
                'breed' => 'Caniche nain',
                'gender' => 'male',
                'birth_date' => $faker->dateTimeBetween('-12 years', '-11 years')->format('Y-m-d'),
            ],
            [
                'name' => 'Atos',
                'species' => 'dog',
                'breed' => 'Caniche nain',
                'gender' => 'male',
                'birth_date' => $faker->dateTimeBetween('-10 years', '-9 years')->format('Y-m-d'),
            ],
            [
                'name' => 'Pablo',
                'species' => 'dog',
                'breed' => 'Caniche royal',
                'gender' => 'male',
                'birth_date' => $faker->dateTimeBetween('-8 years', '-7 years')->format('Y-m-d'),
            ],
            [
                'name' => 'Marley',
                'species' => 'dog',
                'breed' => 'Lagotto Romagnolo',
                'gender' => 'male',
                'birth_date' => $faker->dateTimeBetween('-8 years', '-7 years')->format('Y-m-d'),
            ],

            // 🐱 Chats
            [
                'name' => 'Rosi',
                'species' => 'cat',
                'breed' => 'Cornish Rex',
                'gender' => 'female',
                'birth_date' => $faker->dateTimeBetween('-4 years', '-3 years')->format('Y-m-d'),
            ],
            [
                'name' => 'Oscar',
                'species' => 'cat',
                'breed' => 'Cornish Rex',
                'gender' => 'male',
                'birth_date' => $faker->dateTimeBetween('-3 years', '-2 years')->format('Y-m-d'),
            ],
            [
                'name' => 'Achille',
                'species' => 'cat',
                'breed' => 'Cornish Rex',
                'gender' => 'male',
                'birth_date' => $faker->dateTimeBetween('-3 years', '-2 years')->format('Y-m-d'),
            ],
            [
                'name' => 'Charly',
                'species' => 'cat',
                'breed' => 'LaPerm',
                'gender' => 'male',
                'birth_date' => $faker->dateTimeBetween('-2 years', '-1 years')->format('Y-m-d'),
            ],
            [
                'name' => 'Judith',
                'species' => 'cat',
                'breed' => 'LaPerm',
                'gender' => 'female',
                'birth_date' => $faker->dateTimeBetween('-1 years', '-10 months')->format('Y-m-d'),
            ],
            [
                'name' => 'Gabin',
                'species' => 'cat',
                'breed' => 'Devon Rex',
                'gender' => 'male',
                'birth_date' => $faker->dateTimeBetween('-1 years', '-10 months')->format('Y-m-d'),
            ],
            [
                'name' => 'Simone',
                'species' => 'cat',
                'breed' => 'Devon Rex',
                'gender' => 'female',
                'birth_date' => $faker->dateTimeBetween('-1 years', '-10 months')->format('Y-m-d'),
            ],
            [
                'name' => 'Hannah',
                'species' => 'cat',
                'breed' => 'Lykoi',
                'gender' => 'female',
                'birth_date' => $faker->dateTimeBetween('-1 years', '-10 months')->format('Y-m-d'),
            ],
            [
                'name' => 'Liseth',
                'species' => 'cat',
                'breed' => 'Devon Rex',
                'gender' => 'female',
                'birth_date' => $faker->dateTimeBetween('-5 months', '-3 months')->format('Y-m-d'),
            ],
            [
                'name' => 'Melchior',
                'species' => 'cat',
                'breed' => 'Bengal',
                'gender' => 'male',
                'birth_date' => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            ],
        ];

        // Insertion des animaux dans la base de données
        foreach ($pets as $pet) {
            Pet::create(array_merge($pet, [
                'is_active' => true,
                'owner_id' => $ownerId,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
