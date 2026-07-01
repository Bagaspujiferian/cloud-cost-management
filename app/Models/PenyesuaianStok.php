<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyesuaianStok extends Model
{
    protected $fillable = [
        'user_id',
        'bahan_baku_id',
        'produk_id',
        'tipe',
        'stok_tercatat',
        'stok_aktual',
        'selisih',
        'keterangan',
    ];

    protected $casts = [
        'stok_tercatat' => 'decimal:2',
        'stok_aktual' => 'decimal:2',
        'selisih' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
