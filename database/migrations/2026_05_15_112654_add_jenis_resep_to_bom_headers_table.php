<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bom_headers', function (Blueprint $table) {
            $table->enum('jenis_resep', ['standar', 'alternatif', 'khusus'])->default('standar')->after('produk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bom_headers', function (Blueprint $table) {
            $table->dropColumn('jenis_resep');
        });
    }
};
