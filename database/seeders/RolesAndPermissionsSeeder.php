<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Criar roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $tecnico = Role::firstOrCreate(['name' => 'tecnico']);
        $utilizador = Role::firstOrCreate(['name' => 'utilizador']);

        // Criar permissões
        $permissions = [
            'ver tickets',
            'criar tickets',
            'atribuir tickets',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Atribuir permissões aos roles
        $admin->syncPermissions(Permission::all());
        $tecnico->syncPermissions(['ver tickets','atribuir tickets']);
        $utilizador->syncPermissions(['ver tickets','criar tickets']);

        // Dar admin ao user 1 (se existir)
        $user = User::find(1);
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
