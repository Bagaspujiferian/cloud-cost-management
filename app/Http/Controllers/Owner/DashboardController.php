<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\BarangMasuk;
use App\Models\Produksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $rentang = $request->get('rentang', 'hari_ini');
        
        $startDate = Carbon::today();
        $endDate = Carbon::now();
        
        if ($rentang === 'minggu_ini') {
            $startDate = Carbon::now()->startOfWeek();
        } elseif ($rentang === 'bulan_ini') {
            $startDate = Carbon::now()->startOfMonth();
        }

        // Summary Cards
        $totalProduksiPcs = Produksi::whereBetween('created_at', [$startDate, $endDate])->sum('jumlah_produksi');
        $totalOndeSiapJual = Produk::sum('stok_jadi');
        
        $totalBahanMasuk = \App\Models\BarangMasukDetail::whereHas('barangMasuk', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('total_harga');

        // Low Stock Alerts
        $bahanBakuKritis = BahanBaku::whereRaw('stok_saat_ini <= batas_minimum')->get();

        // Chart Data (Last 7 Days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = Produksi::whereDate('created_at', $date)->sum('jumlah_produksi');
        }

        return view('owner.dashboard', compact(
            'totalProduksiPcs',
            'totalOndeSiapJual',
            'totalBahanMasuk',
            'bahanBakuKritis',
            'chartLabels',
            'chartData',
            'rentang'
        ));
    }
}
