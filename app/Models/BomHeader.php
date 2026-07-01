<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BomHeader extends Model
{
    protected $fillable = ['produk_id', 'versi', 'is_active', 'jenis_resep'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(BomDetail::class);
    }
}
