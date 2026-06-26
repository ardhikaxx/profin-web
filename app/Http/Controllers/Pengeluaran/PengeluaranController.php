<?php

namespace App\Http\Controllers\Pengeluaran;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use App\Http\Requests\PengeluaranRequest;
use App\Services\PengeluaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function __construct(protected PengeluaranService $pengeluaranService) {}

    public function index(Request $request)
    {
        $query = Pengeluaran::with(['kategori', 'karyawan']);

        if (Auth::user()->role === 'karyawan') {
            $query->where('karyawan_id', Auth::id());
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pengeluaran', [$request->dari, $request->sampai]);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_pengeluaran_id', $request->kategori_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengeluarans = $query->orderBy('tanggal_pengeluaran', 'desc')->orderBy('created_at', 'desc')->paginate(10);
        $kategoris    = KategoriPengeluaran::where('is_active', true)->orderBy('nama_kategori')->get();

        return view('pengeluaran.index', compact('pengeluarans', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriPengeluaran::where('is_active', true)->orderBy('nama_kategori')->get();
        return view('pengeluaran.create', compact('kategoris'));
    }

    public function store(PengeluaranRequest $request)
    {
        $pathFoto = null;
        if ($request->hasFile('bukti_foto')) {
            $pathFoto = $request->file('bukti_foto')->store('bukti_pengeluaran', 'public');
        }

        $this->pengeluaranService->simpan($request->validated(), $pathFoto);
        return redirect()->route('pengeluaran.index')->with('success', 'Transaksi pengeluaran berhasil disimpan.');
    }

    public function show(Pengeluaran $pengeluaran)
    {
        if (Auth::user()->role === 'karyawan' && $pengeluaran->karyawan_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        $pengeluaran->load(['kategori', 'karyawan', 'verifier']);
        return view('pengeluaran.show', compact('pengeluaran'));
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        if (Auth::user()->role === 'karyawan') {
            if ($pengeluaran->karyawan_id !== Auth::id() || $pengeluaran->status === 'terverifikasi') {
                abort(403, 'Data yang sudah diverifikasi atau bukan milik Anda tidak dapat diedit.');
            }
        }

        $kategoris = KategoriPengeluaran::where('is_active', true)->orderBy('nama_kategori')->get();
        return view('pengeluaran.edit', compact('pengeluaran', 'kategoris'));
    }

    public function update(PengeluaranRequest $request, Pengeluaran $pengeluaran)
    {
        $pathFoto = null;
        if ($request->hasFile('bukti_foto')) {
            if ($pengeluaran->bukti_foto) {
                Storage::disk('public')->delete($pengeluaran->bukti_foto);
            }
            $pathFoto = $request->file('bukti_foto')->store('bukti_pengeluaran', 'public');
        }

        $this->pengeluaranService->update($pengeluaran, $request->validated(), $pathFoto);
        return redirect()->route('pengeluaran.index')->with('success', 'Transaksi pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Hanya Owner yang berhak menghapus pengeluaran.');
        }

        if ($pengeluaran->bukti_foto) {
            Storage::disk('public')->delete($pengeluaran->bukti_foto);
        }

        $this->pengeluaranService->hapus($pengeluaran);
        return redirect()->route('pengeluaran.index')->with('success', 'Transaksi pengeluaran berhasil dihapus.');
    }

    public function verifikasi(Pengeluaran $pengeluaran)
    {
        $this->pengeluaranService->verifikasi($pengeluaran);
        return redirect()->back()->with('success', "Transaksi {$pengeluaran->kode_transaksi} berhasil diverifikasi.");
    }
}
