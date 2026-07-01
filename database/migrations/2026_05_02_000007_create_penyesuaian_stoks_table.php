<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyesuaian_stoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->nullable()->constrained('bahan_bakus')->nullOnDelete();
            $table->foreignId('produk_id')->nullable()->constrained('produks')->nullOnDelete();
            $table->enum('tipe', ['bahan_baku', 'produk_jadi']);
            $table->decimal('stok_tercatat', 12, 2);
            $table->decimal('stok_aktual', 12, 2);
            $table->decimal('selisih', 12, 2);
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_stoks');
    }
};
