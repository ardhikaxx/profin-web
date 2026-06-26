<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'produk_id',
        'jenis',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'referensi_tipe',
        'referensi_id',
        'keterangan',
        'user_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'stok_sebelum' => 'integer',
            'stok_sesudah' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
