<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasukDetail extends Model
{
    protected $fillable = [
        'barang_masuk_id',
        'bahan_baku_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public function barangMasuk(): BelongsTo
    {
        return $this->belongsTo(BarangMasuk::class);
    }

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class);
    }
}
