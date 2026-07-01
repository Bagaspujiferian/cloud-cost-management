<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use App\Models\Produk;
use App\Models\BarangMasuk;
use App\Models\Produksi;
use App\Models\KartuStok;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBahanBaku = BahanBaku::count();
        $totalProduk = Produk::count();
        $stokRendah = BahanBaku::whereColumn('stok_saat_ini', '<=', 'batas_minimum')->count();
        $transaksiHariIni = BarangMasuk::whereDate('tanggal_masuk', today())->count()
            + Produksi::whereDate('tanggal_produksi', today())->count();

        $totalStokProdukJadi = Produk::sum('stok_jadi');
        $produksiHariIni = Produksi::whereDate('tanggal_produksi', today())->sum('jumlah_produksi');
        $barangMasukHariIni = BarangMasuk::whereDate('tanggal_masuk', today())->count();

        $bahanBakuRendah = BahanBaku::whereColumn('stok_saat_ini', '<=', 'batas_minimum')->get();
        $aktivitasTerakhir = KartuStok::with('user')->latest()->take(10)->get();
        $produkList = Produk::all();

        return view('admin.dashboard', compact(
            'totalBahanBaku',
            'totalProduk',
            'stokRendah',
            'transaksiHariIni',
            'totalStokProdukJadi',
            'produksiHariIni',
            'barangMasukHariIni',
            'bahanBakuRendah',
            'aktivitasTerakhir',
            'produkList'
        ));
    }
}
