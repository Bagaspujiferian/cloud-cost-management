@extends('layouts.owner')
@section('title', 'Laporan & Audit')
@section('page-title', 'Laporan & Audit Inventaris')
@section('page-subtitle', 'Pantau seluruh pergerakan barang dan cetak laporan untuk tutup buku')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col mb-6">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
        <form action="{{ route('owner.laporan.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600">Dari:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 bg-white" required>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600">Sampai:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 bg-white" required>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white font-medium rounded-xl hover:bg-gray-700 transition-colors text-sm w-full sm:w-auto text-center shadow-sm">
                Terapkan Filter
            </button>
        </form>

        <a href="{{ route('owner.laporan.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 border border-red-200 transition-colors text-sm w-full sm:w-auto shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Cetak PDF
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-4 font-semibold whitespace-nowrap">Waktu</th>
                    <th class="px-5 py-4 font-semibold whitespace-nowrap">Oleh</th>
                    <th class="px-5 py-4 font-semibold whitespace-nowrap">Aktivitas</th>
                    <th class="px-5 py-4 font-semibold">Nama Barang</th>
                    <th class="px-5 py-4 font-semibold whitespace-nowrap text-right">Keluar/Masuk</th>
                    <th class="px-5 py-4 font-semibold whitespace-nowrap text-right">Stok Akhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 whitespace-nowrap">
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</p>
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap font-medium text-gray-800">
                        {{ $log->user->name ?? 'Sistem' }}
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap">
                        @if($log->aktivitas == 'Barang Masuk')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                                Belanja
                            </span>
                        @elseif($log->aktivitas == 'Produksi')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                Masak
                            </span>
                        @elseif($log->aktivitas == 'Penyesuaian Stok')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Koreksi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-700 border border-gray-100">
                                {{ $log->aktivitas }}
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="font-medium text-gray-800">{{ $log->nama_item }}</span>
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        @if($log->masuk > 0)
                            <span class="font-bold text-emerald-600">+{{ number_format($log->masuk, 0) }}</span>
                        @elseif($log->keluar > 0)
                            <span class="font-bold text-red-600">-{{ number_format($log->keluar, 0) }}</span>
                        @else
                            <span class="text-gray-400">0</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <span class="font-bold text-gray-800">{{ number_format($log->sisa_stok, 0) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3 border border-gray-100">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3 class="text-gray-800 font-bold mb-1">Tidak Ada Data</h3>
                            <p class="text-gray-500 text-sm max-w-sm">Belum ada aktivitas keluar-masuk barang pada rentang tanggal yang dipilih.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
