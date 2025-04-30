<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseMasterSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kode' => 100, 'nama_lokasi' => 'KACS BEKASI PK 5', 'alamat' => 'JL. RAYA NAROGONG KM 12.5'],
            ['kode' => 104, 'nama_lokasi' => 'WHS P6 - 2', 'alamat' => 'Jl Raya Narogong KM 15'],
            ['kode' => 105, 'nama_lokasi' => 'WHS PK 6', 'alamat' => 'Jl Raya Narogong KM 15'],
            ['kode' => 106, 'nama_lokasi' => 'SUPPORT P6', 'alamat' => 'JL. RAYA NAROGONG KM'],
            ['kode' => 107, 'nama_lokasi' => 'WHS P6 - 3', 'alamat' => 'Jl Raya Narogong KM 15'],
            ['kode' => 111, 'nama_lokasi' => 'WH CIKARANG', 'alamat' => 'MMP Jababeka Warehouse XII'],
            ['kode' => 112, 'nama_lokasi' => 'SUPPORT CKR', 'alamat' => 'MMP Jababeka Warehouse XII'],
            ['kode' => 122, 'nama_lokasi' => 'DRY SURABAYA', 'alamat' => 'Komp. Pergudangan Mutiara'],
            ['kode' => 123, 'nama_lokasi' => 'DRY MEDAN', 'alamat' => 'Gd.No.1B Sebelah Kantor Gudang'],
            ['kode' => 124, 'nama_lokasi' => 'DRY MAKASAR', 'alamat' => 'Komplek Pergudangan Parangloe'],
            ['kode' => 125, 'nama_lokasi' => 'FR BATAM', 'alamat' => 'Komplek Mega Cipta Industrial'],
            ['kode' => 126, 'nama_lokasi' => 'FR PEKANBARU', 'alamat' => 'Jl. HR Subrantas Kav.9, PT'],
            ['kode' => 127, 'nama_lokasi' => 'FR MEDAN', 'alamat' => 'Gudang no. 1B, Jl. KL Yos'],
            ['kode' => 128, 'nama_lokasi' => 'FR PADANG', 'alamat' => 'Jl. By Pass Km.22, Kec. Koto'],
            ['kode' => 129, 'nama_lokasi' => 'FR PALEMBANG', 'alamat' => 'Jl. Tembus Terminal RT. 012,'],
            ['kode' => 132, 'nama_lokasi' => 'FR SEMARANG', 'alamat' => '.'],
            ['kode' => 133, 'nama_lokasi' => 'FR BANJARMSN', 'alamat' => 'Komplek pergudangan Kalimantan'],
            ['kode' => 134, 'nama_lokasi' => 'FR PONTIANAK', 'alamat' => 'Pontianak'],
            ['kode' => 135, 'nama_lokasi' => 'FR BALIKPPN', 'alamat' => 'Jl. Karingau RT. 07'],
            ['kode' => 136, 'nama_lokasi' => 'FR MAKASAR', 'alamat' => 'R905 Makasar'],
            ['kode' => 137, 'nama_lokasi' => 'FR MANADO', 'alamat' => 'R906 Jl. Taman Arimatea Kayu'],
            ['kode' => 138, 'nama_lokasi' => 'WH PALU', 'alamat' => 'Jl.Martadinata Area Komplek'],
            ['kode' => 139, 'nama_lokasi' => 'WH PAPUA', 'alamat' => 'PAPUA'],
            ['kode' => 140, 'nama_lokasi' => 'KONSOLIDATOR', 'alamat' => 'JL. RAYA NAROGONG KM 12.5'],
            ['kode' => 141, 'nama_lokasi' => 'DNR JABAR', 'alamat' => 'Jl. Satria Raya II No. 46,'],
            ['kode' => 142, 'nama_lokasi' => 'DNR BANTEN', 'alamat' => 'Komplek Pergudangan Sinar Hati'],
            ['kode' => 143, 'nama_lokasi' => 'DNR ACEH', 'alamat' => 'jl. soekarno hatta, mibo (depan)'],
            ['kode' => 144, 'nama_lokasi' => 'DNR PAPUA BR', 'alamat' => 'Jln.Brigjen Marinir Abraham O'],
            ['kode' => 145, 'nama_lokasi' => 'DNR KAL-UT', 'alamat' => 'Jl.Kedondong No.31, Tj.Selor h'],
            ['kode' => 146, 'nama_lokasi' => 'DNR BENGKULU', 'alamat' => 'ALAMAT MAP PT.PANYIMBANG RATU'],
            ['kode' => 147, 'nama_lokasi' => 'DNR NTB', 'alamat' => 'Jl.Gajah Mada No.37, Pagesangan'],
            ['kode' => 148, 'nama_lokasi' => 'DNR GRTALO', 'alamat' => 'Jl. Pangeran Hidayat No.202,'],
            ['kode' => 149, 'nama_lokasi' => 'DNR MALUKU U', 'alamat' => 'Jl.Raya Sultan Nuku, Bukit'],
            ['kode' => 150, 'nama_lokasi' => 'SUPPORT P5', 'alamat' => 'JL. RAYA NAROGONG KM 12.5'],
            ['kode' => 151, 'nama_lokasi' => 'DNR SUL-UT', 'alamat' => 'Kairagi Satu, Kec. Mapanget,'],
            ['kode' => 152, 'nama_lokasi' => 'DNR KAL-TENG', 'alamat' => 'JL. A.Yani KM 20,8, RT.08/RW.04'],
            ['kode' => 153, 'nama_lokasi' => 'DNR SUL-BAR', 'alamat' => 'Jl.Kurungan Bassi No.19, Rangas'],
            ['kode' => 154, 'nama_lokasi' => 'DNR SUL-TENG', 'alamat' => 'Jl. Gunung Nokilalaki No.36,'],
            ['kode' => 155, 'nama_lokasi' => 'DNR MALUKU', 'alamat' => 'Kel Amantelu, Sirimau'],
            ['kode' => 156, 'nama_lokasi' => 'DNR SUL-TGR', 'alamat' => 'Mokoau, Kec.Kambu, Kota Kendari'],
            ['kode' => 160, 'nama_lokasi' => 'AS CILEUNGSI', 'alamat' => 'Jl. Raya Narogong KM.19 No.77'],
            ['kode' => 161, 'nama_lokasi' => 'AS MSD', 'alamat' => 'Jl. Raya Narogong KM.19 No.77'],
            ['kode' => 162, 'nama_lokasi' => 'WHS GRABMART', 'alamat' => 'Jl. Raya Narogong KM.19 No.77'],
            ['kode' => 163, 'nama_lokasi' => 'AS TIS', 'alamat' => 'CILEUNGSI'],
            ['kode' => 165, 'nama_lokasi' => 'WH PT RAS', 'alamat' => 'Pasar IkanMuara Baru'],
            ['kode' => 170, 'nama_lokasi' => 'SUPPORT AS', 'alamat' => 'JL. RAYA NAROGONG KM.19 NO.77'],
            ['kode' => 180, 'nama_lokasi' => 'WHS BALI', 'alamat' => 'Jl. Prof Ida Bagus Mantra 865'],
            ['kode' => 181, 'nama_lokasi' => 'WHS DENPASAR', 'alamat' => 'Jl Bypas Ngurah Rai'],
            ['kode' => 190, 'nama_lokasi' => 'WH SURABAYA', 'alamat' => 'JL BYPASS KRIAN KM 1.6'],
            ['kode' => 191, 'nama_lokasi' => 'PALLET SBY', 'alamat' => 'SURABAYA'],
            ['kode' => 192, 'nama_lokasi' => 'TAMBAK SAWAH', 'alamat' => 'SURABAYA'],
            ['kode' => 200, 'nama_lokasi' => 'FIN BEKASI', 'alamat' => 'JL. RAYA NAROGONG KM 12.5'],
            ['kode' => 201, 'nama_lokasi' => 'SBY CONSM', 'alamat' => 'SURABAYA'],
            ['kode' => 210, 'nama_lokasi' => 'ASSET BEKASI', 'alamat' => 'JL. RAYA NAROGONG KM 12.5'],
            ['kode' => 230, 'nama_lokasi' => 'DC DOCUMENT', 'alamat' => 'Jl. Raya Narogong KM.19 No.77'],
            ['kode' => 251, 'nama_lokasi' => 'FIX ASSET', 'alamat' => 'JL. RAYA NAROGONG KM 12.5'],
            ['kode' => 301, 'nama_lokasi' => 'SUD MEDAN', 'alamat' => 'Jl. William Iskandar Pasar V'],
            ['kode' => 800, 'nama_lokasi' => 'TRAINING', 'alamat' => 'NAROGONG'],
            ['kode' => 901, 'nama_lokasi' => 'BUDGET', 'alamat' => 'JL NAROGONG KM 19'],
        ];

        DB::table('warehouse_master_sites')->insert($data);
    }
}
