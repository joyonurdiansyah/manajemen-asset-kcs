<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssetStatus;
use App\Models\Category;
use App\Models\WarehouseMasterSite;
use Carbon\Carbon;

class AssetStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all(); 
        $sites = WarehouseMasterSite::all();

        foreach ($categories as $category) {
            foreach ($sites as $site) {
                AssetStatus::create([
                    'warehouse_master_site_id' => $site->id,
                    'category_id' => $category->id,
                    'asset_code' => strtoupper(substr($category->name, 0, 3)) . rand(100, 999) . ' Device',
                    'brand' => 'Generic',
                    'serial_number' => strtoupper(uniqid()),
                    'type' => $category->name, 
                    'tanggal_visit' => Carbon::now()->toDateString(),
                    'notes' => 'Catatan untuk kategori ' . $category->name,
                    'status_barang' => 'oke',
                    'lokasi_awal' => $site->nama_lokasi,
                    'lokasi_tujuan' => $site->nama_lokasi,
                ]);
            }
        }
    }
}
