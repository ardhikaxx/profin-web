@extends('layouts.auth')

@section('content')
<div class="auth-card p-4 p-md-5 text-center shadow-lg border-0 rounded-4 bg-white" style="max-width: 480px; margin: auto;">
    <div class="mb-4">
        <img src="{{ asset('favicon.svg') }}" alt="Logo UD Sumber Bawang" style="width: 64px; height: 64px;" class="mb-4">
        <div class="d-flex align-items-center justify-content-center mx-auto mb-3 rounded-circle bg-warning bg-opacity-10 text-warning shadow-sm" style="width: 80px; height: 80px;">
            <i class="fas fa-compass fa-2x"></i>
        </div>
        <span class="badge bg-warning text-dark rounded-pill px-3 py-1 text-uppercase tracking-wider fw-bold mb-2" style="font-size: 11px;">
            Kode Error 404
        </span>
        <h3 class="fw-bold text-dark tracking-tight mb-2">HALAMAN TIDAK DITEMUKAN</h3>
        <p class="text-muted small lh-base mb-4">
            Maaf, alamat link URL yang Anda coba buka tidak tersedia, salah ketik, atau telah dipindahkan dari sistem UD. Sumber Bawang Timur.
        </p>
    </div>

    @php
        $backUrl = auth()->check() ? route('dashboard') : route('login');
        $btnText = auth()->check() 
            ? (auth()->user()->role === 'owner' ? 'Kembali ke Dashboard Executive' : 'Kembali ke Dashboard Lapangan')
            : 'Masuk ke Halaman Login';
    @endphp

    <a href="{{ $backUrl }}" class="btn btn-primary w-100 py-2.5 fs-6 fw-semibold shadow-sm rounded-3 text-white text-decoration-none d-block">
        <i class="fas fa-home me-2"></i> {{ $btnText }}
    </a>
</div>
@endsection
