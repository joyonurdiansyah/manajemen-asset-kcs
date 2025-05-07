<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    // Kolom yang dilindungi (tidak bisa diisi melalui mass-assignment)
    protected $guarded = [];

    public function assetStatuses()
    {
        return $this->hasMany(AssetStatus::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
