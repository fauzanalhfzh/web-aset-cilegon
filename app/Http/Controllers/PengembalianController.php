<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\AsetKeluar;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Pengembalian::with('asetKeluar.aset', 'approver')->get();
        return view('pengembalian.index', compact('pengembalians'));
    }

    public function create()
    {
        $asetKeluars = AsetKeluar::where('status', 'approved')->get();
        return view('pengembalian.create', compact('asetKeluars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aset_keluar_id' => 'required|exists:aset_keluars,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pengembalian' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $asetKeluar = AsetKeluar::findOrFail($request->aset_keluar_id);
        if ($asetKeluar->status !== 'approved') {
            if ($request->ajax()) {
                return response()->json(['message' => 'Hanya aset keluar yang sudah disetujui yang dapat dikembalikan.'], 422);
            }
            return redirect()->back()->withErrors(['aset_keluar_id' => 'Hanya aset keluar yang sudah disetujui yang dapat dikembalikan.']);
        }

        if ($request->jumlah > $asetKeluar->jumlah) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Jumlah pengembalian melebihi jumlah aset keluar.'], 422);
            }
            return redirect()->back()->withErrors(['jumlah' => 'Jumlah pengembalian melebihi jumlah aset keluar.']);
        }

        Pengembalian::create(array_merge($request->all(), ['status' => 'pending']));

        if ($request->ajax()) {
            return response()->json(['message' => 'Pengembalian berhasil ditambahkan, menunggu persetujuan.']);
        }

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil ditambahkan, menunggu persetujuan.');
    }

    public function edit(Pengembalian $pengembalian)
    {
        if ($pengembalian->status === 'approved') {
            return redirect()->route('pengembalian.index')->with('error', 'Pengembalian yang sudah dis7etujui tidak dapat diedit.');
        }

        $asetKeluars = AsetKeluar::where('status', 'approved')->get();
        return view('pengembalian.edit', compact('pengembalian', 'asetKeluars'));
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        if ($pengembalian->status === 'approved') {
            if ($request->ajax()) {
                return response()->json(['message' => 'Pengembalian yang sudah disetujui tidak dapat diedit.'], 422);
            }
            return redirect()->route('pengembalian.index')->with('error', 'Pengembalian yang sudah disetujui tidak dapat diedit.');
        }

        $request->validate([
            'aset_keluar_id' => 'required|exists:aset_keluars,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pengembalian' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $asetKeluar = AsetKeluar::findOrFail($request->aset_keluar_id);
        if ($asetKeluar->status !== 'approved') {
            if ($request->ajax()) {
                return response()->json(['message' => 'Hanya aset keluar yang sudah disetujui yang dapat dikembalikan.'], 422);
            }
            return redirect()->back()->withErrors(['aset_keluar_id' => 'Hanya aset keluar yang sudah disetujui yang dapat dikembalikan.']);
        }

        if ($request->jumlah > $asetKeluar->jumlah) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Jumlah pengembalian melebihi jumlah aset keluar.'], 422);
            }
            return redirect()->back()->withErrors(['jumlah' => 'Jumlah pengembalian melebihi jumlah aset keluar.']);
        }

        $pengembalian->update($request->all());

        if ($request->ajax()) {
            return response()->json(['message' => 'Pengembalian berhasil diperbarui.']);
        }

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil diperbarui.');
    }

    public function approve(Pengembalian $pengembalian, Request $request)
    {
        if ($pengembalian->status === 'approved') {
            if ($request->ajax()) {
                return response()->json(['message' => 'Pengembalian sudah disetujui.'], 422);
            }
            return redirect()->route('pengembalian.index')->with('error', 'Pengembalian sudah disetujui.');
        }

        $asetKeluar = AsetKeluar::findOrFail($pengembalian->aset_keluar_id);
        if ($pengembalian->jumlah > $asetKeluar->jumlah) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Jumlah pengembalian melebihi jumlah aset keluar.'], 422);
            }
            return redirect()->route('pengembalian.index')->with('error', 'Jumlah pengembalian melebihi jumlah aset keluar.');
        }

        $pengembalian->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        // Tambahkan jumlah aset kembali ke tabel aset
        $aset = Aset::findOrFail($asetKeluar->aset_id);
        $aset->jumlah += $pengembalian->jumlah;
        $aset->save();

        if ($request->ajax()) {
            return response()->json(['message' => 'Pengembalian berhasil disetujui.']);
        }

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil disetujui.');
    }

    public function destroy(Pengembalian $pengembalian, Request $request)
    {
        if ($pengembalian->status === 'approved') {
            if ($request->ajax()) {
                return response()->json(['message' => 'Pengembalian yang sudah disetujui tidak dapat dihapus.'], 422);
            }
            return redirect()->route('pengembalian.index')->with('error', 'Pengembalian yang sudah disetujui tidak dapat dihapus.');
        }

        $pengembalian->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Pengembalian berhasil dihapus.']);
        }

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil dihapus.');
    }
}