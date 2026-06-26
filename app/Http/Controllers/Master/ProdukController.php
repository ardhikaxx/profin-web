<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Stok;
use App\Http\Requests\ProdukRequest;
use App\Services\AuditLogService;

class ProdukController extends Controller
{
    public function __construct(protected AuditLogService $auditLog) {}

    public function index()
    {
        $produks = Produk::with('satuan')->orderBy('kode_produk')->paginate(10);
        return view('master.produk.index', compact('produks'));
    }

    public function create()
    {
        $satuans = Satuan::orderBy('nama_satuan')->get();
        return view('master.produk.create', compact('satuans'));
    }

    public function store(ProdukRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        
        $produk = Produk::create($data);
        Stok::create(['produk_id' => $produk->id, 'jumlah_stok' => 0]);

        $this->auditLog->catat('Master', 'create', "Menambahkan produk baru: {$produk->nama_produk}", null, $produk->toArray());

        return redirect()->route('master.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        $satuans = Satuan::orderBy('nama_satuan')->get();
        return view('master.produk.edit', compact('produk', 'satuans'));
    }

    public function update(ProdukRequest $request, Produk $produk)
    {
        $lama = $produk->toArray();
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $produk->update($data);
        $this->auditLog->catat('Master', 'update', "Memperbarui produk: {$produk->nama_produk}", $lama, $produk->fresh()->toArray());

        return redirect()->route('master.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $lama = $produk->toArray();
        $nama = $produk->nama_produk;
        $produk->delete();

        $this->auditLog->catat('Master', 'delete', "Menghapus produk: {$nama}", $lama, null);

        return redirect()->route('master.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
