<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $fillable = ['nama_satuan', 'keterangan'];

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}
