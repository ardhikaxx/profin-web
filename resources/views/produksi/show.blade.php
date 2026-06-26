@extends('layouts.app')
@section('title', 'Detail Produksi')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-file-alt me-2 text-primary"></i>Detail Produksi: {{ $produksi->kode_produksi }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('produksi.index') }}">Produksi</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('produksi.index') }}" class="btn btn-light border btn-sm"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
    </div>
</div>

<div class="card max-w-2xl">
    <div class="card-header d-flex justify-content-between align-items-center bg-light">
        <span class="font-monospace fw-bold fs-5 text-primary">{{ $produksi->kode_produksi }}</span>
        @if($produksi->status === 'draft')
            <span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="fas fa-clock me-1"></i> DRAFT</span>
        @else
            <span class="badge bg-success px-3 py-2 fs-6"><i class="fas fa-check-circle me-1"></i> TERVERIFIKASI</span>
        @endif
    </div>
    <div class="card-body p-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="text-muted small mb-1">Tanggal & Shift</p>
                <h6 class="fw-bold text-dark">{{ \Carbon\Carbon::parse($produksi->tanggal_produksi)->translatedFormat('l, d F Y') }}</h6>
            </div>
            <div class="col-md-6">
                <p class="text-muted small mb-1">Operator Karyawan</p>
                <h6 class="fw-bold text-dark"><i class="fas fa-user-circle me-1 text-secondary"></i> {{ $produksi->karyawan->name ?? '-' }}</h6>
            </div>
        </div>

        <hr>

        <div class="mb-4">
            <p class="text-muted small mb-1">Produk Jadi</p>
            <h4 class="fw-bold text-primary mb-0">{{ $produksi->produk->nama_produk ?? '-' }}</h4>
            <span class="badge bg-secondary">{{ $produksi->satuan->nama_satuan ?? '-' }}</span>
        </div>

        <div class="row g-3 p-3 bg-light rounded border mb-4 text-center">
            <div class="col-4 border-end">
                <small class="text-muted d-block">Total Produksi</small>
                <span class="fs-5 fw-bold text-dark">{{ number_format($produksi->jumlah_produksi, 0, ',', '.') }}</span>
                <small class="text-muted">{{ $produksi->satuan->nama_satuan ?? '' }}</small>
            </div>
            <div class="col-4 border-end">
                <small class="text-danger d-block">Gagal / Rusak</small>
                <span class="fs-5 fw-bold text-danger">{{ number_format($produksi->jumlah_gagal, 0, ',', '.') }}</span>
                <small class="text-muted">{{ $produksi->satuan->nama_satuan ?? '' }}</small>
            </div>
            <div class="col-4">
                <small class="text-success fw-semibold d-block">Bersih Masuk Gudang</small>
                <span class="fs-4 fw-bold text-success">{{ number_format($produksi->jumlah_bersih, 0, ',', '.') }}</span>
                <small class="text-muted">{{ $produksi->satuan->nama_satuan ?? '' }}</small>
            </div>
        </div>

        <div class="mb-4">
            <p class="text-muted small mb-1">Catatan</p>
            <div class="p-3 bg-light rounded border small">
                {{ $produksi->catatan ?? 'Tidak ada catatan.' }}
            </div>
        </div>

        @if($produksi->status === 'terverifikasi')
        <div class="alert alert-success d-flex align-items-center gap-3 mb-0">
            <i class="fas fa-shield-check fa-2x"></i>
            <div>
                <div class="fw-bold">Data Terverifikasi Resmi</div>
                <div class="small">Diverifikasi oleh <strong>{{ $produksi->verifier->name ?? 'Owner' }}</strong> pada {{ \Carbon\Carbon::parse($produksi->verified_at)->format('d/m/Y H:i') }}. Stok gudang telah diupdate permanen.</div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
