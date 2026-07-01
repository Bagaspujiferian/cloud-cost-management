<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\BahanBaku;
use App\Models\KartuStok;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangMasuks = BarangMasuk::with(['supplier', 'user', 'details.bahanBaku'])
            ->orderBy('created_at', 'desc')
            ->get();
        $suppliers = Supplier::all();
        $bahanBakus = BahanBaku::all();
        return view('admin.barang-masuk.index', compact('barangMasuks', 'suppliers', 'bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_masuk' => 'required|date',
            'catatan' => 'nullable|string',
            'bahan_baku_id' => 'required|array|min:1',
            'bahan_baku_id.*' => 'exists:bahan_bakus,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'numeric|min:0.01',
            'harga_satuan' => 'nullable|array',
            'harga_satuan.*' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $barangMasuk = BarangMasuk::create([
                'kode_masuk' => BarangMasuk::generateKode(),
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'tanggal_masuk' => $request->tanggal_masuk,
                'catatan' => $request->catatan,
            ]);

            foreach ($request->bahan_baku_id as $index => $bahanBakuId) {
                $jumlah = $request->jumlah[$index];
                $hargaSatuan = $request->harga_satuan[$index] ?? 0;
                $totalHarga = $jumlah * $hargaSatuan;

                BarangMasukDetail::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'bahan_baku_id' => $bahanBakuId,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'total_harga' => $totalHarga,
                ]);

                // Update stok bahan baku
                $bahanBaku = BahanBaku::find($bahanBakuId);
                $bahanBaku->stok_saat_ini += $jumlah;
                $bahanBaku->save();

                // Catat di kartu stok
                KartuStok::create([
                    'tipe_item' => 'bahan_baku',
                    'referensi_id' => $barangMasuk->id,
                    'referensi_tipe' => 'BarangMasuk',
                    'aktivitas' => 'Belanja Bahan',
                    'nama_item' => $bahanBaku->nama_bahan,
                    'masuk' => $jumlah,
                    'keluar' => 0,
                    'sisa_stok' => $bahanBaku->stok_saat_ini,
                    'user_id' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('admin.barang-masuk.index')->with('success', 'Penerimaan barang berhasil dicatat!');
    }
}
