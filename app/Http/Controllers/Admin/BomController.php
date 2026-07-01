<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use App\Models\BomDetail;
use App\Models\BomHeader;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
{
    public function index()
    {
        $produks = Produk::with(['activeBom.details.bahanBaku'])->get();
        $bahanBakus = BahanBaku::all();
        return view('admin.bom.index', compact('produks', 'bahanBakus'));
    }

    public function store(Request $request)
    {
        $messages = [
            'produk_id.required' => 'Produk wajib dipilih.',
            'nama_produk_baru.required_if' => 'Nama menu baru wajib diisi.',
            'nama_produk_baru.unique' => 'Nama menu/produk ini sudah ada, silakan pilih dari dropdown atau gunakan nama lain.',
            'jenis_resep.required' => 'Jenis resep wajib dipilih.',
            'jenis_resep.in' => 'Jenis resep tidak valid.',
            'bahan_baku_id.required' => 'Minimal satu komposisi bahan baku wajib diisi.',
            'bahan_baku_id.*.exists' => 'Bahan baku tidak ditemukan.',
            'bahan_baku_id.*.distinct' => 'Tidak boleh ada bahan baku yang sama/ganda dalam satu resep.',
            'kuantitas.required' => 'Kuantitas wajib diisi.',
            'kuantitas.*.numeric' => 'Kuantitas harus berupa angka.',
            'kuantitas.*.min' => 'Kuantitas minimal adalah 0.01.',
            'produk_id.exists' => 'Produk yang dipilih tidak valid.'
        ];

        $request->validate([
            'produk_id' => 'required', // We will manually check if it's new or existing
            'nama_produk_baru' => 'required_if:produk_id,new|string|max:255|unique:produks,nama_produk',
            'jenis_resep' => 'required|in:standar,alternatif,khusus',
            'bahan_baku_id' => 'required|array|min:1',
            'bahan_baku_id.*' => 'exists:bahan_bakus,id|distinct',
            'kuantitas' => 'required|array|min:1',
            'kuantitas.*' => 'numeric|min:0.01',
        ], $messages);

        $produkId = $request->produk_id;

        if ($produkId !== 'new') {
            $request->validate(['produk_id' => 'exists:produks,id'], $messages);
        }

        DB::transaction(function () use ($request, &$produkId) {
            // Create new product if requested
            if ($produkId === 'new') {
                $produk = Produk::create([
                    'kode_produk' => Produk::generateKode(),
                    'nama_produk' => $request->nama_produk_baru,
                    'stok_jadi' => 0,
                ]);
                $produkId = $produk->id;
            }

            // Deactivate old BOM for this product if we are creating a new one
            BomHeader::where('produk_id', $produkId)->update(['is_active' => false]);

            // Create new BOM
            $lastBom = BomHeader::where('produk_id', $produkId)->orderBy('id', 'desc')->first();
            $versi = $lastBom ? number_format(floatval($lastBom->versi) + 0.1, 1) : '1.0';

            $bomHeader = BomHeader::create([
                'produk_id' => $produkId,
                'versi' => $versi,
                'jenis_resep' => $request->jenis_resep,
                'is_active' => true,
            ]);

            foreach ($request->bahan_baku_id as $index => $bahanBakuId) {
                BomDetail::create([
                    'bom_header_id' => $bomHeader->id,
                    'bahan_baku_id' => $bahanBakuId,
                    'kuantitas' => $request->kuantitas[$index],
                ]);
            }
        });

        return redirect()->route('admin.bom.index')->with('success', 'Resep berhasil disimpan!');
    }

    public function update(Request $request, BomHeader $bom)
    {
        $messages = [
            'jenis_resep.required' => 'Jenis resep wajib dipilih.',
            'jenis_resep.in' => 'Jenis resep tidak valid.',
            'bahan_baku_id.required' => 'Minimal satu komposisi bahan baku wajib diisi.',
            'bahan_baku_id.*.exists' => 'Bahan baku tidak ditemukan.',
            'bahan_baku_id.*.distinct' => 'Tidak boleh ada bahan baku yang sama/ganda dalam satu resep.',
            'kuantitas.required' => 'Kuantitas wajib diisi.',
            'kuantitas.*.numeric' => 'Kuantitas harus berupa angka.',
            'kuantitas.*.min' => 'Kuantitas minimal adalah 0.01.',
        ];

        $request->validate([
            'jenis_resep' => 'required|in:standar,alternatif,khusus',
            'bahan_baku_id' => 'required|array|min:1',
            'bahan_baku_id.*' => 'exists:bahan_bakus,id|distinct',
            'kuantitas' => 'required|array|min:1',
            'kuantitas.*' => 'numeric|min:0.01',
        ], $messages);

        DB::transaction(function () use ($request, $bom) {
            $bom->update(['jenis_resep' => $request->jenis_resep]);

            // Delete existing details and re-create
            $bom->details()->delete();

            foreach ($request->bahan_baku_id as $index => $bahanBakuId) {
                BomDetail::create([
                    'bom_header_id' => $bom->id,
                    'bahan_baku_id' => $bahanBakuId,
                    'kuantitas' => $request->kuantitas[$index],
                ]);
            }
        });

        return redirect()->route('admin.bom.index')->with('success', 'Resep berhasil diperbarui!');
    }

    public function destroy(BomHeader $bom)
    {
        $bom->delete();
        return redirect()->route('admin.bom.index')->with('success', 'Resep berhasil dihapus!');
    }
}
