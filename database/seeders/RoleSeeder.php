<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Admin => all
           User => acceso a boletas, compras y perfil
         */
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        Permission::create(['name' => 'dashboard'])->syncRoles([$admin,$user]);
        Permission::create(['name' => 'show'])->syncRoles($admin);
        Permission::create(['name' => 'usuarios.show'])->syncRoles([$admin,$user]);
        Permission::create(['name' => 'usuarios.trashed'])->syncRoles([$admin]);
        Permission::create(['name' => 'usuarios.restore'])->syncRoles($admin);
        Permission::create(['name' => 'usuarios.create'])->syncRoles($admin);
        Permission::create(['name' => 'usuarios.store'])->syncRoles($admin);

    }
}
