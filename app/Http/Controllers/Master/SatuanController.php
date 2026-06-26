<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function __construct(protected AuditLogService $auditLog) {}

    public function index()
    {
        $satuans = Satuan::orderBy('nama_satuan')->paginate(10);
        return view('master.satuan.index', compact('satuans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuans,nama_satuan',
            'keterangan'  => 'nullable|string|max:100',
        ], [
            'nama_satuan.required' => 'Nama satuan wajib diisi.',
            'nama_satuan.unique'   => 'Satuan ini sudah ada.',
        ]);

        $satuan = Satuan::create($data);
        $this->auditLog->catat('Master', 'create', "Menambahkan satuan baru: {$satuan->nama_satuan}");

        return redirect()->route('master.satuan.index')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function update(Request $request, Satuan $satuan)
    {
        $data = $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuans,nama_satuan,' . $satuan->id,
            'keterangan'  => 'nullable|string|max:100',
        ]);

        $lama = $satuan->toArray();
        $satuan->update($data);
        $this->auditLog->catat('Master', 'update', "Memperbarui satuan: {$satuan->nama_satuan}", $lama, $satuan->fresh()->toArray());

        return redirect()->route('master.satuan.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Satuan $satuan)
    {
        $lama = $satuan->toArray();
        $nama = $satuan->nama_satuan;
        $satuan->delete();
        $this->auditLog->catat('Master', 'delete', "Menghapus satuan: {$nama}", $lama, null);

        return redirect()->route('master.satuan.index')->with('success', 'Satuan berhasil dihapus.');
    }
}
