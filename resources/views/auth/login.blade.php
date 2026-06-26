@extends('layouts.auth')

@section('content')
<div class="auth-card p-4 p-md-5 shadow-lg border-0 rounded-4 bg-white">
    <div class="text-center mb-4 pb-1">
        <img src="{{ asset('favicon.svg') }}" alt="Logo UD Sumber Bawang" style="width: 72px; height: 72px;" class="mb-3">
        <h4 class="fw-bold mb-1 text-dark tracking-tight" style="font-size: 18px; letter-spacing: -0.3px;">UD. SUMBER BAWANG TIMUR</h4>
        <p class="text-muted small mb-0">Sistem Informasi Produksi & Keuangan</p>
    </div>

    <form method="POST" action="{{ route('login') }}" id="form-login">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                <input type="text" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="masukkan email..." autofocus>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                <input type="password" name="password" id="login-password" class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" placeholder="masukkan password...">
                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#login-password"><i class="fas fa-eye"></i></button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label small text-secondary" for="remember">
                    Ingat Saya
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 fs-6 fw-bold shadow-sm rounded-3 mt-2">
            MASUK KE SISTEM <i class="fas fa-arrow-right ms-2 small"></i>
        </button>
    </form>

    <div class="text-center mt-4 pt-3 border-top">
        <small class="text-muted" style="font-size: 11.5px;">&copy; {{ date('Y') }} UD. Sumber Bawang Timur. Hak Cipta Dilindungi.</small>
    </div>
</div>
@endsection
