@extends('layouts.app')
@section('title', 'Laporan Produksi')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-clipboard-list me-2 text-primary"></i>Laporan Produksi</h4>
        <p class="text-muted small mb-0">Laporan eksekutif rekapitulasi hasil produksi bersih dan barang gagal.</p>
    </div>
    <div>
        <a href="{{ route('laporan.produksi.pdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm me-1"><i class="fas fa-file-pdf me-1"></i> Export PDF</a>
        <a href="{{ route('laporan.produksi.excel', request()->query()) }}" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i> Export Excel</a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('laporan.produksi') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Produk</label>
                <select name="produk_id" class="form-select form-select-sm">
                    <option value="">Semua Produk</option>
                    @foreach($produks as $p)
                        <option value="{{ $p->id }}" {{ request('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_produk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('laporan.produksi') }}" class="btn btn-light btn-sm border"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card border-info h-100 mb-0" style="border-left-color: var(--color-info) !important;">
            <div class="card-body p-3 text-center">
                <small class="text-muted d-block">Total Produksi Mentah</small>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($summary['total_produksi'], 0, ',', '.') }} unit</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-danger h-100 mb-0" style="border-left-color: var(--color-danger) !important;">
            <div class="card-body p-3 text-center">
                <small class="text-danger d-block">Total Gagal/Rusak</small>
                <h3 class="fw-bold text-danger mb-0">{{ number_format($summary['total_gagal'], 0, ',', '.') }} unit</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-success h-100 mb-0" style="border-left-color: var(--color-success) !important;">
            <div class="card-body p-3 text-center">
                <small class="text-success fw-bold d-block">Total Bersih (Stok Masuk)</small>
                <h3 class="fw-bold text-success mb-0">{{ number_format($summary['total_bersih'], 0, ',', '.') }} unit</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Rincian Data Produksi</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Produk</th>
                    <th class="text-end">Total Produksi</th>
                    <th class="text-end">Gagal</th>
                    <th class="text-end">Bersih</th>
                    <th>Operator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_produksi)->format('d/m/Y') }}</td>
                    <td class="col-kode fw-bold text-primary">{{ $item->kode_produksi }}</td>
                    <td class="fw-semibold">{{ $item->produk->nama_produk ?? '-' }}</td>
                    <td class="col-nominal text-end">{{ number_format($item->jumlah_produksi, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? '' }}</td>
                    <td class="col-nominal text-end text-danger">{{ number_format($item->jumlah_gagal, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? '' }}</td>
                    <td class="col-nominal text-end fw-bold text-success">{{ number_format($item->jumlah_bersih, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? '' }}</td>
                    <td>{{ $item->karyawan->name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Data laporan produksi tidak ditemukan pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
