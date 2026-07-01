<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bom_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete();
            $table->string('versi')->default('1.0');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bom_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_header_id')->constrained('bom_headers')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->cascadeOnDelete();
            $table->decimal('kuantitas', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bom_details');
        Schema::dropIfExists('bom_headers');
    }
};
