<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarangMasuk extends Model
{
    protected $fillable = [
        'kode_masuk',
        'supplier_id',
        'user_id',
        'tanggal_masuk',
        'catatan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(BarangMasukDetail::class);
    }

    public static function generateKode(): string
    {
        $today = now()->format('Ymd');
        $last = self::where('kode_masuk', 'like', "IN-{$today}%")->orderBy('id', 'desc')->first();
        $num = $last ? intval(substr($last->kode_masuk, -4)) + 1 : 1;
        return "IN-{$today}-" . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
