<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Clear existing permissions to avoid duplicates
        // Comment this out if you want to keep existing permissions
        // Permission::query()->delete();
        
        $permissions = [
            // Asset Management
            ['name' => 'assets.view', 'guard_name' => 'web', 'group' => 'Asset Management'],
            ['name' => 'assets.create', 'guard_name' => 'web', 'group' => 'Asset Management'],
            ['name' => 'assets.edit', 'guard_name' => 'web', 'group' => 'Asset Management'],
            ['name' => 'assets.delete', 'guard_name' => 'web', 'group' => 'Asset Management'],
            ['name' => 'assets.export', 'guard_name' => 'web', 'group' => 'Asset Management'],
            ['name' => 'assets.import', 'guard_name' => 'web', 'group' => 'Asset Management'],
            
            // Site Management
            ['name' => 'sites.view', 'guard_name' => 'web', 'group' => 'Site Management'],
            ['name' => 'sites.create', 'guard_name' => 'web', 'group' => 'Site Management'],
            ['name' => 'sites.edit', 'guard_name' => 'web', 'group' => 'Site Management'],
            ['name' => 'sites.delete', 'guard_name' => 'web', 'group' => 'Site Management'],
            ['name' => 'sites.export', 'guard_name' => 'web', 'group' => 'Site Management'],
            ['name' => 'sites.view_assets', 'guard_name' => 'web', 'group' => 'Site Management'],
            
            // User Division Management
            ['name' => 'divisions.view', 'guard_name' => 'web', 'group' => 'Division Management'],
            ['name' => 'divisions.create', 'guard_name' => 'web', 'group' => 'Division Management'],
            ['name' => 'divisions.edit', 'guard_name' => 'web', 'group' => 'Division Management'],
            ['name' => 'divisions.delete', 'guard_name' => 'web', 'group' => 'Division Management'],
            
            // Category Management
            ['name' => 'categories.view', 'guard_name' => 'web', 'group' => 'Category Management'],
            ['name' => 'categories.create', 'guard_name' => 'web', 'group' => 'Category Management'],
            ['name' => 'categories.edit', 'guard_name' => 'web', 'group' => 'Category Management'],
            ['name' => 'categories.delete', 'guard_name' => 'web', 'group' => 'Category Management'],
            ['name' => 'categories.view_assets', 'guard_name' => 'web', 'group' => 'Category Management'],
            
            // Subcategory Management
            ['name' => 'subcategories.view', 'guard_name' => 'web', 'group' => 'Subcategory Management'],
            ['name' => 'subcategories.create', 'guard_name' => 'web', 'group' => 'Subcategory Management'],
            ['name' => 'subcategories.edit', 'guard_name' => 'web', 'group' => 'Subcategory Management'],
            ['name' => 'subcategories.delete', 'guard_name' => 'web', 'group' => 'Subcategory Management'],
            ['name' => 'subcategories.export', 'guard_name' => 'web', 'group' => 'Subcategory Management'],
            
            // User Management
            ['name' => 'users.view', 'guard_name' => 'web', 'group' => 'User Management'],
            ['name' => 'users.create', 'guard_name' => 'web', 'group' => 'User Management'],
            ['name' => 'users.edit', 'guard_name' => 'web', 'group' => 'User Management'],
            ['name' => 'users.delete', 'guard_name' => 'web', 'group' => 'User Management'],
            
            // Role & Permission Management
            ['name' => 'roles.view', 'guard_name' => 'web', 'group' => 'System Management'],
            ['name' => 'roles.edit', 'guard_name' => 'web', 'group' => 'System Management'],
            ['name' => 'permissions.manage', 'guard_name' => 'web', 'group' => 'System Management'],
            
            // General System Permissions
            ['name' => 'system.access', 'guard_name' => 'web', 'group' => 'System Management'],
            ['name' => 'reports.view', 'guard_name' => 'web', 'group' => 'Reporting'],
            ['name' => 'logs.view', 'guard_name' => 'web', 'group' => 'System Management'],
        ];
        
        // Check if 'group' column exists in permissions table
        // If not, add migration to add this column
        if (!DB::connection()->getSchemaBuilder()->hasColumn('permissions', 'group')) {
            $this->command->info('The "group" column does not exist in the permissions table. Please run the migration to add it.');
        }
        
        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
        
        // Create roles if they don't exist
        $roles = [
            'super-admin',
            'admin',
            'manager',
            'staff'
        ];
        
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            
            // Assign permissions based on role
            if ($roleName === 'super-admin') {
                // Super admin gets all permissions
                $role->syncPermissions(Permission::all());
            } elseif ($roleName === 'admin') {
                // Admin gets all except some system level permissions
                $role->syncPermissions(
                    Permission::whereNotIn('name', ['permissions.manage', 'logs.view'])->get()
                );
            } elseif ($roleName === 'manager') {
                // Manager gets view permissions plus some create/edit
                $role->syncPermissions(
                    Permission::where('name', 'like', '%.view%')
                        ->orWhere('name', 'like', '%.export%')
                        ->orWhere('name', 'system.access')
                        ->orWhere('name', 'reports.view')
                        ->orWhere('name', 'assets.create')
                        ->orWhere('name', 'assets.edit')
                        ->get()
                );
            } elseif ($roleName === 'staff') {
                // Staff gets basic view permissions only
                $role->syncPermissions(
                    Permission::where('name', 'like', '%.view%')
                        ->orWhere('name', 'system.access')
                        ->get()
                );
            }
        }
        
        $this->command->info('Permissions and roles created successfully!');
    }
}
