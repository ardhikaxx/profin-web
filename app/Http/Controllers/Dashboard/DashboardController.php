<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\Stok;
use App\Models\Pengeluaran;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'owner') {
            $totalProduksiHariIni = Produksi::whereDate('tanggal_produksi', today())->sum('jumlah_bersih');
            $jumlahStokProduk     = Stok::sum('jumlah_stok');
            $totalPengeluaranBulan= Pengeluaran::whereMonth('tanggal_pengeluaran', now()->month)
                                    ->whereYear('tanggal_pengeluaran', now()->year)
                                    ->sum('jumlah');

            $produksisBulan = Produksi::with('produk')
                                ->whereMonth('tanggal_produksi', now()->month)
                                ->whereYear('tanggal_produksi', now()->year)
                                ->get();
            
            $estimasiPendapatanBulan = $produksisBulan->sum(fn($p) => $p->jumlah_bersih * ($p->produk->harga_estimasi ?? 0));
            $labaSementara = $estimasiPendapatanBulan - $totalPengeluaranBulan;

            $jumlahTransaksiPengeluaran = Pengeluaran::whereMonth('tanggal_pengeluaran', now()->month)
                                            ->whereYear('tanggal_pengeluaran', now()->year)
                                            ->count();

            $produkStokRendah = Stok::with('produk')->get()->filter(fn($s) => $s->jumlah_stok <= ($s->produk->stok_minimum ?? 0));
            $produksiPending  = Produksi::where('status', 'draft')->count();

            // Data grafik produksi 30 hari terakhir
            $grafikProduksi = Produksi::selectRaw('DATE(tanggal_produksi) as tanggal, SUM(jumlah_bersih) as total')
                                ->where('tanggal_produksi', '>=', now()->subDays(30))
                                ->groupBy('tanggal')
                                ->orderBy('tanggal')
                                ->get();

            // Data grafik pengeluaran per kategori bulan ini
            $grafikPengeluaran = Pengeluaran::selectRaw('kategori_pengeluaran_id, SUM(jumlah) as total')
                                    ->with('kategori')
                                    ->whereMonth('tanggal_pengeluaran', now()->month)
                                    ->groupBy('kategori_pengeluaran_id')
                                    ->get();

            $produksiTerbaru = Produksi::with(['produk', 'karyawan'])->orderBy('created_at', 'desc')->limit(5)->get();

            return view('dashboard.index', compact(
                'totalProduksiHariIni', 'jumlahStokProduk', 'totalPengeluaranBulan',
                'estimasiPendapatanBulan', 'labaSementara', 'jumlahTransaksiPengeluaran',
                'produkStokRendah', 'produksiPending', 'grafikProduksi', 'grafikPengeluaran',
                'produksiTerbaru'
            ));
        } else {
            // Dashboard Karyawan
            $produksiHariIni = Produksi::where('karyawan_id', $user->id)
                                ->whereDate('tanggal_produksi', today())
                                ->sum('jumlah_bersih');
            
            $pengeluaranHariIni = Pengeluaran::where('karyawan_id', $user->id)
                                    ->whereDate('tanggal_pengeluaran', today())
                                    ->sum('jumlah');

            $stokSeluruhProduk = Stok::with(['produk.satuan'])->get();
            $produksiTerbaru   = Produksi::with('produk')->where('karyawan_id', $user->id)->orderBy('created_at', 'desc')->limit(5)->get();

            return view('dashboard.karyawan', compact(
                'produksiHariIni', 'pengeluaranHariIni', 'stokSeluruhProduk', 'produksiTerbaru'
            ));
        }
    }
}
