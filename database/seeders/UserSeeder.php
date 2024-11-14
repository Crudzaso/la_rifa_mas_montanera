<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            User::create([
                'names' => 'Jonathan',
                'lastnames' => 'admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '633401092',
                'remember_token' => Str::random(10),
            ])->assignRole('admin');

            User::create([
                'names' => 'Nancy',
                'lastnames' => 'Espinosa',
                'email' => 'nancy@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '633401093',
                'remember_token' => Str::random(10),
            ])->assignRole('user');
        }
    }
}
