<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::create(['code' => 'KCS', 'name' => 'KCS']);
        Location::create(['code' => 'TKS', 'name' => 'TKS']);
        Location::create(['code' => 'MKA', 'name' => 'MKA']);
    }
}
