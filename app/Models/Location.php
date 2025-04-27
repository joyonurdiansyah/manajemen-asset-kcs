<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    // Kolom yang dilindungi (tidak bisa diisi melalui mass-assignment)
    protected $guarded = [];
    
    public function assetStatuses()
    {
        return $this->hasMany(AssetStatus::class);
    }
}
