<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Optional: Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permissions (bisa kamu sesuaikan)
        $permissions = [
            'create user',
            'edit user',
            'delete user',
            'view user',
            'approve request',
            'manage system',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat roles
        $developer = Role::firstOrCreate(['name' => 'Developer']);
        $admin     = Role::firstOrCreate(['name' => 'Admin']);
        $approval  = Role::firstOrCreate(['name' => 'Approval']);

        // Assign permissions ke role tertentu
        $developer->givePermissionTo(Permission::all()); // akses penuh
        $admin->givePermissionTo(['create user', 'edit user', 'view user']);
        $approval->givePermissionTo(['approve request']);
    }
}
