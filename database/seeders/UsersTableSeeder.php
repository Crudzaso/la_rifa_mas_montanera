<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0;$i<100;$i++){
            User::create([
                'names' => fake()->name,
                'lastnames' => fake()->lastName,
                'email' => fake()->email,
                'password' => Hash::make('password'),
                'address' => fake()->address,
            ]);
        }
    }
}
