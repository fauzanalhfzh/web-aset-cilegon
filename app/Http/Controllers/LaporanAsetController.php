<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\KategoriAset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanAsetController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with(['kategori', 'approver']);

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter berdasarkan tanggal pembelian
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pembelian', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pembelian', '<=', $request->tanggal_sampai);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_aset', 'like', "%{$search}%")
                  ->orWhereHas('kategori', function ($q) use ($search) {
                      $q->where('nama_kategori', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $asets = $query->paginate(15)->appends($request->all());

        // Data untuk statistik
        $statistik = $this->getStatistik($request);

        // Data untuk dropdown filter
        $kategoris = KategoriAset::all();
        $statusOptions = ['pending', 'approved', 'rejected'];
        $kondisiOptions = ['baik', 'rusak ringan', 'rusak berat'];

        $statusPending = Aset::where('status', 'pending')->count();

        // dd($statusPending);

        return view('laporan.index', compact(
            'asets',
            'statistik',
            'kategoris',
            'statusOptions',
            'kondisiOptions',
            'request',
            'statusPending'
        ));
    }

    public function detail($id)
    {
        $aset = Aset::with(['kategori', 'approver'])->findOrFail($id);
        return view('laporan.detail', compact('aset'));
    }

    public function export(Request $request)
    {
        $query = Aset::with(['kategori', 'approver']);

        // Apply same filters as index method
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pembelian', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pembelian', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_aset', 'like', "%{$search}%")
                  ->orWhereHas('kategori', function ($q) use ($search) {
                      $q->where('nama_kategori', 'like', "%{$search}%");
                  });
            });
        }

        $asets = $query->get();

        $filename = 'laporan_aset_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($asets) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No',
                'Nama Aset',
                'Kategori',
                'Jumlah',
                'Kondisi',
                'Tanggal Pembelian',
                'Status',
                'Approved By',
                'Tanggal Dibuat'
            ]);

            // Data CSV
            $no = 1;
            foreach ($asets as $aset) {
                fputcsv($file, [
                    $no++,
                    $aset->nama_aset,
                    $aset->kategori->nama_kategori ?? '-',
                    $aset->jumlah,
                    ucfirst($aset->kondisi),
                    Carbon::parse($aset->tanggal_pembelian)->format('d/m/Y'),
                    ucfirst($aset->status),
                    $aset->approver->name ?? '-',
                    Carbon::parse($aset->created_at)->format('d/m/Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // public function print(Request $request)
    // {
    //     $query = Aset::with(['kategori', 'approver']);

    //     // Apply same filters as index method
    //     if ($request->filled('kategori_id')) {
    //         $query->where('kategori_id', $request->kategori_id);
    //     }

    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->filled('kondisi')) {
    //         $query->where('kondisi', $request->kondisi);
    //     }

    //     if ($request->filled('tanggal_dari')) {
    //         $query->whereDate('tanggal_pembelian', '>=', $request->tanggal_dari);
    //     }

    //     if ($request->filled('tanggal_sampai')) {
    //         $query->whereDate('tanggal_pembelian', '<=', $request->tanggal_sampai);
    //     }

    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('nama_aset', 'like', "%{$search}%")
    //               ->orWhereHas('kategori', function ($q) use ($search) {
    //                   $q->where('nama_kategori', 'like', "%{$search}%");
    //               });
    //         });
    //     }

    //     $asets = $query->get();
    //     $statistik = $this->getStatistik($request);

    //     return view('laporan.print', compact('asets', 'statistik', 'request'));
    // }



    public function print(Request $request)
    {
        $query = Aset::with(['kategori', 'approver']);

        // Apply filters as in the original method
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pembelian', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pembelian', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_aset', 'like', "%{$search}%")
                  ->orWhereHas('kategori', function ($q) use ($search) {
                      $q->where('nama_kategori', 'like', "%{$search}%");
                  });
            });
        }

        $asets = $query->get();
        $statistik = $this->getStatistik($request);

        // Fetch users for signatures
        $admin = User::where('role', 'Admin')->where('is_active', true)->first(['id', 'name', 'sign']);
        $lurah = User::where('role', 'Lurah')->where('is_active', true)->first(['id', 'name', 'sign']);

        return view('laporan.print', compact('asets', 'statistik', 'request', 'admin', 'lurah'));
    }


    private function getStatistik($request = null)
    {
        $query = Aset::query();

        // Apply filters if provided
        if ($request) {
            if ($request->filled('kategori_id')) {
                $query->where('kategori_id', $request->kategori_id);
            }

            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tanggal_pembelian', '>=', $request->tanggal_dari);
            }

            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tanggal_pembelian', '<=', $request->tanggal_sampai);
            }
        }

        return [
            'total_aset' => $query->count(),
            'total_jumlah' => $query->count(),
            'status_approved' => $query->where('status', 'approved')->count(),
            'status_pending' => $query->where('status', 'pending')->count(),
            'status_rejected' => $query->where('status', 'rejected')->count(),
            'kondisi_baik' => $query->where('kondisi', 'baik')->count(),
            'kondisi_rusak_ringan' => $query->where('kondisi', 'rusak ringan')->count(),
            'kondisi_rusak_berat' => $query->where('kondisi', 'rusak berat')->count(),
            'per_kategori' => KategoriAset::withCount('asets')->get(),
        ];
    }
}