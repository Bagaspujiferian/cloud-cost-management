<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produksi extends Model
{
    protected $table = 'produksis';

    protected $fillable = [
        'kode_produksi',
        'produk_id',
        'user_id',
        'tanggal_produksi',
        'jumlah_produksi',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ProduksiDetail::class);
    }

    public static function generateKode(): string
    {
        $today = now()->format('Ymd');
        $last = self::where('kode_produksi', 'like', "PRO-{$today}%")->orderBy('id', 'desc')->first();
        $num = $last ? intval(substr($last->kode_produksi, -4)) + 1 : 1;
        return "PRO-{$today}-" . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
