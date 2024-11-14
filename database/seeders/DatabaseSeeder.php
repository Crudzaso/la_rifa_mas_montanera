<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //Roles y permisos
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(UsersTableSeeder::class);
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('user');
        });
    }
}
