<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $division = Division::where('name', 'IT')->first();

        $users = [
            [
                'name' => 'Wahyu',
                'email' => 'wahyu@gmail.com',
            ],
            [
                'name' => 'Joyo',
                'email' => 'joyo@gmail.com',
            ],
            [
                'name' => 'Dedy',
                'email' => 'dedy@gmail.com',
            ],
            [
                'name' => 'Muri',
                'email' => 'muri@gmail.com',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']], 
                [
                    'name' => $user['name'],
                    'password' => Hash::make('ITINFRA2025'),
                    'division_id' => $division->id,
                ]
            );
        }
    }
}
