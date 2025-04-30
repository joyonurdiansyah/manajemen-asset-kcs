<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AssetStatus;
use App\Models\Location;
use App\Models\Category;
use App\Models\WarehouseMasterSite;

class AssetStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengambil lokasi dan kategori dari data yang sudah ada di database atau buat baru jika tidak ada
        $locationKCS = WarehouseMasterSite::firstOrCreate(['kode' => '100'], ['nama_lokasi' => 'Kantor C']);
        $locationTKS = WarehouseMasterSite::firstOrCreate(['kode' => '104'], ['nama_lokasi' => 'Toko Kunci']);
        $locationMKA = WarehouseMasterSite::firstOrCreate(['kode' => '105'], ['nama_lokasi' => 'Makanan Kesehatan']);

        $printerCategory = Category::firstOrCreate(['name' => 'Printer']);
        $switchCategory = Category::firstOrCreate(['name' => 'Switch']);
        $cpuCategory = Category::firstOrCreate(['name' => 'CPU']);
        $monitorCategory = Category::firstOrCreate(['name' => 'Monitor']);
        $apCategory = Category::firstOrCreate(['name' => 'AP']);

        // Menambahkan status asset berdasarkan data yang sudah ada
        AssetStatus::firstOrCreate([
            'warehouse_master_site_id' => $locationKCS->id,
            'category_id' => $printerCategory->id,
            'asset_code' => '0311100009955',
            'brand' => 'Epson',
            'serial_number' => 'X3GW083927',
            'type' => 'Printer',
            'tanggal_visit' => '2025-04-22',
            'notes' => 'Rusak',
            'status_barang' => 'rusak',  
        ]);

        AssetStatus::firstOrCreate([
            'location_id' => $locationKCS->id,
            'category_id' => $switchCategory->id,
            'asset_code' => '0611100141410',
            'brand' => 'HKVISION 8-PORT 10/100M',
            'serial_number' => 'K918381865',
            'type' => 'Switch',
            'tanggal_visit' => '2025-04-22',
            'notes' => 'Rusak',
            'status_barang' => 'rusak', 
        ]);

        AssetStatus::firstOrCreate([
            'location_id' => $locationKCS->id,
            'category_id' => $switchCategory->id,
            'asset_code' => '0611404023498',
            'brand' => 'Ruijie',
            'serial_number' => 'G1TA06X05595',
            'type' => 'Switch',
            'tanggal_visit' => '2025-04-22',
            'notes' => 'Oke, mau dipasang P6',
            'status_barang' => 'oke', 
        ]);

        AssetStatus::firstOrCreate([
            'location_id' => $locationTKS->id,
            'category_id' => $printerCategory->id,
            'asset_code' => 'L360 Epson',
            'brand' => 'Epson',
            'serial_number' => 'X3GW085678',
            'type' => 'Printer',
            'tanggal_visit' => '2025-04-22',
            'notes' => 'Rusak',
            'status_barang' => 'rusak', 
        ]);

        AssetStatus::firstOrCreate([
            'location_id' => $locationTKS->id,
            'category_id' => $cpuCategory->id,
            'asset_code' => 'DELL L240AS-00',
            'brand' => 'DELL',
            'serial_number' => '00697110',
            'type' => 'CPU',
            'tanggal_visit' => '2025-04-22',
            'notes' => 'Bisa digunakan, tetapi lemot perlu install ulang',
            'status_barang' => 'oke',  
        ]);

        AssetStatus::firstOrCreate([
            'location_id' => $locationMKA->id,
            'category_id' => $monitorCategory->id,
            'asset_code' => 'HPV194 Monitor',
            'brand' => 'HP',
            'serial_number' => '3CQB022MDS',
            'type' => 'Monitor',
            'tanggal_visit' => '2025-04-22',
            'notes' => 'Bisa digunakan, tetapi lemot',
            'status_barang' => 'oke', 
        ]);

        AssetStatus::firstOrCreate([
            'location_id' => $locationMKA->id,
            'category_id' => $cpuCategory->id,
            'asset_code' => 'HP EliteDesk 800 G1',
            'brand' => 'HP',
            'serial_number' => 'SGH342PN5H',
            'type' => 'CPU',
            'tanggal_visit' => '2025-04-22',
            'notes' => 'Bisa digunakan, tetapi lemot perlu install ulang',
            'status_barang' => 'oke',  
        ]);
    }
}
