<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAset extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori'];

    public function asets()
    {
        return $this->hasMany(Aset::class, 'kategori_id');
    }
}