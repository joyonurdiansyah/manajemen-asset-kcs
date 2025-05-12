<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignSuperAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::findById(4); 
        
        if (!$superAdminRole) {
            $this->command->error('Super-admin role not found!');
            return;
        }
        
        $userEmails = [
            'wahyu@gmail.com',
            'joyo@gmail.com',
            'dedy@gmail.com',
            'muri@gmail.com',
        ];
        
        $users = User::whereIn('email', $userEmails)->get();
        
        if ($users->isEmpty()) {
            $this->command->error('None of the specified users were found!');
            return;
        }
        
        foreach ($users as $user) {
            if (!$user->hasRole($superAdminRole)) {
                $user->assignRole($superAdminRole);
                $this->command->info("Assigned super-admin role to {$user->name} ({$user->email})");
            } else {
                $this->command->info("{$user->name} ({$user->email}) already has super-admin role");
            }
        }
        
        $this->command->info("Role assignment completed! {$users->count()} users processed.");
        
        // Report any missing users
        $foundEmails = $users->pluck('email')->toArray();
        $missingEmails = array_diff($userEmails, $foundEmails);
        
        if (!empty($missingEmails)) {
            $this->command->warn("The following users were not found: " . implode(', ', $missingEmails));
        }
    }
}
