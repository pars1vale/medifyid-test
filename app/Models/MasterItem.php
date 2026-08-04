<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MasterItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function getFotoUrlAttribute()
    {
        return $this->foto ? Storage::disk('public')->url($this->foto) : null;
    }

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'item_kategori', 'master_item_id', 'kategori_id');
    }
}
