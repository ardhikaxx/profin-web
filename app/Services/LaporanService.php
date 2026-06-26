<?php

namespace App\Services;

use App\Models\Produksi;
use App\Models\Stok;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class LaporanService
{
    public function getLaporanProduksi(Request $request, bool $paginate = false)
    {
        $query = Produksi::with(['produk', 'satuan', 'karyawan']);

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_produksi', [$request->dari, $request->sampai]);
        } elseif ($request->filled('periode')) {
            if ($request->periode === 'minggu') {
                $query->whereBetween('tanggal_produksi', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->periode === 'bulan') {
                $query->whereMonth('tanggal_produksi', now()->month)->whereYear('tanggal_produksi', now()->year);
            }
        }

        if ($request->filled('produk_id')) {
            $query->where('produk_id', $request->produk_id);
        }

        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        $query->orderBy('tanggal_produksi', 'desc');
        $allData = (clone $query)->get();

        $summary = [
            'total_produksi' => $allData->sum('jumlah_produksi'),
            'total_gagal'    => $allData->sum('jumlah_gagal'),
            'total_bersih'   => $allData->sum('jumlah_bersih'),
        ];

        $data = $paginate ? $query->paginate(10)->withQueryString() : $allData;

        return compact('data', 'summary');
    }

    public function getLaporanStok(Request $request, bool $paginate = false)
    {
        $query = Stok::with(['produk.satuan']);

        if ($request->filled('status')) {
            if ($request->status === 'kritis') {
                $query->whereHas('produk', function($q) {
                    $q->whereColumn('stoks.jumlah_stok', '<=', 'produks.stok_minimum');
                });
            } elseif ($request->status === 'normal') {
                $query->whereHas('produk', function($q) {
                    $q->whereColumn('stoks.jumlah_stok', '>', 'produks.stok_minimum');
                });
            }
        }

        if ($request->filled('produk_id')) {
            $query->where('produk_id', $request->produk_id);
        }

        $data = $paginate ? $query->paginate(10)->withQueryString() : $query->get();

        return compact('data');
    }

    public function getLaporanPengeluaran(Request $request, bool $paginate = false)
    {
        $query = Pengeluaran::with(['kategori', 'karyawan']);

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pengeluaran', [$request->dari, $request->sampai]);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_pengeluaran_id', $request->kategori_id);
        }

        $query->orderBy('tanggal_pengeluaran', 'desc');
        $allData = (clone $query)->get();

        $summary = [
            'total_pengeluaran' => $allData->sum('jumlah'),
        ];

        $data = $paginate ? $query->paginate(10)->withQueryString() : $allData;

        return compact('data', 'summary');
    }

    public function getLaporanLabaRugi(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $produksis = Produksi::with('produk')
            ->whereMonth('tanggal_produksi', $bulan)
            ->whereYear('tanggal_produksi', $tahun)
            ->get();

        $pendapatanPerProduk = [];
        $totalPendapatan = 0;

        foreach ($produksis->groupBy('produk_id') as $produkId => $items) {
            $produk = $items->first()->produk;
            $totalBersih = $items->sum('jumlah_bersih');
            $subtotal = $totalBersih * $produk->harga_estimasi;
            
            $pendapatanPerProduk[] = [
                'nama_produk'    => $produk->nama_produk,
                'total_bersih'   => $totalBersih,
                'satuan'         => $produk->satuan->nama_satuan ?? 'unit',
                'harga_estimasi' => $produk->harga_estimasi,
                'subtotal'       => $subtotal,
                'total'          => $subtotal,
            ];

            $totalPendapatan += $subtotal;
        }

        $pengeluarans = Pengeluaran::with('kategori')
            ->whereMonth('tanggal_pengeluaran', $bulan)
            ->whereYear('tanggal_pengeluaran', $tahun)
            ->get();

        $pengeluaranPerKategori = [];
        $totalPengeluaran = 0;

        foreach ($pengeluarans->groupBy('kategori_pengeluaran_id') as $katId => $items) {
            $kat = $items->first()->kategori;
            $subtotal = $items->sum('jumlah');

            $pengeluaranPerKategori[] = [
                'nama_kategori' => $kat->nama_kategori ?? 'Lainnya',
                'subtotal'      => $subtotal,
                'total'         => $subtotal,
            ];

            $totalPengeluaran += $subtotal;
        }

        $labaBersih = $totalPendapatan - $totalPengeluaran;

        return compact('bulan', 'tahun', 'pendapatanPerProduk', 'totalPendapatan', 'pengeluaranPerKategori', 'totalPengeluaran', 'labaBersih');
    }
}
