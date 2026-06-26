@extends('layouts.app')
@section('title', 'Persediaan Stok Gudang')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-boxes-stacked me-2 text-primary"></i>Persediaan Stok Gudang</h4>
        <p class="text-muted small mb-0">Pantau ketersediaan stok riil, lakukan penyesuaian/koreksi aktual, atau catat barang keluar gudang.</p>
    </div>
    <div>
        <a href="{{ route('stok.histori') }}" class="btn btn-outline-secondary btn-sm me-2"><i class="fas fa-history me-1"></i> Histori Stok</a>
        <button type="button" class="btn btn-primary btn-sm fw-semibold me-1 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKurangiStok">
            <i class="fas fa-minus-circle me-1"></i> Catat Barang Keluar
        </button>
        <button type="button" class="btn btn-primary btn-sm fw-semibold text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKoreksiStok">
            <i class="fas fa-scale-balanced me-1"></i> Koreksi Opname
        </button>
    </div>
</div>

<!-- 3 Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card h-100 mb-0">
            <div class="card-body p-3">
                <p class="text-muted small mb-1">Total Jenis Produk</p>
                <h3 class="fw-bold mb-0">{{ number_format($totalProduk, 0, ',', '.') }} <small class="fs-6 text-muted">Varian</small></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-success h-100 mb-0" style="border-left-color: var(--color-success) !important;">
            <div class="card-body p-3">
                <p class="text-muted small mb-1">Stok Kondisi Aman</p>
                <h3 class="fw-bold mb-0 text-success">{{ number_format($stokNormal, 0, ',', '.') }} <small class="fs-6 text-muted">Varian</small></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-danger h-100 mb-0" style="border-left-color: var(--color-danger) !important;">
            <div class="card-body p-3">
                <p class="text-muted small mb-1">Stok Kritis (< Minimum)</p>
                <h3 class="fw-bold mb-0 text-danger">{{ number_format($stokKritis, 0, ',', '.') }} <small class="fs-6 text-muted">Varian</small></h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Tabel Ketersediaan Stok Fisik Gudang</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Satuan</th>
                    <th>Stok Tersedia</th>
                    <th>Stok Minimum</th>
                    <th>Status Gudang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stoks as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="col-kode fw-bold text-primary">{{ $item->produk->kode_produk ?? '-' }}</td>
                    <td class="fw-semibold">{{ $item->produk->nama_produk ?? '-' }}</td>
                    <td><span class="badge bg-secondary">{{ $item->produk->satuan->nama_satuan ?? '-' }}</span></td>
                    <td class="col-nominal fs-6 fw-bold {{ $item->jumlah_stok <= ($item->produk->stok_minimum ?? 0) ? 'text-danger' : 'text-success' }}">
                        {{ number_format($item->jumlah_stok, 0, ',', '.') }}
                    </td>
                    <td class="col-nominal text-muted">{{ number_format($item->produk->stok_minimum ?? 0, 0, ',', '.') }}</td>
                    <td>
                        @if($item->jumlah_stok <= ($item->produk->stok_minimum ?? 0))
                            <span class="badge rounded-pill bg-danger"><i class="fas fa-exclamation-triangle me-1"></i> Kritis</span>
                        @else
                            <span class="badge rounded-pill bg-success"><i class="fas fa-check me-1"></i> Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Data stok produk kosong.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stoks->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $stoks->links() }}
    </div>
    @endif
</div>

<!-- Modal Kurangi Stok (Barang Keluar) -->
<div class="modal fade" id="modalKurangiStok" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('stok.kurangi') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fas fa-minus-circle me-2"></i>Catat Distribusi/Penjualan (Barang Keluar)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="produk_id" class="form-select" required>
                            <option value="">-- pilih produk --</option>
                            @foreach($allStoks as $s)
                                <option value="{{ $s->produk_id }}">{{ $s->produk->nama_produk ?? '-' }} (Stok: {{ $s->jumlah_stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jumlah Keluar <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control fw-bold" min="1" placeholder="cth: 50" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keterangan / Tujuan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="cth: Dikirim ke Pasar Induk Surabaya..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold text-white">Simpan Pengurangan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Koreksi Stok (Stock Opname) -->
<div class="modal fade" id="modalKoreksiStok" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('stok.koreksi') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fas fa-scale-balanced me-2"></i>Koreksi Stock Opname (Penyesuaian Manual)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="produk_id" class="form-select" required>
                            <option value="">-- pilih produk --</option>
                            @foreach($allStoks as $s)
                                <option value="{{ $s->produk_id }}">{{ $s->produk->nama_produk ?? '-' }} (Sistem: {{ $s->jumlah_stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Stok Aktual Hasil Opname Fisik <span class="text-danger">*</span></label>
                        <input type="number" name="stok_aktual" class="form-control fw-bold text-primary" min="0" placeholder="cth: 120" required>
                        <div class="form-text small">Sistem akan menyesuaikan jumlah stok sesuai angka riil ini.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alasan Koreksi <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="cth: Penyusutan berat gudang pasca panen..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold text-white">Update Stok Aktual</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
