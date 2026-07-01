@extends('layouts.owner')
@section('title', 'Dashboard Eksekutif')
@section('page-title', 'Dashboard Eksekutif')
@section('page-subtitle', 'Ringkasan kesehatan bisnis & performa produksi')

@section('content')
<!-- Filter Rentang Waktu -->
<div class="mb-6 flex justify-end">
    <form method="GET" action="{{ route('owner.dashboard') }}" class="flex items-center gap-2">
        <label class="text-sm font-medium text-gray-600">Rentang Waktu:</label>
        <select name="rentang" onchange="this.form.submit()" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 bg-white">
            <option value="hari_ini" {{ $rentang === 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
            <option value="minggu_ini" {{ $rentang === 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
            <option value="bulan_ini" {{ $rentang === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
        </select>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform"></div>
        <div class="relative flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Produksi ({{ ucfirst(str_replace('_', ' ', $rentang)) }})</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalProduksiPcs, 0) }} <span class="text-base font-medium text-gray-500">Pcs</span></h3>
            </div>
            <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform"></div>
        <div class="relative flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Stok Onde Siap Jual</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalOndeSiapJual, 0) }} <span class="text-base font-medium text-gray-500">Pcs</span></h3>
            </div>
            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform"></div>
        <div class="relative flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Pengeluaran Bahan Baku</p>
                <h3 class="text-3xl font-bold text-gray-800"><span class="text-base font-medium text-gray-500">Rp</span> {{ number_format($totalBahanMasuk, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 bg-amber-100 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Tren Produksi (7 Hari Terakhir)</h3>
        <div class="h-72 w-full">
            <canvas id="produksiChart"></canvas>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-gray-100 bg-red-50/50 flex items-center gap-2">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
            <h3 class="font-semibold text-red-800">Peringatan Stok Kritis</h3>
        </div>
        <div class="p-0 flex-1 overflow-y-auto">
            @if($bahanBakuKritis->count() > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach($bahanBakuKritis as $bahan)
                    <li class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-medium text-gray-800">{{ $bahan->nama_bahan }}</span>
                            <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded">Kritis</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Sisa: <strong class="text-gray-800">{{ number_format($bahan->stok_saat_ini, 0) }}</strong> {{ $bahan->satuan }}</span>
                            <span class="text-gray-400 text-xs">Min: {{ number_format($bahan->batas_minimum, 0) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                            <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ min(100, ($bahan->stok_saat_ini / max(1, $bahan->batas_minimum)) * 100) }}%"></div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="p-8 text-center flex flex-col items-center justify-center h-full">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-gray-500 font-medium">Semua stok bahan baku aman!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('produksiChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(249, 115, 22, 0.5)'); // orange-500
        gradient.addColorStop(1, 'rgba(249, 115, 22, 0.0)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Produksi (Pcs)',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#f97316', // orange-500
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f97316',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Pcs';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 12 }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 12 }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
