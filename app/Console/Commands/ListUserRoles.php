<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ListUserRoles extends Command
{
    protected $signature = 'users:roles {role? : The role name to filter by}';
    protected $description = 'List all users and their assigned roles';

    public function handle()
    {
        $roleName = $this->argument('role');

        if ($roleName) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                $this->error("Role '{$roleName}' not found!");
                return 1;
            }

            $users = User::role($roleName)->get();
            $this->info("Users with role '{$roleName}': " . $users->count());
        } else {
            $users = User::with('roles')->get();
            $this->info("All users: " . $users->count());
        }

        $headers = ['ID', 'Name', 'Email', 'Roles'];
        $rows = [];

        foreach ($users as $user) {
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->roles->pluck('name')->join(', ')
            ];
        }

        $this->table($headers, $rows);

        return 0;
    }
}
