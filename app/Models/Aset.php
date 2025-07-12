<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_aset',
        'kategori_id',
        'jumlah',
        'kondisi',
        'tanggal_pembelian',
        'status',
        'approved_by',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriAset::class, 'kategori_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}