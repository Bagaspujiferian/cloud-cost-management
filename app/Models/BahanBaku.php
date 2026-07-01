<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanBaku extends Model
{
    protected $fillable = [
        'kode_bahan',
        'nama_bahan',
        'kategori',
        'satuan',
        'stok_saat_ini',
        'batas_minimum',
    ];

    protected $casts = [
        'stok_saat_ini' => 'decimal:2',
        'batas_minimum' => 'decimal:2',
    ];

    public function bomDetails(): HasMany
    {
        return $this->hasMany(BomDetail::class);
    }

    public function barangMasukDetails(): HasMany
    {
        return $this->hasMany(BarangMasukDetail::class);
    }

    public function isStokRendah(): bool
    {
        return $this->stok_saat_ini <= $this->batas_minimum;
    }

    public static function generateKode(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $num = $last ? intval(substr($last->kode_bahan, 3)) + 1 : 1;
        return 'BB-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
