<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id',
        'jumlah',
        'tanggal_keluar',
        'keterangan',
        // 'approved_by',
        // 'status',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}