<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produksi')->unique();
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_produksi');
            $table->integer('jumlah_produksi');
            $table->enum('status', ['berhasil', 'gagal'])->default('berhasil');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('produksi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->constrained('produksis')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->cascadeOnDelete();
            $table->decimal('jumlah_terpakai', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produksi_details');
        Schema::dropIfExists('produksis');
    }
};
