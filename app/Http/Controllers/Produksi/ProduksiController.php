<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\Produk;
use App\Models\User;
use App\Http\Requests\ProduksiRequest;
use App\Services\ProduksiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    public function __construct(protected ProduksiService $produksiService) {}

    public function index(Request $request)
    {
        $query = Produksi::with(['produk', 'satuan', 'karyawan']);

        if (Auth::user()->role === 'karyawan') {
            $query->where('karyawan_id', Auth::id());
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_produksi', [$request->dari, $request->sampai]);
        }

        if ($request->filled('produk_id')) {
            $query->where('produk_id', $request->produk_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $produksis = $query->orderBy('tanggal_produksi', 'desc')->orderBy('created_at', 'desc')->paginate(10);
        $produks   = Produk::where('is_active', true)->orderBy('nama_produk')->get();

        return view('produksi.index', compact('produksis', 'produks'));
    }

    public function create()
    {
        $produks   = Produk::with('satuan')->where('is_active', true)->orderBy('nama_produk')->get();
        $karyawans = Auth::user()->role === 'owner' ? User::where('role', 'karyawan')->where('is_active', true)->get() : [];

        return view('produksi.create', compact('produks', 'karyawans'));
    }

    public function store(ProduksiRequest $request)
    {
        $this->produksiService->simpan($request->validated());
        return redirect()->route('produksi.index')->with('success', 'Data produksi berhasil disimpan.');
    }

    public function show(Produksi $produksi)
    {
        if (Auth::user()->role === 'karyawan' && $produksi->karyawan_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        $produksi->load(['produk', 'satuan', 'karyawan', 'verifier']);
        return view('produksi.show', compact('produksi'));
    }

    public function edit(Produksi $produksi)
    {
        if (Auth::user()->role === 'karyawan') {
            if ($produksi->karyawan_id !== Auth::id() || $produksi->status === 'terverifikasi') {
                abort(403, 'Data yang sudah diverifikasi atau bukan milik Anda tidak dapat diedit.');
            }
        }

        $produks   = Produk::with('satuan')->where('is_active', true)->orderBy('nama_produk')->get();
        $karyawans = Auth::user()->role === 'owner' ? User::where('role', 'karyawan')->where('is_active', true)->get() : [];

        return view('produksi.edit', compact('produksi', 'produks', 'karyawans'));
    }

    public function update(ProduksiRequest $request, Produksi $produksi)
    {
        $this->produksiService->update($produksi, $request->validated());
        return redirect()->route('produksi.index')->with('success', 'Data produksi berhasil diperbarui.');
    }

    public function destroy(Produksi $produksi)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Hanya Owner yang berhak menghapus data produksi.');
        }

        $this->produksiService->hapus($produksi);
        return redirect()->route('produksi.index')->with('success', 'Data produksi beserta histori stok berhasil dihapus.');
    }

    public function verifikasi(Produksi $produksi)
    {
        $this->produksiService->verifikasi($produksi);
        return redirect()->back()->with('success', "Produksi {$produksi->kode_produksi} berhasil diverifikasi.");
    }
}
