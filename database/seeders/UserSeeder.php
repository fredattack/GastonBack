<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        //Drop familly table
        \App\Models\Familly::getQuery()->delete();
        \App\Models\Familly::factory()->create([
            'name' => 'Ma famille',
        ]);

        //Drop users table
        \App\Models\User::getQuery()->delete();
        \App\Models\User::factory()->create([
            'name' => 'Fred',
            'email' => 'fredmoras8@gmail.com',
            'password' => bcrypt('P@ssw0rd'),
            ]);
    }
}
