@extends('layouts.auth')

@section('content')
<div class="auth-card p-4 p-md-5 text-center shadow-lg border-0 rounded-4 bg-white" style="max-width: 480px; margin: auto;">
    <div class="mb-4">
        <img src="{{ asset('favicon.svg') }}" alt="Logo UD Sumber Bawang" style="width: 64px; height: 64px;" class="mb-4">
        <div class="d-flex align-items-center justify-content-center mx-auto mb-3 rounded-circle bg-danger bg-opacity-10 text-danger shadow-sm" style="width: 80px; height: 80px;">
            <i class="fas fa-shield-halved fa-2x"></i>
        </div>
        <span class="badge bg-danger rounded-pill px-3 py-1 text-uppercase tracking-wider fw-bold mb-2" style="font-size: 11px;">
            Kode Error 403
        </span>
        <h3 class="fw-bold text-dark tracking-tight mb-2">AKSES TERBATAS DITOLAK</h3>
        <p class="text-muted small lh-base mb-4">
            Maaf, akun Anda saat ini (@if(auth()->check())<strong class="text-dark">{{ auth()->user()->name }}</strong> — <span class="badge bg-secondary text-uppercase">{{ auth()->user()->role }}</span>@else<strong class="text-dark">Tamu</strong>@endif) tidak memiliki hak otorisasi keamanan untuk membuka area eksekutif ini.
        </p>
        <div class="alert alert-light border small text-start p-3 rounded-3 mb-4 text-muted">
            <i class="fas fa-circle-info text-primary me-2"></i><strong>Tips Keamanan:</strong> Modul Laporan Keuangan, Master Data, dan Verifikasi eksklusif dikendalikan penuh oleh Owner.
        </div>
    </div>

    @php
        $backUrl = auth()->check() ? route('dashboard') : route('login');
        $btnText = auth()->check() 
            ? (auth()->user()->role === 'owner' ? 'Kembali ke Dashboard Executive' : 'Kembali ke Dashboard Lapangan')
            : 'Masuk ke Halaman Login';
    @endphp

    <a href="{{ $backUrl }}" class="btn btn-primary w-100 py-2.5 fs-6 fw-semibold shadow-sm rounded-3 text-white text-decoration-none d-block">
        <i class="fas fa-arrow-left me-2"></i> {{ $btnText }}
    </a>
</div>
@endsection
