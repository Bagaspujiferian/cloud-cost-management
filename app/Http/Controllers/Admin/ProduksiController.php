<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use App\Models\KartuStok;
use App\Models\Produk;
use App\Models\Produksi;
use App\Models\ProduksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    public function index()
    {
        $produksis = Produksi::with(['produk', 'user', 'details.bahanBaku'])
            ->orderBy('created_at', 'desc')
            ->get();
        $produks = Produk::with(['activeBom.details.bahanBaku'])->get();
        return view('admin.produksi.index', compact('produksis', 'produks'));
    }

    public function store(Request $request)
    {
        $messages = [
            'produk_id.required' => 'Produk wajib dipilih.',
            'produk_id.exists' => 'Produk tidak ditemukan.',
            'tanggal_produksi.required' => 'Tanggal produksi wajib diisi.',
            'tanggal_produksi.date' => 'Format tanggal tidak valid.',
            'jumlah_produksi.required' => 'Jumlah produksi wajib diisi.',
            'jumlah_produksi.integer' => 'Jumlah produksi harus berupa angka bulat.',
            'jumlah_produksi.min' => 'Jumlah produksi minimal adalah 1.',
            'catatan.required' => 'Catatan wajib diisi untuk keperluan audit.',
        ];

        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'tanggal_produksi' => 'required|date',
            'jumlah_produksi' => 'required|integer|min:1',
            'catatan' => 'required|string',
        ], $messages);

        $produk = Produk::with('activeBom.details.bahanBaku')->find($request->produk_id);

        if (!$produk->activeBom) {
            return redirect()->back()->with('error', 'Produk ini belum memiliki resep (BOM) aktif!');
        }

        // Check if enough stock for all materials
        $bomDetails = $produk->activeBom->details;
        $insufficientStock = [];

        foreach ($bomDetails as $detail) {
            $needed = $detail->kuantitas * $request->jumlah_produksi;
            if ($detail->bahanBaku->stok_saat_ini < $needed) {
                $insufficientStock[] = $detail->bahanBaku->nama_bahan .
                    " (butuh: {$needed} {$detail->bahanBaku->satuan}, tersedia: {$detail->bahanBaku->stok_saat_ini} {$detail->bahanBaku->satuan})";
            }
        }

        if (!empty($insufficientStock)) {
            return redirect()->back()->with('error', 'Stok bahan baku tidak mencukupi: ' . implode(', ', $insufficientStock));
        }

        DB::transaction(function () use ($request, $produk, $bomDetails) {
            $produksi = Produksi::create([
                'kode_produksi' => Produksi::generateKode(),
                'produk_id' => $request->produk_id,
                'user_id' => auth()->id(),
                'tanggal_produksi' => $request->tanggal_produksi,
                'jumlah_produksi' => $request->jumlah_produksi,
                'status' => 'berhasil',
                'catatan' => $request->catatan,
            ]);

            // Auto-deduct bahan baku based on BOM
            foreach ($bomDetails as $detail) {
                $jumlahTerpakai = $detail->kuantitas * $request->jumlah_produksi;

                ProduksiDetail::create([
                    'produksi_id' => $produksi->id,
                    'bahan_baku_id' => $detail->bahan_baku_id,
                    'jumlah_terpakai' => $jumlahTerpakai,
                ]);

                // Kurangi stok bahan baku
                $bahanBaku = $detail->bahanBaku;
                $bahanBaku->stok_saat_ini -= $jumlahTerpakai;
                $bahanBaku->save();

                // Catat di kartu stok (bahan baku keluar)
                KartuStok::create([
                    'tipe_item' => 'bahan_baku',
                    'referensi_id' => $produksi->id,
                    'referensi_tipe' => 'Produksi',
                    'aktivitas' => 'Produksi - ' . $produk->nama_produk,
                    'nama_item' => $bahanBaku->nama_bahan,
                    'masuk' => 0,
                    'keluar' => $jumlahTerpakai,
                    'sisa_stok' => $bahanBaku->stok_saat_ini,
                    'user_id' => auth()->id(),
                ]);
            }

            // Tambah stok produk jadi
            $produk->stok_jadi += $request->jumlah_produksi;
            $produk->save();

            // Catat di kartu stok (produk jadi masuk)
            KartuStok::create([
                'tipe_item' => 'produk_jadi',
                'referensi_id' => $produksi->id,
                'referensi_tipe' => 'Produksi',
                'aktivitas' => 'Hasil Produksi',
                'nama_item' => $produk->nama_produk,
                'masuk' => $request->jumlah_produksi,
                'keluar' => 0,
                'sisa_stok' => $produk->stok_jadi,
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()->route('admin.produksi.index')->with('success', 'Produksi berhasil dicatat! Stok bahan baku otomatis terpotong.');
    }
}
