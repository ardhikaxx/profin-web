<?php

namespace App\Services;

use App\Models\Stok;
use App\Models\StokHistory;
use Exception;

class StokService
{
    public function tambahStok(int $produkId, int $jumlah, string $referensiTipe, ?int $referensiId, int $userId, ?string $keterangan = null): void
    {
        $stok = Stok::firstOrCreate(['produk_id' => $produkId], ['jumlah_stok' => 0]);
        $sebelum = $stok->jumlah_stok;
        $stok->increment('jumlah_stok', $jumlah);
        $this->catatHistori($produkId, 'masuk', $jumlah, $sebelum, $stok->jumlah_stok, $referensiTipe, $referensiId, $userId, $keterangan);
    }

    public function kurangiStok(int $produkId, int $jumlah, string $referensiTipe, ?int $referensiId, int $userId, ?string $keterangan = null): void
    {
        $stok = Stok::firstOrCreate(['produk_id' => $produkId], ['jumlah_stok' => 0]);
        if ($stok->jumlah_stok < $jumlah) {
            throw new Exception('Stok tidak mencukupi untuk melakukan pengurangan.');
        }
        $sebelum = $stok->jumlah_stok;
        $stok->decrement('jumlah_stok', $jumlah);
        $this->catatHistori($produkId, 'keluar', $jumlah, $sebelum, $stok->jumlah_stok, $referensiTipe, $referensiId, $userId, $keterangan);
    }

    public function koreksiStok(int $produkId, int $stokAktual, int $userId, string $keterangan): void
    {
        $stok = Stok::firstOrCreate(['produk_id' => $produkId], ['jumlah_stok' => 0]);
        $sebelum = $stok->jumlah_stok;
        $selisih = abs($stokAktual - $sebelum);

        $stok->update(['jumlah_stok' => $stokAktual]);
        $this->catatHistori($produkId, 'koreksi', $selisih, $sebelum, $stokAktual, 'Manual', null, $userId, $keterangan);
    }

    protected function catatHistori(int $produkId, string $jenis, int $jumlah, int $sebelum, int $sesudah, ?string $refTipe, ?int $refId, int $userId, ?string $keterangan): void
    {
        StokHistory::create([
            'produk_id'      => $produkId,
            'jenis'          => $jenis,
            'jumlah'         => $jumlah,
            'stok_sebelum'   => $sebelum,
            'stok_sesudah'   => $sesudah,
            'referensi_tipe' => $refTipe,
            'referensi_id'   => $refId,
            'keterangan'     => $keterangan,
            'user_id'        => $userId,
            'created_at'     => now(),
        ]);
    }
}
