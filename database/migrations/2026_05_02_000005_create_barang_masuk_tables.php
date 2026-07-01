<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_masuk')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_masuk');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('barang_masuk_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuks')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->cascadeOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuk_details');
        Schema::dropIfExists('barang_masuks');
    }
};
