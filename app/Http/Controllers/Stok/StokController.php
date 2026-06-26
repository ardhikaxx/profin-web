<?php

namespace App\Http\Controllers\Stok;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\StokHistory;
use App\Models\Produk;
use App\Services\StokService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    public function __construct(protected StokService $stokService) {}

    public function index()
    {
        $allStoks = Stok::with(['produk.satuan'])->get();
        
        $totalProduk = $allStoks->count();
        $stokNormal  = $allStoks->filter(fn($s) => $s->jumlah_stok > ($s->produk->stok_minimum ?? 0))->count();
        $stokKritis  = $allStoks->filter(fn($s) => $s->jumlah_stok <= ($s->produk->stok_minimum ?? 0))->count();

        $stoks = Stok::with(['produk.satuan'])->paginate(10);
        $produks = Produk::where('is_active', true)->orderBy('nama_produk')->get();

        return view('stok.index', compact('stoks', 'allStoks', 'totalProduk', 'stokNormal', 'stokKritis', 'produks'));
    }

    public function histori(Request $request)
    {
        $query = StokHistory::with(['produk.satuan', 'user']);

        if ($request->filled('produk_id')) {
            $query->where('produk_id', $request->produk_id);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereDate('created_at', '>=', $request->dari)->whereDate('created_at', '<=', $request->sampai);
        }

        $histories = $query->orderBy('created_at', 'desc')->paginate(15);
        $produks   = Produk::orderBy('nama_produk')->get();

        return view('stok.histori', compact('histories', 'produks'));
    }

    public function kurangi(Request $request)
    {
        $request->validate([
            'produk_id'  => 'required|exists:produks,id',
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'required|string',
        ]);

        $this->stokService->kurangiStok(
            $request->produk_id,
            $request->jumlah,
            'Distribusi/Penjualan',
            null,
            Auth::id(),
            $request->keterangan
        );

        return redirect()->back()->with('success', 'Stok berhasil dikurangi.');
    }

    public function koreksi(Request $request)
    {
        $request->validate([
            'produk_id'   => 'required|exists:produks,id',
            'stok_aktual' => 'required|integer|min:0',
            'keterangan'  => 'required|string',
        ]);

        $this->stokService->koreksiStok(
            $request->produk_id,
            $request->stok_aktual,
            Auth::id(),
            $request->keterangan
        );

        return redirect()->back()->with('success', 'Koreksi stok aktual berhasil dilakukan.');
    }
}
