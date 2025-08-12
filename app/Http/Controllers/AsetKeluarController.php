<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\AsetKeluar;
use Illuminate\Http\Request;

class AsetKeluarController extends Controller
{
    public function index()
    {
        $asetKeluars = AsetKeluar::with('aset')->get(); // Hapus 'approver'
        return view('aset-keluar.index', compact('asetKeluars'));
    }

    public function create()
    {
        $asets = Aset::all(); // Hapus filter status 'approved'
        return view('aset-keluar.create', compact('asets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aset_id' => 'required|exists:asets,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $aset = Aset::findOrFail($request->aset_id);
        if ($request->jumlah > $aset->jumlah) {
            return $request->ajax()
                ? response()->json(['message' => 'Jumlah aset keluar melebihi jumlah aset yang tersedia.'], 422)
                : redirect()->back()->withErrors(['jumlah' => 'Jumlah aset keluar melebihi jumlah aset yang tersedia.']);
        }

        AsetKeluar::create($request->all());

        // Kurangi jumlah aset langsung setelah dibuat
        $aset->jumlah -= $request->jumlah;
        $aset->save();

        return $request->ajax()
            ? response()->json(['message' => 'Aset keluar berhasil ditambahkan.'])
            : redirect()->route('aset.index')->with('success', 'Aset keluar berhasil ditambahkan.');
    }

    public function edit(AsetKeluar $asetKeluar)
    {
        $asets = Aset::all();
        return view('aset-keluar.edit', compact('asetKeluar', 'asets'));
    }

    public function update(Request $request, AsetKeluar $asetKeluar)
    {
        $request->validate([
            'aset_id' => 'required|exists:asets,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $aset = Aset::findOrFail($request->aset_id);

        // Tambahkan kembali jumlah sebelumnya ke stok
        $aset->jumlah += $asetKeluar->jumlah;

        // Cek apakah jumlah baru melebihi stok
        if ($request->jumlah > $aset->jumlah) {
            return $request->ajax()
                ? response()->json(['message' => 'Jumlah aset keluar melebihi jumlah aset yang tersedia.'], 422)
                : redirect()->back()->withErrors(['jumlah' => 'Jumlah aset keluar melebihi jumlah aset yang tersedia.']);
        }

        $asetKeluar->update($request->all());

        // Kurangi jumlah aset berdasarkan nilai baru
        $aset->jumlah -= $request->jumlah;
        $aset->save();

        return $request->ajax()
            ? response()->json(['message' => 'Aset keluar berhasil diperbarui.'])
            : redirect()->route('aset-keluar.index')->with('success', 'Aset keluar berhasil diperbarui.');
    }

    public function destroy(AsetKeluar $asetKeluar, Request $request)
    {
        // Tambahkan kembali jumlah ke stok saat data dihapus
        $aset = Aset::find($asetKeluar->aset_id);
        if ($aset) {
            $aset->jumlah += $asetKeluar->jumlah;
            $aset->save();
        }

        $asetKeluar->delete();

        return $request->ajax()
            ? response()->json(['message' => 'Aset keluar berhasil dihapus.'])
            : redirect()->route('aset-keluar.index')->with('success', 'Aset keluar berhasil dihapus.');
    }


    public function return(AsetKeluar $asetKeluar, Request $request)
{
    $aset = Aset::find($asetKeluar->aset_id);
    if ($aset) {
        $aset->jumlah += $asetKeluar->jumlah;
        $aset->save();
    }

    $asetKeluar->delete();

    return $request->ajax()
        ? response()->json(['message' => 'Aset berhasil dikembalikan ke stok.'])
        : redirect()->route('aset-keluar.index')->with('success', 'Aset berhasil dikembalikan ke stok.');
}

}
