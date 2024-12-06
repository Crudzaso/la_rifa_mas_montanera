<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Roles
        $admin = Role::create(['name' => 'admin']);
        $organizador = Role::create(['name' => 'organizador']);
        $cliente = Role::create(['name' => 'cliente']);

        // Permisos para usuarios
        Permission::create(['name' => 'users.index']);
        Permission::create(['name' => 'users.create']);
        Permission::create(['name' => 'users.edit']);
        Permission::create(['name' => 'users.delete']);

        // Permisos para rifas
        Permission::create(['name' => 'raffles.index']);
        Permission::create(['name' => 'raffles.create']);
        Permission::create(['name' => 'raffles.edit']);
        Permission::create(['name' => 'raffles.delete']);

        // Permisos para boletos
        Permission::create(['name' => 'tickets.index']);
        Permission::create(['name' => 'tickets.create']);
        Permission::create(['name' => 'tickets.show']);
        Permission::create(['name' => 'tickets.buy']);

        // Asignar permisos al admin
        $admin->givePermissionTo([
            'users.index', 'users.create', 'users.edit', 'users.delete',
            'raffles.index', 'raffles.create', 'raffles.edit', 'raffles.delete',
            'tickets.index', 'tickets.create', 'tickets.show', 'tickets.buy'
        ]);

        // Asignar permisos al organizador
        $organizador->givePermissionTo([
            'raffles.index', 'raffles.create', 'raffles.edit', 'raffles.delete'
        ]);

        // Asignar permisos al cliente
        $cliente->givePermissionTo([
            'raffles.index',
            'tickets.index', 'tickets.create', 'tickets.show',
            'raffles.index',
            'tickets.buy'
        ]);
    }
}
