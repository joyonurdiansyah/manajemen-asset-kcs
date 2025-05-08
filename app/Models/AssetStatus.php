<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetStatus extends Model
{
    protected $guarded = [];

    public function warehouseMasterSite()
    {
        return $this->belongsTo(WarehouseMasterSite::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function lokasiAwal()
    {
        return $this->belongsTo(WarehouseMasterSite::class, 'lokasi_awal_id');
    }
    
    public function lokasiTujuan()
    {
        return $this->belongsTo(WarehouseMasterSite::class, 'lokasi_tujuan_id');
    }
}
