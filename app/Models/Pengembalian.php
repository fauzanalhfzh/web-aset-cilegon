<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_keluar_id',
        'jumlah',
        'tanggal_pengembalian',
        'keterangan',
        'approved_by',
        'status',
    ];

    public function asetKeluar()
    {
        return $this->belongsTo(AsetKeluar::class, 'aset_keluar_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}