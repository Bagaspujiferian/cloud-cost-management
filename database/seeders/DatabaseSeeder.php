<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\BahanBaku;
use App\Models\Produk;
use App\Models\BomHeader;
use App\Models\BomDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Users =====
        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@ondeonde.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Owner',
            'email' => 'owner@ondeonde.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // ===== Suppliers =====
        Supplier::create([
            'nama' => 'Toko Bahan Kue Makmur',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Pasar Baru No. 15, Jakarta',
        ]);

        Supplier::create([
            'nama' => 'UD Sumber Rezeki',
            'telepon' => '082345678901',
            'alamat' => 'Jl. Raya Cibitung No. 8, Bekasi',
        ]);

        // ===== Bahan Baku =====
        $tepungKetan = BahanBaku::create([
            'kode_bahan' => 'BB-0001',
            'nama_bahan' => 'Tepung Ketan',
            'kategori' => 'bahan_utama',
            'satuan' => 'gram',
            'stok_saat_ini' => 50000,
            'batas_minimum' => 5000,
        ]);

        $wijen = BahanBaku::create([
            'kode_bahan' => 'BB-0002',
            'nama_bahan' => 'Wijen',
            'kategori' => 'bahan_pelengkap',
            'satuan' => 'gram',
            'stok_saat_ini' => 10000,
            'batas_minimum' => 2000,
        ]);

        $kacangHijau = BahanBaku::create([
            'kode_bahan' => 'BB-0003',
            'nama_bahan' => 'Kacang Hijau',
            'kategori' => 'bahan_isi',
            'satuan' => 'gram',
            'stok_saat_ini' => 20000,
            'batas_minimum' => 3000,
        ]);

        $selaiCoklat = BahanBaku::create([
            'kode_bahan' => 'BB-0004',
            'nama_bahan' => 'Selai Coklat',
            'kategori' => 'bahan_isi',
            'satuan' => 'gram',
            'stok_saat_ini' => 15000,
            'batas_minimum' => 2000,
        ]);

        $kejuCheddar = BahanBaku::create([
            'kode_bahan' => 'BB-0005',
            'nama_bahan' => 'Keju Cheddar',
            'kategori' => 'bahan_isi',
            'satuan' => 'gram',
            'stok_saat_ini' => 10000,
            'batas_minimum' => 1500,
        ]);

        // ===== Produk =====
        $ondeKacang = Produk::create([
            'kode_produk' => 'PRD-0001',
            'nama_produk' => 'Onde-Onde Kacang Hijau',
            'deskripsi' => 'Onde-onde klasik dengan isian kacang hijau',
            'stok_jadi' => 0,
        ]);

        $ondeCoklat = Produk::create([
            'kode_produk' => 'PRD-0002',
            'nama_produk' => 'Onde-Onde Coklat Lumer',
            'deskripsi' => 'Onde-onde dengan isian selai coklat lumer',
            'stok_jadi' => 0,
        ]);

        $ondeKeju = Produk::create([
            'kode_produk' => 'PRD-0003',
            'nama_produk' => 'Onde-Onde Keju',
            'deskripsi' => 'Onde-onde dengan isian keju cheddar',
            'stok_jadi' => 0,
        ]);

        // ===== BOM (Resep) =====
        // Onde-Onde Kacang Hijau
        $bomKacang = BomHeader::create([
            'produk_id' => $ondeKacang->id,
            'versi' => '1.0',
            'is_active' => true,
        ]);
        BomDetail::create(['bom_header_id' => $bomKacang->id, 'bahan_baku_id' => $tepungKetan->id, 'kuantitas' => 20]);
        BomDetail::create(['bom_header_id' => $bomKacang->id, 'bahan_baku_id' => $wijen->id, 'kuantitas' => 5]);
        BomDetail::create(['bom_header_id' => $bomKacang->id, 'bahan_baku_id' => $kacangHijau->id, 'kuantitas' => 10]);

        // Onde-Onde Coklat Lumer
        $bomCoklat = BomHeader::create([
            'produk_id' => $ondeCoklat->id,
            'versi' => '1.0',
            'is_active' => true,
        ]);
        BomDetail::create(['bom_header_id' => $bomCoklat->id, 'bahan_baku_id' => $tepungKetan->id, 'kuantitas' => 20]);
        BomDetail::create(['bom_header_id' => $bomCoklat->id, 'bahan_baku_id' => $wijen->id, 'kuantitas' => 5]);
        BomDetail::create(['bom_header_id' => $bomCoklat->id, 'bahan_baku_id' => $selaiCoklat->id, 'kuantitas' => 10]);

        // Onde-Onde Keju
        $bomKeju = BomHeader::create([
            'produk_id' => $ondeKeju->id,
            'versi' => '1.0',
            'is_active' => true,
        ]);
        BomDetail::create(['bom_header_id' => $bomKeju->id, 'bahan_baku_id' => $tepungKetan->id, 'kuantitas' => 20]);
        BomDetail::create(['bom_header_id' => $bomKeju->id, 'bahan_baku_id' => $wijen->id, 'kuantitas' => 5]);
        BomDetail::create(['bom_header_id' => $bomKeju->id, 'bahan_baku_id' => $kejuCheddar->id, 'kuantitas' => 10]);
    }
}
