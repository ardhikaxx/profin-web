<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengeluaran;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class KategoriPengeluaranController extends Controller
{
    public function __construct(protected AuditLogService $auditLog) {}

    public function index()
    {
        $kategoris = KategoriPengeluaran::orderBy('nama_kategori')->paginate(10);
        return view('master.kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_pengeluarans,nama_kategori',
            'deskripsi'     => 'nullable|string',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Kategori pengeluaran ini sudah ada.',
        ]);

        $data['is_active'] = true;
        $kat = KategoriPengeluaran::create($data);
        $this->auditLog->catat('Master', 'create', "Menambahkan kategori biaya: {$kat->nama_kategori}");

        return redirect()->route('master.kategori.index')->with('success', 'Kategori pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriPengeluaran $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_pengeluarans,nama_kategori,' . $kategori->id,
            'deskripsi'     => 'nullable|string',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        $lama = $kategori->toArray();
        $kategori->update($data);
        $this->auditLog->catat('Master', 'update', "Memperbarui kategori biaya: {$kategori->nama_kategori}", $lama, $kategori->fresh()->toArray());

        return redirect()->route('master.kategori.index')->with('success', 'Kategori pengeluaran berhasil diperbarui.');
    }

    public function destroy(KategoriPengeluaran $kategori)
    {
        $lama = $kategori->toArray();
        $nama = $kategori->nama_kategori;
        $kategori->delete();
        $this->auditLog->catat('Master', 'delete', "Menghapus kategori biaya: {$nama}", $lama, null);

        return redirect()->route('master.kategori.index')->with('success', 'Kategori pengeluaran berhasil dihapus.');
    }
}
