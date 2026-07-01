<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\Produk;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->get('search');
        
        $bahanBakuQuery = BahanBaku::query();
        if ($searchQuery) {
            $bahanBakuQuery->where('nama_bahan', 'like', "%{$searchQuery}%")
                           ->orWhere('id_bahan', 'like', "%{$searchQuery}%");
        }
        $bahanBaku = $bahanBakuQuery->orderBy('created_at', 'desc')->get();

        $produkQuery = Produk::query();
        if ($searchQuery) {
            $produkQuery->where('nama_produk', 'like', "%{$searchQuery}%")
                        ->orWhere('id_produk', 'like', "%{$searchQuery}%");
        }
        $produk = $produkQuery->orderBy('created_at', 'desc')->get();

        return view('owner.aset.index', compact('bahanBaku', 'produk', 'searchQuery'));
    }
}
