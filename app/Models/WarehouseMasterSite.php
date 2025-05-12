<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WarehouseMasterSite extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function assetStatuses()
    {
        return $this->hasMany(AssetStatus::class);
    }


    public function assetCheckSchedules()
    {
        return $this->hasMany(AssetCheckSchedule::class);
    }
}
