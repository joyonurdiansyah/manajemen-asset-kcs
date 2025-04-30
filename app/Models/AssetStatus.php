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
}
