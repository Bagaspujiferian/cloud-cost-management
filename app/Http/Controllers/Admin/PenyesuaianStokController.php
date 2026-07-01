<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use App\Models\KartuStok;
use App\Models\PenyesuaianStok;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyesuaianStokController extends Controller
{
    public function index()
    {
        $penyesuaians = PenyesuaianStok::with(['user', 'bahanBaku', 'produk'])
            ->orderBy('created_at', 'desc')->get();
        $bahanBakus = BahanBaku::all();
        $produks = Produk::all();
        return view('admin.penyesuaian-stok.index', compact('penyesuaians', 'bahanBakus', 'produks'));
    }

    public function store(Request $request)
    {
        $messages = [
            'tipe.required' => 'Tipe item wajib dipilih.',
            'tipe.in' => 'Tipe item tidak valid.',
            'item_id.required' => 'Item wajib dipilih.',
            'stok_aktual.required' => 'Stok aktual wajib diisi.',
            'stok_aktual.numeric' => 'Stok aktual harus berupa angka.',
            'stok_aktual.min' => 'Stok aktual tidak boleh minus.',
            'keterangan.required' => 'Keterangan wajib diisi untuk keperluan audit dan pertanggungjawaban.',
        ];

        $request->validate([
            'tipe' => 'required|in:bahan_baku,produk_jadi',
            'item_id' => 'required|integer',
            'stok_aktual' => 'required|numeric|min:0',
            'keterangan' => 'required|string',
        ], $messages);

        if ($request->tipe === 'bahan_baku') {
            $item = BahanBaku::findOrFail($request->item_id);
            $stokTercatat = $item->stok_saat_ini;
            if ($request->stok_aktual >= $stokTercatat) {
                return redirect()->back()->with('error', 'Penyesuaian stok hanya boleh untuk mengurangi bahan (karena rusak/hilang). Untuk menambah stok, silakan gunakan menu Belanja Bahan!')->withInput();
            }
        } else {
            $item = Produk::findOrFail($request->item_id);
            $stokTercatat = $item->stok_jadi;
            if ($request->stok_aktual >= $stokTercatat) {
                return redirect()->back()->with('error', 'Penyesuaian stok hanya boleh untuk mengurangi produk (karena basi/rusak). Untuk menambah stok, silakan gunakan menu Catat Produksi!')->withInput();
            }
        }

        DB::transaction(function () use ($request, $item, $stokTercatat) {
            $selisih = $request->stok_aktual - $stokTercatat;

            if ($request->tipe === 'bahan_baku') {
                PenyesuaianStok::create([
                    'user_id' => auth()->id(), 'bahan_baku_id' => $item->id, 'produk_id' => null,
                    'tipe' => 'bahan_baku', 'stok_tercatat' => $stokTercatat,
                    'stok_aktual' => $request->stok_aktual, 'selisih' => $selisih,
                    'keterangan' => $request->keterangan,
                ]);
                $item->stok_saat_ini = $request->stok_aktual;
                $item->save();

                KartuStok::create([
                    'tipe_item' => 'bahan_baku', 'referensi_id' => $item->id, 'referensi_tipe' => 'PenyesuaianStok',
                    'aktivitas' => 'Penyesuaian Stok', 'nama_item' => $item->nama_bahan,
                    'masuk' => 0, 'keluar' => abs($selisih),
                    'sisa_stok' => $item->stok_saat_ini, 'user_id' => auth()->id(),
                ]);
            } else {
                PenyesuaianStok::create([
                    'user_id' => auth()->id(), 'bahan_baku_id' => null, 'produk_id' => $item->id,
                    'tipe' => 'produk_jadi', 'stok_tercatat' => $stokTercatat,
                    'stok_aktual' => $request->stok_aktual, 'selisih' => $selisih,
                    'keterangan' => $request->keterangan,
                ]);
                $item->stok_jadi = $request->stok_aktual;
                $item->save();

                KartuStok::create([
                    'tipe_item' => 'produk_jadi', 'referensi_id' => $item->id, 'referensi_tipe' => 'PenyesuaianStok',
                    'aktivitas' => 'Penyesuaian Stok', 'nama_item' => $item->nama_produk,
                    'masuk' => 0, 'keluar' => abs($selisih),
                    'sisa_stok' => $item->stok_jadi, 'user_id' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('admin.penyesuaian-stok.index')->with('success', 'Penyesuaian stok berhasil dicatat!');
    }
}
