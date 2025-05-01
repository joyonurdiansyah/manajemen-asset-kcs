<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            'ADMIN OPS' => 'KACS-101',
            'ADM OPS' => 'KACS-102',
            'COMMERCIAL' => 'KACS-103',
            'COSTUMER' => 'KACS-104',
            'FA' => 'KACS-105',
            'GA' => 'KACS-106',
            'HRD' => 'KACS-107',
            'INV' => 'KACS-108',
            'INVENTORY' => 'KACS-109',
            'IT' => 'KACS-110',
            'LP' => 'KACS-111',
            'MAINTENANCE' => 'KACS-112',
            'MITIGASI' => 'KACS-113',
            'MTC' => 'KACS-114',
            'OPERATIONAL' => 'KACS-115',
            'OPS' => 'KACS-116',
            'PURCHASING' => 'KACS-117',
            'QHSE' => 'KACS-118',
            'RISK CONTROL' => 'KACS-119',
            'TRANSPORT' => 'KACS-120',
        ];

        foreach ($divisions as $name => $code) {
            Division::create(['name' => $name, 'code' => $code]);
        }
    }
}
