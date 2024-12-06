<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Primero ejecutamos el seeder de roles
        $this->call([
            RoleSeeder::class
        ]);

        // Creamos el usuario admin
        $admin = User::factory()->create([
            'name' => 'Admin Master',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password')
        ]);

        // Asignamos el rol admin
        $admin->assignRole('admin');

        // Creamos usuario de prueba (opcional)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ])->assignRole('cliente');

        User::factory()->create([
            'name' => 'Test Organizador',
            'email' => 'test2@example.com',
            'password' => Hash::make('password')
        ])->assignRole('organizador');
    }
}
