<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsetController extends Controller
{
    public function index()
    {
        $asets = Aset::with('kategori')->get();
        return view('aset.index', compact('asets'));
    }

    public function create()
    {
        $kategoris = KategoriAset::all();
        return view('aset.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_asets,id',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baik,Rusak,Perlu Perbaikan',
            'tanggal_pembelian' => 'required|date',
        ]);

        Aset::create(array_merge($request->all(), ['status' => 'pending']));

        if ($request->ajax()) {
            return response()->json(['message' => 'Aset berhasil ditambahkan, menunggu persetujuan.']);
        }

        return redirect()->route('aset.index')->with('success', 'Aset berhasil ditambahkan, menunggu persetujuan.');
    }

    public function edit(Aset $aset)
    {
        $kategoris = KategoriAset::all();
        return view('aset.edit', compact('aset', 'kategoris'));
    }

    public function update(Request $request, Aset $aset)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_asets,id',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baik,Rusak,Perlu Perbaikan',
            'tanggal_pembelian' => 'required|date',
        ]);

        $aset->update($request->all());

        if ($request->ajax()) {
            return response()->json(['message' => 'Aset berhasil diperbarui.']);
        }

        return redirect()->route('aset.index')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Aset $aset, Request $request)
    {
        $aset->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Aset berhasil dihapus.']);
        }

        return redirect()->route('aset.index')->with('success', 'Aset berhasil dihapus.');
    }

    public function approve(Aset $aset, Request $request)
    {
        if ($aset->status === 'approved') {
            if ($request->ajax()) {
                return response()->json(['message' => 'Aset sudah disetujui.'], 422);
            }
            return redirect()->route('aset.index')->with('error', 'Aset sudah disetujui.');
        }

        $aset->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => 'Aset berhasil disetujui.']);
        }

        return redirect()->route('aset.index')->with('success', 'Aset berhasil disetujui.');
    }
}