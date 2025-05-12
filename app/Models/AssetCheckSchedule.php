<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCheckSchedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function warehouseMasterSite()
    {
        return $this->belongsTo(WarehouseMasterSite::class);
    }
}
