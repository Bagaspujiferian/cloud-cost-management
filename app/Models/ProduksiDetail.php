<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProduksiDetail extends Model
{
    protected $fillable = [
        'produksi_id',
        'bahan_baku_id',
        'jumlah_terpakai',
    ];

    protected $casts = [
        'jumlah_terpakai' => 'decimal:2',
    ];

    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class);
    }

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class);
    }
}
