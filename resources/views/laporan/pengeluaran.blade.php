@extends('layouts.app')
@section('title', 'Laporan Pengeluaran')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-file-invoice-dollar me-2 text-danger"></i>Laporan Pengeluaran</h4>
        <p class="text-muted small mb-0">Laporan rekapitulasi biaya operasional dan pengeluaran kas usaha.</p>
    </div>
    <div>
        <a href="{{ route('laporan.pengeluaran.pdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm me-1"><i class="fas fa-file-pdf me-1"></i> Export PDF</a>
        <a href="{{ route('laporan.pengeluaran.excel', request()->query()) }}" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i> Export Excel</a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('laporan.pengeluaran') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Kategori</label>
                <select name="kategori_id" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('laporan.pengeluaran') }}" class="btn btn-light btn-sm border"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card stat-card border-danger mb-0" style="border-left-color: var(--color-danger) !important;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-danger fw-bold d-block mb-1">Total Pengeluaran Periode Ini</small>
                    <h3 class="fw-bold text-danger mb-0 font-monospace">Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</h3>
                </div>
                <i class="fas fa-money-bill-transfer fa-3x text-danger opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Rincian Transaksi Pengeluaran</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Transaksi</th>
                    <th>Kategori Biaya</th>
                    <th>Keterangan</th>
                    <th class="text-end">Nominal (Rp)</th>
                    <th>Operator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d/m/Y') }}</td>
                    <td class="col-kode fw-bold text-danger">{{ $item->kode_transaksi }}</td>
                    <td><span class="badge bg-secondary">{{ $item->kategori->nama_kategori ?? '-' }}</span></td>
                    <td>{{ $item->keterangan }}</td>
                    <td class="col-nominal text-end fw-bold text-danger">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $item->karyawan->name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Data pengeluaran tidak ditemukan pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
