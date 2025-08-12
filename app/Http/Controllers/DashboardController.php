<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Assets
        $totalAset = Aset::count();

        // Active Assets
        $asetAktif = Aset::all()->count();

        // Assets Needing Maintenance
        $perluMaintenance = Aset::all()->count();

        // Total Value
        $nilaiTotal = Aset::sum('jumlah'); // Assuming 'jumlah' represents the value in Rp

        // Asset Distribution Data (for chart)
        $kategoriData = KategoriAset::withCount('asets')->get()->map(function ($kategori) {
            return [
                'nama_kategori' => $kategori->nama_kategori,
                'jumlah' => $kategori->asets_count,
            ];
        });

        // Monthly Trends Data (example: assets added per month)
        $monthlyTrends = Aset::selectRaw('MONTH(tanggal_pembelian) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('M', mktime(0, 0, 0, $item->month, 1)),
                    'count' => $item->count,
                ];
            });

        // Recent Assets
        $recentAsets = Aset::with('kategori')
            ->latest('tanggal_pembelian')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalAset',
            'asetAktif',
            'perluMaintenance',
            'nilaiTotal',
            'kategoriData',
            'monthlyTrends',
            'recentAsets'
        ));
    }
}