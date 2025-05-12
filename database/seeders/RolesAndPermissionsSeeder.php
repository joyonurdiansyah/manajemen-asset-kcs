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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view_assets',
            'add_assets',
            'edit_assets',
            'delete_assets',
            'export_assets',
            'import_assets',
            
            'view_sites',
            'add_sites',
            'edit_sites',
            'delete_sites',
            'export_sites',
            
            'view_divisions',
            'add_divisions',
            'edit_divisions',
            'delete_divisions',
            
            'view_categories',
            'add_categories',
            'edit_categories',
            'delete_categories',
            
            'view_subcategories',
            'add_subcategories',
            'edit_subcategories',
            'delete_subcategories',
            'export_subcategories',
            
            'view_permissions',
            'edit_permissions',
            
            'access_cekbarang',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $developerRole = Role::where('name', 'Developer')->first();
        if (!$developerRole) {
            $developerRole = Role::create(['name' => 'Developer']);
        }
        $developerRole->givePermissionTo(Permission::all());
        
        $superAdminRole = Role::where('name', 'super-admin')->first();
        if (!$superAdminRole) {
            $superAdminRole = Role::create(['name' => 'super-admin']);
        }
        $superAdminRole->givePermissionTo(Permission::all());
        
        $adminRole = Role::where('name', 'Admin')->first();
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'Admin']);
        }
        $adminRole->givePermissionTo([
            'view_assets', 'view_sites', 'view_divisions',
            'view_categories', 'view_subcategories', 'access_cekbarang'
        ]);
        
        $approvalRole = Role::where('name', 'Approval')->first();
        if (!$approvalRole) {
            $approvalRole = Role::create(['name' => 'Approval']);
        }
        $approvalRole->givePermissionTo([
            'view_assets', 'view_sites', 'view_categories', 'view_subcategories'
        ]);
        
        $managerRole = Role::where('name', 'manager')->first();
        if (!$managerRole) {
            $managerRole = Role::create(['name' => 'manager']);
        }
        $managerRole->givePermissionTo([
            'view_assets', 'view_sites', 'view_categories', 'view_subcategories',
            'export_assets', 'export_sites', 'export_subcategories'
        ]);
        
        $staffRole = Role::where('name', 'staff')->first();
        if (!$staffRole) {
            $staffRole = Role::create(['name' => 'staff']);
        }
        $staffRole->givePermissionTo([
            'view_assets', 'view_sites', 'access_cekbarang'
        ]);
    }
}
