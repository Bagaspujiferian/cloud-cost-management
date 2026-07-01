<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bahan')->unique();
            $table->string('nama_bahan');
            $table->enum('kategori', ['bahan_utama', 'bahan_isi', 'bahan_pelengkap'])->default('bahan_utama');
            $table->enum('satuan', ['gram', 'kg', 'pcs', 'liter', 'ml'])->default('gram');
            $table->decimal('stok_saat_ini', 12, 2)->default(0);
            $table->decimal('batas_minimum', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
