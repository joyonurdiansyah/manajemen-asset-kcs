<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Asset;
use App\Models\AssetLog;
use App\Models\AssetStatus;
use App\Models\Location;
use Illuminate\Database\Seeder;

class AssetLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => '0311100009955', 'merk' => 'L360 Epson', 'serial' => 'X3GW083927', 'type' => 'Printer', 'keterangan' => 'Rusak'],
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => '0611100141410', 'merk' => 'HKVISION 8-PORT 10/100M', 'serial' => 'K918381865', 'type' => 'Switch', 'keterangan' => 'Rusak'],
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => '0611404023498', 'merk' => 'RG-ES209GC-P - Ruijie', 'serial' => 'G1TA06X05595', 'type' => 'Switch', 'keterangan' => 'Oke, mau dipasang P6'],
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => '0611404023511', 'merk' => 'RG-ES209GC-P - Ruijie', 'serial' => 'G1TA06X04987', 'type' => 'Switch', 'keterangan' => 'Oke, mau dipasang P6'],
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => '0611404023535', 'merk' => 'RG-ES209GC-P - Ruijie', 'serial' => 'G1TA06X01013B', 'type' => 'Switch', 'keterangan' => 'Oke, mau dipasang P6'],
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => '0611100017838', 'merk' => 'RG-ES209GC-P - Ruijie', 'serial' => 'ZASC12P012059', 'type' => 'Switch', 'keterangan' => 'Oke, mau dipasang P6'],
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => '0611100024775', 'merk' => 'RG-ES209GC-P - Ruijie', 'serial' => 'G1TB23A009224', 'type' => 'Switch', 'keterangan' => 'Untuk backup, pemasangan p6'],
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => '0611100024768', 'merk' => 'RG-ES209GC-P - Ruijie', 'serial' => 'G1TB23A003777', 'type' => 'Switch', 'keterangan' => 'Untuk backup, pemasangan p6'],
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'HP EliteDesk 800 G1 SFF', 'serial' => 'SGH342PLFK', 'type' => 'CPU', 'keterangan' => 'Bisa Digunakan, tetapi lemot perlu install ulang'],
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => '0611404023580', 'merk' => 'U6-PRO', 'serial' => 'V9C05D6B26DF1', 'type' => 'AP', 'keterangan' => 'Oke'],
            ['lokasi' => 'KCS', 'tanggal' => '2025-04-22', 'asset_code' => '061110002481', 'merk' => 'D-LINK DPR-1061', 'serial' => 'P1R629B000230', 'type' => 'Print Server', 'keterangan' => 'Oke'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'L360 Epson', 'serial' => 'X3GW085678', 'type' => 'Printer', 'keterangan' => 'Rusak'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'DELL L240AS-00', 'serial' => '00697110', 'type' => 'CPU', 'keterangan' => 'Bisa digunakan, tetapi lemot perlu install ulang'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'L275AM-00', 'serial' => '00678185', 'type' => 'CPU', 'keterangan' => 'Bisa digunakan, tetapi lemot perlu install ulang'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'DELL AC290AM-00', 'serial' => '53QHW22', 'type' => 'CPU', 'keterangan' => 'Bisa digunakan, tetapi lemot perlu install ulang'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'Dell l240AS-00', 'serial' => '00588039', 'type' => 'CPU', 'keterangan' => 'Bisa digunakan, tetapi lemot perlu install ulang'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'Compaq Presario CQ3000', 'serial' => null, 'type' => 'CPU', 'keterangan' => 'Bisa digunakan, tetapi lemot perlu install ulang'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'Dell Inc', 'serial' => 'CN-04H19R-72872-22A-KWTL', 'type' => 'MONITOR', 'keterangan' => 'Bisa digunakan, tetapi lemot'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'HP LV1911', 'serial' => '6CM4341KPW', 'type' => 'MONITOR', 'keterangan' => 'Bisa digunakan, tetapi lemot'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'Dell Inc', 'serial' => 'CN-04H19R-72872-1AS-JF7L', 'type' => 'MONITOR', 'keterangan' => 'Bisa digunakan, tetapi lemot'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'DELL', 'serial' => 'CN-0YKH87-72872-67B-GM3U', 'type' => 'MONITOR', 'keterangan' => 'Bisa digunakan, tetapi lemot'],
            ['lokasi' => 'TKS', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'LG 19M38A', 'serial' => '804INAR2J168', 'type' => 'MONITOR', 'keterangan' => 'Bisa digunakan, tetapi lemot'],
            ['lokasi' => 'MKA', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'HPV194 Monitor', 'serial' => '3CQB022MDS', 'type' => 'MONITOR', 'keterangan' => 'Bisa digunakan, tetapi lemot'],
            ['lokasi' => 'MKA', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'HPLV1911', 'serial' => '6CM33829NK', 'type' => 'MONITOR', 'keterangan' => 'Bisa digunakan, tetapi lemot'],
            ['lokasi' => 'MKA', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'HP EliteDesk 800 G1', 'serial' => 'SGH342PN5H', 'type' => 'CPU', 'keterangan' => 'Bisa digunakan, tetapi lemot perlu install ulang'],
            ['lokasi' => 'MKA', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'DELL L290AM-00', 'serial' => '00259669', 'type' => 'CPU', 'keterangan' => 'Bisa digunakan, tetapi lemot perlu install ulang'],
            ['lokasi' => 'MKA', 'tanggal' => '2025-04-22', 'asset_code' => null, 'merk' => 'DELL - H290AM - 00', 'serial' => '7M6DH62', 'type' => 'CPU', 'keterangan' => 'Bisa digunakan, tetapi lemot perlu install ulang'],
        ];

        foreach ($data as $item) {
            $location = Location::firstOrCreate(['name' => $item['lokasi']]);
            $asset = Asset::firstOrCreate([
                'kode_asset' => $item['asset_code'] ?? 'default_code', 
                'merk' => $item['merk'],
                'serial_number' => $item['serial'],
                'type' => $item['type'],
            ]);

            $status = AssetStatus::firstOrCreate(['status' => $item['keterangan']]);

            AssetLog::create([
                'location_id' => $location->id,
                'asset_id' => $asset->id,
                'status_id' => $status->id,
                'tanggal' => $item['tanggal'],
            ]);
        }
    }
}
