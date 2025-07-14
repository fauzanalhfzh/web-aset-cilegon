<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\AsetKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsetKeluarController extends Controller
{
    public function index()
    {
        $asetKeluars = AsetKeluar::with('aset', 'approver')->get();
        return view('aset-keluar.index', compact('asetKeluars'));
    }

    public function create()
    {
        $asets = Aset::where('status', 'approved')->get();
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
            if ($request->ajax()) {
                return response()->json(['message' => 'Jumlah aset keluar melebihi jumlah aset yang tersedia.'], 422);
            }
            return redirect()->back()->withErrors(['jumlah' => 'Jumlah aset keluar melebihi jumlah aset yang tersedia.']);
        }

        AsetKeluar::create(array_merge($request->all(), ['status' => 'pending']));

        if ($request->ajax()) {
            return response()->json(['message' => 'Aset keluar berhasil ditambahkan, menunggu persetujuan.']);
        }

        return redirect()->route('aset-keluar.index')->with('success', 'Aset keluar berhasil ditambahkan, menunggu persetujuan.');
    }

    public function edit(AsetKeluar $asetKeluar)
    {
        if ($asetKeluar->status === 'approved') {
            return redirect()->route('aset-keluar.index')->with('error', 'Aset keluar yang sudah disetujui tidak dapat diedit.');
        }

        $asets = Aset::where('status', 'approved')->get();
        return view('aset-keluar.edit', compact('asetKeluar', 'asets'));
    }

    public function update(Request $request, AsetKeluar $asetKeluar)
    {
        if ($asetKeluar->status === 'approved') {
            if ($request->ajax()) {
                return response()->json(['message' => 'Aset keluar yang sudah disetujui tidak dapat diedit.'], 422);
            }
            return redirect()->route('aset-keluar.index')->with('error', 'Aset keluar yang sudah disetujui tidak dapat diedit.');
        }

        $request->validate([
            'aset_id' => 'required|exists:asets,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $aset = Aset::findOrFail($request->aset_id);
        if ($request->jumlah > $aset->jumlah) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Jumlah aset keluar melebihi jumlah aset yang tersedia.'], 422);
            }
            return redirect()->back()->withErrors(['jumlah' => 'Jumlah aset keluar melebihi jumlah aset yang tersedia.']);
        }

        $asetKeluar->update($request->all());

        if ($request->ajax()) {
            return response()->json(['message' => 'Aset keluar berhasil diperbarui.']);
        }

        return redirect()->route('aset-keluar.index')->with('success', 'Aset keluar berhasil diperbarui.');
    }

    public function approve(AsetKeluar $asetKeluar, Request $request)
    {
        if ($asetKeluar->status === 'approved') {
            if ($request->ajax()) {
                return response()->json(['message' => 'Aset keluar sudah disetujui.'], 422);
            }
            return redirect()->route('aset-keluar.index')->with('error', 'Aset keluar sudah disetujui.');
        }

        $aset = Aset::findOrFail($asetKeluar->aset_id);
        if ($asetKeluar->jumlah > $aset->jumlah) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Jumlah aset keluar melebihi jumlah aset yang tersedia.'], 422);
            }
            return redirect()->route('aset-keluar.index')->with('error', 'Jumlah aset keluar melebihi jumlah aset yang tersedia.');
        }

        $asetKeluar->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        // Kurangi jumlah aset di tabel aset
        $aset->jumlah -= $asetKeluar->jumlah;
        $aset->save();

        if ($request->ajax()) {
            return response()->json(['message' => 'Aset keluar berhasil disetujui.']);
        }

        return redirect()->route('aset-keluar.index')->with('success', 'Aset keluar berhasil disetujui.');
    }

    public function destroy(AsetKeluar $asetKeluar, Request $request)
    {
        if ($asetKeluar->status === 'approved') {
            if ($request->ajax()) {
                return response()->json(['message' => 'Aset keluar yang sudah disetujui tidak dapat dihapus.'], 422);
            }
            return redirect()->route('aset-keluar.index')->with('error', 'Aset keluar yang sudah disetujui tidak dapat dihapus.');
        }

        $asetKeluar->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Aset keluar berhasil dihapus.']);
        }

        return redirect()->route('aset-keluar.index')->with('success', 'Aset keluar berhasil dihapus.');
    }
}