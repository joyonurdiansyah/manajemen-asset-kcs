<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory;

    // Kolom yang dilindungi (tidak bisa diisi melalui mass-assignment)
    protected $guarded = [];

    public function assetStatuses()
    {
        return $this->hasMany(AssetStatus::class);
    }

    public function assetLogs()
    {
        return $this->hasMany(AssetLog::class);
    }
}
