@extends('layouts.auth')

@section('content')
<div class="auth-card p-4 p-md-5">
    <div class="text-center mb-4">
        <div class="bg-primary-light text-primary rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-3" style="width: 64px; height: 64px;">
            <i class="fas fa-industry fa-2x"></i>
        </div>
        <h4 class="fw-bold mb-1" style="color: var(--color-primary-dark);">UD. SUMBER BAWANG TIMUR</h4>
        <p class="text-muted small mb-0">Sistem Produksi & Keuangan</p>
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

        <button type="submit" class="btn btn-primary w-100 py-2 fs-6 fw-bold">
            <i class="fas fa-sign-in-alt me-2"></i> MASUK KE SISTEM
        </button>
    </form>

    <div class="text-center mt-4 pt-3 border-top">
        <small class="text-muted" style="font-size: 12px;">&copy; {{ date('Y') }} UD. Sumber Bawang Timur</small>
    </div>
</div>
@endsection
