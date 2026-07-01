<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BomDetail extends Model
{
    protected $fillable = ['bom_header_id', 'bahan_baku_id', 'kuantitas'];

    protected $casts = [
        'kuantitas' => 'decimal:2',
    ];

    public function bomHeader(): BelongsTo
    {
        return $this->belongsTo(BomHeader::class);
    }

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class);
    }
}
