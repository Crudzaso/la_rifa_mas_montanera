<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0;$i<10;$i++){
            User::create([
                'names' => fake()->name,
                'lastnames' => fake()->lastName,
                'email' => fake()->email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone_number' => '3' . fake()->randomElement(['0', '1', '2', '3', '5']) . fake()->numerify('#######'),
                'address' => fake()->address,
                'remember_token' => Str::random(10),
                'profile_photo_path' => fake()->imageUrl($width = 640, $height = 480)
            ])->assignRole('user');
        }
    }
}
