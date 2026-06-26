<aside class="sidebar">
    <div class="brand">
        <div class="bg-primary rounded p-2 text-white d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i class="fas fa-leaf fs-5"></i>
        </div>
        <div>
            <h1 class="brand-name">UD. Sumber Bawang Timur</h1>
            <span class="brand-sub">Sistem Terintegrasi</span>
        </div>
    </div>

    @auth
    <div class="user-info">
        <i class="fas fa-user-circle fs-2 text-white"></i>
        <div class="overflow-hidden">
            <p class="user-name text-truncate mb-1">{{ auth()->user()->name }}</p>
            <span class="user-role role-{{ auth()->user()->role }}">
                {{ auth()->user()->role }}
            </span>
        </div>
    </div>
    @endauth

    <nav class="mt-2 mb-4">
        <div class="menu-label">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ route('produksi.index') }}" class="nav-link {{ request()->routeIs('produksi.*') ? 'active' : '' }}">
            <i class="fas fa-industry"></i> Produksi
            @if(auth()->user()->role === 'owner')
                @php $pendingPrd = \App\Models\Produksi::where('status', 'draft')->count(); @endphp
                @if($pendingPrd > 0)
                    <span class="badge badge-count bg-warning text-dark">{{ $pendingPrd }}</span>
                @endif
            @endif
        </a>
        <a href="{{ route('pengeluaran.index') }}" class="nav-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i> Pengeluaran
        </a>

        @if(auth()->user()->role === 'owner')
        <a href="{{ route('stok.index') }}" class="nav-link {{ request()->routeIs('stok.*') ? 'active' : '' }}">
            <i class="fas fa-boxes-stacked"></i> Persediaan Stok
            @php 
                $kritis = \App\Models\Stok::whereHas('produk', fn($q) => $q->whereColumn('stoks.jumlah_stok', '<=', 'produks.stok_minimum'))->count(); 
            @endphp
            @if($kritis > 0)
                <span class="badge badge-count">{{ $kritis }}</span>
            @endif
        </a>

        <div class="menu-label mt-3">Laporan</div>
        <a href="{{ route('laporan.produksi') }}" class="nav-link {{ request()->routeIs('laporan.produksi') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> Lap. Produksi
        </a>
        <a href="{{ route('laporan.stok') }}" class="nav-link {{ request()->routeIs('laporan.stok') ? 'active' : '' }}">
            <i class="fas fa-warehouse"></i> Lap. Stok
        </a>
        <a href="{{ route('laporan.pengeluaran') }}" class="nav-link {{ request()->routeIs('laporan.pengeluaran') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar"></i> Lap. Pengeluaran
        </a>
        <a href="{{ route('laporan.laba-rugi') }}" class="nav-link {{ request()->routeIs('laporan.laba-rugi') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Lap. Laba Rugi
        </a>

        <div class="menu-label mt-3">Master Data</div>
        <a href="{{ route('master.produk.index') }}" class="nav-link {{ request()->routeIs('master.produk.*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> Produk
        </a>
        <a href="{{ route('master.satuan.index') }}" class="nav-link {{ request()->routeIs('master.satuan.*') ? 'active' : '' }}">
            <i class="fas fa-ruler"></i> Satuan
        </a>
        <a href="{{ route('master.kategori.index') }}" class="nav-link {{ request()->routeIs('master.kategori.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Kategori Pengeluaran
        </a>
        <a href="{{ route('master.user.index') }}" class="nav-link {{ request()->routeIs('master.user.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Pengguna
        </a>

        <div class="menu-label mt-3">Sistem</div>
        <a href="{{ route('audit-log.index') }}" class="nav-link {{ request()->routeIs('audit-log.*') ? 'active' : '' }}">
            <i class="fas fa-shield-halved"></i> Audit Log
        </a>
        @endif

        <div class="menu-label mt-4">Akun</div>
        <a href="{{ route('profil') }}" class="nav-link {{ request()->routeIs('profil') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Profil Saya
        </a>
        <a href="javascript:void(0)" onclick="SwalHelper.confirmLogout('form-logout-sidebar')" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
        <form id="form-logout-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </nav>
</aside>
