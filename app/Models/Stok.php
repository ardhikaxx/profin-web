<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $fillable = ['produk_id', 'jumlah_stok'];

    protected function casts(): array
    {
        return [
            'jumlah_stok' => 'integer',
        ];
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
