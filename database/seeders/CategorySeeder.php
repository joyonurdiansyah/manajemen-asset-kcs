<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Printer']);
        Category::create(['name' => 'Switch']);
        Category::create(['name' => 'CPU']);
        Category::create(['name' => 'Monitor']);
        Category::create(['name' => 'AP']);
        Category::create(['name' => 'Print Server']);
        Category::create(['name' => 'UPS']);
        Category::create(['name' => 'LED TV']);
        Category::create(['name' => 'Alat Scan']);
    }
}
