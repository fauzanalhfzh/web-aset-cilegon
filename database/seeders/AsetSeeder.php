<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aset;
use App\Models\KategoriAset;
use App\Models\User;

class AsetSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriList = KategoriAset::pluck('id')->toArray();
        $approver = User::first(); // ambil user pertama sebagai approver

        $data = [
            [
                'nama_aset' => 'Laptop Dell XPS',
                'kategori_id' => $kategoriList[array_rand($kategoriList)],
                'jumlah' => 10,
                'kondisi' => 'Baik',
                'tanggal_pembelian' => '2022-01-15',
                // 'status' => 'approved',
                // 'approved_by' => $approver?->id,
            ],
            [
                'nama_aset' => 'Meja Kantor',
                'kategori_id' => $kategoriList[array_rand($kategoriList)],
                'jumlah' => 20,
                'kondisi' => 'Perlu Perbaikan',
                'tanggal_pembelian' => '2021-05-20',
                // 'status' => 'pending',
                // 'approved_by' => null,
            ],
            [
                'nama_aset' => 'Mobil Dinas Toyota Avanza',
                'kategori_id' => $kategoriList[array_rand($kategoriList)],
                'jumlah' => 2,
                'kondisi' => 'Rusak',
                'tanggal_pembelian' => '2020-08-10',
                // 'status' => 'approved',
                // 'approved_by' => $approver?->id,
            ],
        ];

        foreach ($data as $aset) {
            Aset::create($aset);
        }
    }
}
