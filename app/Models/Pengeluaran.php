<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'tanggal_pengeluaran',
        'kategori_pengeluaran_id',
        'jumlah',
        'keterangan',
        'bukti_foto',
        'karyawan_id',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengeluaran' => 'date',
            'jumlah' => 'decimal:2',
            'verified_at' => 'datetime',
        ];
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'kategori_pengeluaran_id');
    }

    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
