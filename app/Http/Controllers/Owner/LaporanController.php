<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KartuStok;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $logs = KartuStok::whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('owner.laporan.index', compact('logs', 'startDate', 'endDate'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $logs = KartuStok::whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('owner.laporan.pdf', compact('logs', 'startDate', 'endDate'));
        
        $filename = 'Laporan_Inventaris_' . Carbon::parse($startDate)->format('d_M_Y') . '_sd_' . Carbon::parse($endDate)->format('d_M_Y') . '.pdf';
        
        return $pdf->stream($filename);
    }
}
