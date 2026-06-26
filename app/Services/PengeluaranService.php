<?php

namespace App\Services;

use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;
use Exception;

class PengeluaranService
{
    public function __construct(
        protected AuditLogService $auditLog
    ) {}

    public function generateKodeTransaksi(string $tanggal): string
    {
        $dateStr = date('Ymd', strtotime($tanggal));
        $prefix  = "EXP-{$dateStr}-";
        
        $last = Pengeluaran::where('kode_transaksi', 'like', "{$prefix}%")
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        if ($last) {
            $num = (int) substr($last->kode_transaksi, -3) + 1;
        } else {
            $num = 1;
        }

        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function simpan(array $data, ?string $pathFoto = null): Pengeluaran
    {
        $data['kode_transaksi'] = $this->generateKodeTransaksi($data['tanggal_pengeluaran']);
        $data['karyawan_id']    = $data['karyawan_id'] ?? Auth::id();
        $data['status']         = 'draft';
        if ($pathFoto) {
            $data['bukti_foto'] = $pathFoto;
        }

        $pengeluaran = Pengeluaran::create($data);
        
        $this->auditLog->catat('Pengeluaran', 'create', "Input pengeluaran {$pengeluaran->kode_transaksi}", null, $pengeluaran->toArray());
        
        return $pengeluaran;
    }

    public function update(Pengeluaran $pengeluaran, array $data, ?string $pathFoto = null): Pengeluaran
    {
        if ($pengeluaran->status === 'terverifikasi' && Auth::user()->role !== 'owner') {
            throw new Exception('Data pengeluaran yang telah diverifikasi tidak dapat diubah.');
        }

        $lama = $pengeluaran->toArray();
        if ($pathFoto) {
            $data['bukti_foto'] = $pathFoto;
        }

        $pengeluaran->update($data);
        
        $this->auditLog->catat('Pengeluaran', 'update', "Update pengeluaran {$pengeluaran->kode_transaksi}", $lama, $pengeluaran->fresh()->toArray());
        
        return $pengeluaran;
    }

    public function verifikasi(Pengeluaran $pengeluaran): Pengeluaran
    {
        $lama = $pengeluaran->toArray();
        $pengeluaran->update([
            'status'      => 'terverifikasi',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);
        
        $this->auditLog->catat('Pengeluaran', 'verify', "Verifikasi pengeluaran {$pengeluaran->kode_transaksi}", $lama, $pengeluaran->fresh()->toArray());
        
        return $pengeluaran;
    }

    public function hapus(Pengeluaran $pengeluaran): void
    {
        $lama = $pengeluaran->toArray();
        $kode = $pengeluaran->kode_transaksi;
        
        $pengeluaran->delete();
        
        $this->auditLog->catat('Pengeluaran', 'delete', "Hapus pengeluaran {$kode}", $lama, null);
    }
}
