<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetLog extends Model
{
    use HasFactory;

    // Kolom yang dilindungi (tidak bisa diisi melalui mass-assignment)
    protected $guarded = [];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
