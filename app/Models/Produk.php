<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'satuan_id',
        'harga_estimasi',
        'stok_minimum',
        'deskripsi',
        'is_active'
    ];

    protected function casts(): array
    {
        return [
            'harga_estimasi' => 'decimal:2',
            'stok_minimum' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function stok()
    {
        return $this->hasOne(Stok::class);
    }

    public function produksis()
    {
        return $this->hasMany(Produksi::class);
    }
}
