<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Category::create(['name' => 'Elektronik']); // Simple seeding
    
        $this->call([
            WarehouseMasterSiteSeeder::class,
            AssetStatusSeeder::class,
        ]);
    }
}
