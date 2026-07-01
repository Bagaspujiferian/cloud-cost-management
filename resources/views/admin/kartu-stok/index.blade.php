@extends('layouts.admin')
@section('title', 'Kartu Stok')
@section('page-title', 'Kartu Stok')
@section('page-subtitle', 'Riwayat audit trail seluruh pergerakan stok')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">📋 Kartu Stok — Audit Trail</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                <th class="px-5 py-3">Waktu</th>
                <th class="px-5 py-3">Aktivitas</th>
                <th class="px-5 py-3">Tipe</th>
                <th class="px-5 py-3">Nama Barang</th>
                <th class="px-5 py-3 text-right">Masuk (+)</th>
                <th class="px-5 py-3 text-right">Keluar (-)</th>
                <th class="px-5 py-3 text-right">Sisa Stok</th>
                <th class="px-5 py-3">Oleh</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($kartuStoks as $ks)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap text-xs">{{ $ks->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                            {{ str_contains($ks->aktivitas, 'Belanja') ? 'bg-blue-100 text-blue-700' :
                              (str_contains($ks->aktivitas, 'Produksi') ? 'bg-purple-100 text-purple-700' :
                              (str_contains($ks->aktivitas, 'Penyesuaian') ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ $ks->aktivitas }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $ks->tipe_item === 'bahan_baku' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }}">{{ $ks->tipe_item === 'bahan_baku' ? 'Bahan Baku' : 'Produk Jadi' }}</span>
                    </td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $ks->nama_item }}</td>
                    <td class="px-5 py-3 text-right {{ $ks->masuk > 0 ? 'text-emerald-600 font-semibold' : 'text-gray-300' }}">{{ $ks->masuk > 0 ? '+'.number_format($ks->masuk, 0) : '-' }}</td>
                    <td class="px-5 py-3 text-right {{ $ks->keluar > 0 ? 'text-red-600 font-semibold' : 'text-gray-300' }}">{{ $ks->keluar > 0 ? '-'.number_format($ks->keluar, 0) : '-' }}</td>
                    <td class="px-5 py-3 text-right font-bold text-gray-800">{{ number_format($ks->sisa_stok, 0) }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $ks->user->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-gray-400 text-sm">Belum ada catatan pergerakan stok</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kartuStoks->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $kartuStoks->links() }}
    </div>
    @endif
</div>
@endsection
