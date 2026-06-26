<header class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button type="button" id="sidebar-toggle" class="btn btn-sm btn-outline-secondary d-lg-none">
            <i class="fas fa-bars"></i>
        </button>
        <span class="fw-semibold text-muted d-none d-md-inline small">
            <i class="fas fa-calendar-alt me-2 text-primary"></i> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
        </span>
    </div>

    <div class="d-flex align-items-center gap-3">
        @if(auth()->user()->role === 'owner')
            @php 
                $alertStok = \App\Models\Stok::whereHas('produk', fn($q) => $q->whereColumn('stoks.jumlah_stok', '<=', 'produks.stok_minimum'))->count();
                $alertDraft = \App\Models\Produksi::where('status', 'draft')->count();
                $totalAlert = $alertStok + $alertDraft;
            @endphp
            <div class="dropdown">
                <button class="btn btn-light position-relative rounded-circle p-2 border" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 38px; height: 38px;">
                    <i class="fas fa-bell text-secondary"></i>
                    @if($totalAlert > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                            {{ $totalAlert }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="width: 300px;">
                    <li><h6 class="dropdown-header fw-bold text-primary">Notifikasi Sistem</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    @if($alertStok > 0)
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-start gap-2 rounded" href="{{ route('stok.index') }}">
                                <i class="fas fa-exclamation-triangle text-danger mt-1"></i>
                                <div>
                                    <div class="fw-semibold small">Stok Kritis</div>
                                    <div class="text-muted" style="font-size: 12px;">{{ $alertStok }} produk di bawah stok minimum.</div>
                                </div>
                            </a>
                        </li>
                    @endif
                    @if($alertDraft > 0)
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-start gap-2 rounded" href="{{ route('produksi.index') }}">
                                <i class="fas fa-clock text-warning mt-1"></i>
                                <div>
                                    <div class="fw-semibold small">Produksi Draft</div>
                                    <div class="text-muted" style="font-size: 12px;">{{ $alertDraft }} data produksi belum diverifikasi.</div>
                                </div>
                            </a>
                        </li>
                    @endif
                    @if($totalAlert === 0)
                        <li class="text-center py-3 text-muted small">Tidak ada notifikasi baru.</li>
                    @endif
                </ul>
            </div>
        @endif

        <div class="dropdown">
            <a class="d-flex align-items-center text-decoration-none dropdown-toggle gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle fs-4 text-primary"></i>
                <span class="d-none d-md-block fw-semibold small text-dark">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li>
                    <a class="dropdown-item small py-2" href="{{ route('profil') }}">
                        <i class="fas fa-user-circle me-2 text-secondary"></i> Profil Saya
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item small py-2 text-danger" href="javascript:void(0)" onclick="SwalHelper.confirmLogout('form-logout-navbar')">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                    <form id="form-logout-navbar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
