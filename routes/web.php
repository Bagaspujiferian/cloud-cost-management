<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BahanBakuController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\BomController;
use App\Http\Controllers\Admin\BarangMasukController;
use App\Http\Controllers\Admin\ProduksiController;
use App\Http\Controllers\Admin\PenyesuaianStokController;
use App\Http\Controllers\Admin\KartuStokController;

// Redirect root to login
Route::get('/', fn() => redirect('/login'));

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('bahan-baku', BahanBakuController::class)->except(['show', 'create', 'edit']);
    Route::resource('supplier', SupplierController::class)->except(['show', 'create', 'edit']);

    // BOM
    Route::resource('bom', BomController::class)->except(['show', 'create', 'edit']);

    // Transaksi (Create & Read only)
    Route::get('barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
    Route::post('barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');

    Route::get('produksi', [ProduksiController::class, 'index'])->name('produksi.index');
    Route::post('produksi', [ProduksiController::class, 'store'])->name('produksi.store');

    Route::get('penyesuaian-stok', [PenyesuaianStokController::class, 'index'])->name('penyesuaian-stok.index');
    Route::post('penyesuaian-stok', [PenyesuaianStokController::class, 'store'])->name('penyesuaian-stok.store');

    // Kartu Stok (Read only)
    Route::get('kartu-stok', [KartuStokController::class, 'index'])->name('kartu-stok.index');
});

// Owner routes
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/master-data', [\App\Http\Controllers\Owner\AsetController::class, 'index'])->name('aset.index');
    Route::resource('karyawan', \App\Http\Controllers\Owner\KaryawanController::class)->except(['show']);
    
    Route::get('/laporan', [\App\Http\Controllers\Owner\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [\App\Http\Controllers\Owner\LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
});
