<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_stoks', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_item', ['bahan_baku', 'produk_jadi']);
            $table->unsignedBigInteger('referensi_id');
            $table->string('referensi_tipe'); // e.g. BarangMasuk, Produksi, PenyesuaianStok
            $table->string('aktivitas'); // e.g. Belanja, Produksi, Penyesuaian
            $table->string('nama_item');
            $table->decimal('masuk', 12, 2)->default(0);
            $table->decimal('keluar', 12, 2)->default(0);
            $table->decimal('sisa_stok', 12, 2)->default(0);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_stoks');
    }
};
