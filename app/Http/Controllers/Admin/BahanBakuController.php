<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index()
    {
        $bahanBakus = BahanBaku::withCount(['bomDetails', 'barangMasukDetails'])
            ->orderBy('created_at', 'desc')->get();
        return view('admin.bahan-baku.index', compact('bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255|unique:bahan_bakus,nama_bahan',
            'kategori' => 'required|in:bahan_utama,bahan_isi,bahan_pelengkap',
            'satuan' => 'required|in:gram,kg,pcs,liter,ml',
            'batas_minimum' => 'required|numeric|min:0',
        ], [
            'nama_bahan.required' => 'Nama bahan baku wajib diisi.',
            'nama_bahan.unique' => 'Nama bahan baku sudah terdaftar, silakan gunakan nama lain.',
            'nama_bahan.max' => 'Nama bahan baku maksimal 255 karakter.',
            'kategori.required' => 'Kategori bahan baku wajib dipilih.',
            'kategori.in' => 'Kategori bahan baku tidak valid.',
            'satuan.required' => 'Satuan bahan baku wajib dipilih.',
            'satuan.in' => 'Satuan bahan baku tidak valid.',
            'batas_minimum.required' => 'Batas minimum wajib diisi.',
            'batas_minimum.numeric' => 'Batas minimum harus berupa angka.',
            'batas_minimum.min' => 'Batas minimum tidak boleh kurang dari 0.',
        ]);

        BahanBaku::create([
            'kode_bahan' => BahanBaku::generateKode(),
            'nama_bahan' => $request->nama_bahan,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'stok_saat_ini' => 0,
            'batas_minimum' => $request->batas_minimum,
        ]);

        return redirect()->route('admin.bahan-baku.index')->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    public function update(Request $request, BahanBaku $bahanBaku)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255|unique:bahan_bakus,nama_bahan,' . $bahanBaku->id,
            'kategori' => 'required|in:bahan_utama,bahan_isi,bahan_pelengkap',
            'satuan' => 'required|in:gram,kg,pcs,liter,ml',
            'batas_minimum' => 'required|numeric|min:0',
        ], [
            'nama_bahan.required' => 'Nama bahan baku wajib diisi.',
            'nama_bahan.unique' => 'Nama bahan baku sudah terdaftar, silakan gunakan nama lain.',
            'nama_bahan.max' => 'Nama bahan baku maksimal 255 karakter.',
            'kategori.required' => 'Kategori bahan baku wajib dipilih.',
            'kategori.in' => 'Kategori bahan baku tidak valid.',
            'satuan.required' => 'Satuan bahan baku wajib dipilih.',
            'satuan.in' => 'Satuan bahan baku tidak valid.',
            'batas_minimum.required' => 'Batas minimum wajib diisi.',
            'batas_minimum.numeric' => 'Batas minimum harus berupa angka.',
            'batas_minimum.min' => 'Batas minimum tidak boleh kurang dari 0.',
        ]);

        $bahanBaku->update($request->only(['nama_bahan', 'kategori', 'satuan', 'batas_minimum']));

        return redirect()->route('admin.bahan-baku.index')->with('success', 'Bahan baku berhasil diperbarui!');
    }

    public function destroy(BahanBaku $bahanBaku)
    {
        // Check if bahan baku is used in any transaction
        if ($bahanBaku->bomDetails()->exists() || $bahanBaku->barangMasukDetails()->exists()) {
            return redirect()->route('admin.bahan-baku.index')
                ->with('error', 'Bahan baku tidak bisa dihapus karena sudah digunakan dalam transaksi atau resep.');
        }

        $bahanBaku->delete();
        return redirect()->route('admin.bahan-baku.index')->with('success', 'Bahan baku berhasil dihapus!');
    }
}
