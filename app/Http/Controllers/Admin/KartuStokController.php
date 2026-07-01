<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuStok;

class KartuStokController extends Controller
{
    public function index()
    {
        $kartuStoks = KartuStok::with('user')->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.kartu-stok.index', compact('kartuStoks'));
    }
}
