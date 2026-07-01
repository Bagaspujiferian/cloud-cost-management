<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KartuStok extends Model
{
    protected $fillable = [
        'tipe_item',
        'referensi_id',
        'referensi_tipe',
        'aktivitas',
        'nama_item',
        'masuk',
        'keluar',
        'sisa_stok',
        'user_id',
    ];

    protected $casts = [
        'masuk' => 'decimal:2',
        'keluar' => 'decimal:2',
        'sisa_stok' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
