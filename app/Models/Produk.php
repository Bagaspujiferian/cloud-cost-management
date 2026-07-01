<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Produk extends Model
{
    protected $table = 'produks';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'deskripsi',
        'stok_jadi',
    ];

    protected $casts = [
        'stok_jadi' => 'decimal:2',
    ];

    public function bomHeaders(): HasMany
    {
        return $this->hasMany(BomHeader::class);
    }

    public function activeBom(): HasOne
    {
        return $this->hasOne(BomHeader::class)->where('is_active', true);
    }

    public function produksis(): HasMany
    {
        return $this->hasMany(Produksi::class);
    }

    public static function generateKode(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $num = $last ? intval(substr($last->kode_produk, 4)) + 1 : 1;
        return 'PRD-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
