@extends('layouts.app')
@section('title', 'Detail Pengeluaran')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-file-invoice me-2 text-danger"></i>Detail Pengeluaran: {{ $pengeluaran->kode_transaksi }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('pengeluaran.index') }}">Pengeluaran</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('pengeluaran.index') }}" class="btn btn-light border btn-sm"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
    </div>
</div>

<div class="card max-w-2xl">
    <div class="card-header d-flex justify-content-between align-items-center bg-light">
        <span class="font-monospace fw-bold fs-5 text-danger">{{ $pengeluaran->kode_transaksi }}</span>
        @if($pengeluaran->status === 'draft')
            <span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="fas fa-clock me-1"></i> DRAFT</span>
        @else
            <span class="badge bg-success px-3 py-2 fs-6"><i class="fas fa-check-circle me-1"></i> TERVERIFIKASI</span>
        @endif
    </div>
    <div class="card-body p-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="text-muted small mb-1">Tanggal Transaksi</p>
                <h6 class="fw-bold text-dark">{{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->translatedFormat('l, d F Y') }}</h6>
            </div>
            <div class="col-md-6">
                <p class="text-muted small mb-1">Operator Karyawan</p>
                <h6 class="fw-bold text-dark"><i class="fas fa-user-circle me-1 text-secondary"></i> {{ $pengeluaran->karyawan->name ?? '-' }}</h6>
            </div>
        </div>

        <hr>

        <div class="mb-4">
            <p class="text-muted small mb-1">Kategori Pos Biaya</p>
            <h5 class="fw-bold text-dark mb-0"><span class="badge bg-secondary fs-6">{{ $pengeluaran->kategori->nama_kategori ?? '-' }}</span></h5>
        </div>

        <div class="p-4 bg-danger bg-opacity-10 rounded border border-danger mb-4 text-center">
            <small class="text-danger fw-semibold d-block mb-1">Total Nominal Pengeluaran</small>
            <h2 class="fw-bold text-danger mb-0 font-monospace">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</h2>
        </div>

        <div class="mb-4">
            <p class="text-muted small mb-1">Keterangan / Keperluan</p>
            <div class="p-3 bg-light rounded border small">
                {{ $pengeluaran->keterangan }}
            </div>
        </div>

        <div class="mb-4">
            <p class="text-muted small mb-2">Bukti Foto Nota Transaksi</p>
            @if($pengeluaran->bukti_foto)
                <div class="text-center bg-light p-3 rounded border">
                    <img src="{{ asset('storage/'.$pengeluaran->bukti_foto) }}" alt="Bukti Nota" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                </div>
            @else
                <div class="p-4 text-center bg-light rounded text-muted small border">
                    <i class="fas fa-image-slash fa-2x mb-2"></i>
                    <p class="mb-0">Tidak ada lampiran foto bukti nota.</p>
                </div>
            @endif
        </div>

        @if($pengeluaran->status === 'terverifikasi')
        <div class="alert alert-success d-flex align-items-center gap-3 mb-0">
            <i class="fas fa-shield-check fa-2x"></i>
            <div>
                <div class="fw-bold">Biaya Terverifikasi Sah</div>
                <div class="small">Diverifikasi oleh <strong>{{ $pengeluaran->verifier->name ?? 'Owner' }}</strong> pada {{ \Carbon\Carbon::parse($pengeluaran->verified_at)->format('d/m/Y H:i') }}. Masuk laporan laba rugi.</div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
