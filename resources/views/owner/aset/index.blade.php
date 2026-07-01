@extends('layouts.owner')
@section('title', 'Pantau Aset Gudang')
@section('page-title', 'Pantau Aset Gudang')
@section('page-subtitle', 'Melihat status seluruh bahan baku dan barang jadi tanpa hak ubah (Read-Only)')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div class="w-full sm:max-w-xs relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <form action="{{ route('owner.aset.index') }}" method="GET">
            <input type="text" name="search" value="{{ $searchQuery }}" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 bg-white transition-shadow" placeholder="Cari ID atau nama barang...">
        </form>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <!-- Tabel Bahan Baku -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                Stok Bahan Baku
            </h3>
            <span class="text-xs font-medium text-gray-500 bg-gray-200 px-2.5 py-1 rounded-full">{{ $bahanBaku->count() }} Jenis</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 font-semibold w-16">ID</th>
                        <th class="px-4 py-3 font-semibold">Nama Bahan</th>
                        <th class="px-4 py-3 font-semibold">Stok Aktual</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($bahanBaku as $bahan)
                    <tr class="hover:bg-orange-50/30 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $bahan->id_bahan }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $bahan->nama_bahan }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $bahan->kategori }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-gray-800">{{ number_format($bahan->stok_saat_ini, 0) }}</span> 
                            <span class="text-xs text-gray-500">{{ $bahan->satuan }}</span>
                            <p class="text-xs text-gray-400 mt-0.5">Min: {{ number_format($bahan->batas_minimum, 0) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if($bahan->stok_saat_ini <= $bahan->batas_minimum)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                    Kritis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aman
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <p class="text-gray-500 font-medium">Belum ada data bahan baku.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Produk Jadi -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                Stok Barang Jadi
            </h3>
            <span class="text-xs font-medium text-gray-500 bg-gray-200 px-2.5 py-1 rounded-full">{{ $produk->count() }} Jenis</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 font-semibold w-16">ID</th>
                        <th class="px-4 py-3 font-semibold">Nama Produk</th>
                        <th class="px-4 py-3 font-semibold text-right">Stok Siap Jual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($produk as $p)
                    <tr class="hover:bg-orange-50/30 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->id_produk }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->nama_produk }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="font-bold text-gray-800 text-base">{{ number_format($p->stok_jadi, 0) }}</span> 
                            <span class="text-xs text-gray-500">Pcs</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                <p class="text-gray-500 font-medium">Belum ada data barang jadi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
