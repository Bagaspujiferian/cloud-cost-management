@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data stok dan aktivitas hari ini')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <!-- Total Bahan Baku -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bahan Baku</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBahanBaku }}</p>
                <p class="text-xs text-gray-400 mt-1">Jenis bahan terdaftar</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
    </div>

    <!-- Total Produk Jadi -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Produk Jadi</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalStokProdukJadi) }}</p>
                <p class="text-xs text-gray-400 mt-1">Total pcs semua varian</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            </div>
        </div>
    </div>

    <!-- Stok Rendah -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300 {{ $stokRendah > 0 ? 'ring-2 ring-red-200' : '' }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Rendah</p>
                <p class="text-3xl font-bold {{ $stokRendah > 0 ? 'text-red-600' : 'text-gray-800' }} mt-1">{{ $stokRendah }}</p>
                <p class="text-xs {{ $stokRendah > 0 ? 'text-red-400' : 'text-gray-400' }} mt-1">{{ $stokRendah > 0 ? 'Perlu segera dibelanja!' : 'Semua stok aman' }}</p>
            </div>
            <div class="w-12 h-12 {{ $stokRendah > 0 ? 'bg-red-50' : 'bg-gray-50' }} rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 {{ $stokRendah > 0 ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
    </div>

    <!-- Transaksi Hari Ini -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi Hari Ini</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $transaksiHariIni }}</p>
                <p class="text-xs text-gray-400 mt-1">Belanja + Produksi</p>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Stok Produk Jadi -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Stok Produk Jadi</h3>
        </div>
        <div class="p-5 space-y-3">
            @forelse($produkList as $produk)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-orange-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-amber-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">OO</div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $produk->nama_produk }}</p>
                        <p class="text-xs text-gray-400">{{ $produk->kode_produk }}</p>
                    </div>
                </div>
                <span class="text-lg font-bold {{ $produk->stok_jadi > 0 ? 'text-emerald-600' : 'text-gray-400' }}">{{ number_format($produk->stok_jadi) }} <span class="text-xs font-normal text-gray-400">pcs</span></span>
            </div>
            @empty
            <p class="text-center text-gray-400 py-8 text-sm">Belum ada produk</p>
            @endforelse
        </div>
    </div>

    <!-- Bahan Baku Rendah -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">⚠️ Bahan Baku Stok Rendah</h3>
        </div>
        <div class="p-5">
            @forelse($bahanBakuRendah as $bahan)
            <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl mb-2">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $bahan->nama_bahan }}</p>
                    <p class="text-xs text-red-500">Min: {{ number_format($bahan->batas_minimum) }} {{ $bahan->satuan }}</p>
                </div>
                <span class="text-lg font-bold text-red-600">{{ number_format($bahan->stok_saat_ini) }} <span class="text-xs font-normal">{{ $bahan->satuan }}</span></span>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="w-12 h-12 mx-auto text-emerald-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-gray-400">Semua stok aman!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Aktivitas Terakhir -->
<div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Aktivitas Terakhir</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                <th class="px-5 py-3">Waktu</th><th class="px-5 py-3">Aktivitas</th><th class="px-5 py-3">Item</th><th class="px-5 py-3 text-right">Masuk</th><th class="px-5 py-3 text-right">Keluar</th><th class="px-5 py-3 text-right">Sisa</th><th class="px-5 py-3">Oleh</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($aktivitasTerakhir as $akt)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $akt->created_at->format('d/m H:i') }}</td>
                    <td class="px-5 py-3"><span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ str_contains($akt->aktivitas, 'Belanja') ? 'bg-blue-100 text-blue-700' : (str_contains($akt->aktivitas, 'Produksi') ? 'bg-purple-100 text-purple-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $akt->aktivitas }}</span></td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $akt->nama_item }}</td>
                    <td class="px-5 py-3 text-right {{ $akt->masuk > 0 ? 'text-emerald-600 font-semibold' : 'text-gray-300' }}">{{ $akt->masuk > 0 ? '+'.number_format($akt->masuk, 0) : '-' }}</td>
                    <td class="px-5 py-3 text-right {{ $akt->keluar > 0 ? 'text-red-600 font-semibold' : 'text-gray-300' }}">{{ $akt->keluar > 0 ? '-'.number_format($akt->keluar, 0) : '-' }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-800">{{ number_format($akt->sisa_stok, 0) }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $akt->user->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada aktivitas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
