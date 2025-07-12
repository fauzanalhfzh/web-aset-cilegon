<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriAset;

class KategoriAsetSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            'Elektronik',
            'Kendaraan Dinas',
            'Peralatan Kantor',
            'Bangunan',
            'Inventaris Umum',
            'Komputer dan Aksesoris',
            'Furnitur',
        ];

        foreach ($kategori as $nama) {
            KategoriAset::create([
                'nama_kategori' => $nama,
            ]);
        }
    }
}
