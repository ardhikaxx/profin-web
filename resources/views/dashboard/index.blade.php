@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-chart-line me-2 text-primary"></i>Dashboard Executive</h4>
        <p class="text-muted small mb-0">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>. Pantau performa produksi dan keuangan usaha Anda.</p>
    </div>
    <div>
        <a href="{{ route('produksi.create') }}" class="btn btn-primary btn-sm me-2"><i class="fas fa-plus me-1"></i> Input Produksi</a>
        <a href="{{ route('pengeluaran.create') }}" class="btn btn-outline-danger btn-sm"><i class="fas fa-minus me-1"></i> Input Biaya</a>
    </div>
</div>

<!-- Row 1: 4 Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100 mb-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Total Produksi Hari Ini</p>
                        <h3 class="fw-bold mb-0">{{ number_format($totalProduksiHariIni, 0, ',', '.') }} <small class="fs-6 text-muted">unit</small></h3>
                        <small class="text-success"><i class="fas fa-check-circle me-1"></i> Realtime hari ini</small>
                    </div>
                    <div class="stat-icon bg-primary-light text-primary">
                        <i class="fas fa-industry fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card border-info h-100 mb-0" style="border-left-color: var(--color-info) !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Stok Produk Gudang</p>
                        <h3 class="fw-bold mb-0">{{ number_format($jumlahStokProduk, 0, ',', '.') }} <small class="fs-6 text-muted">unit</small></h3>
                        @if($produkStokRendah->count() > 0)
                            <small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> {{ $produkStokRendah->count() }} stok kritis</small>
                        @else
                            <small class="text-success"><i class="fas fa-check me-1"></i> Stok aman</small>
                        @endif
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-boxes-stacked fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card border-danger h-100 mb-0" style="border-left-color: var(--color-danger) !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Pengeluaran Bulan Ini</p>
                        <h3 class="fw-bold mb-0 fs-5">Rp {{ number_format($totalPengeluaranBulan, 0, ',', '.') }}</h3>
                        <small class="text-muted">{{ $jumlahTransaksiPengeluaran }} transaksi</small>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-money-bill-wave fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card {{ $labaSementara >= 0 ? 'border-success' : 'border-danger' }} h-100 mb-0" style="border-left-color: {{ $labaSementara >= 0 ? 'var(--color-success)' : 'var(--color-danger)' }} !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Estimasi Laba Sementara</p>
                        <h3 class="fw-bold mb-0 fs-5 {{ $labaSementara >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($labaSementara, 0, ',', '.') }}
                        </h3>
                        <small class="text-muted">Pendapatan - Pengeluaran</small>
                    </div>
                    <div class="stat-icon {{ $labaSementara >= 0 ? 'bg-success text-success' : 'bg-danger text-danger' }} bg-opacity-10">
                        <i class="fas fa-chart-pie fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Charts -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card h-100 mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-chart-area me-2 text-primary"></i>Tren Produksi Harian (30 Hari Terakhir)</span>
            </div>
            <div class="card-body p-4">
                <canvas id="chartProduksi" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100 mb-0">
            <div class="card-header">
                <span><i class="fas fa-chart-pie me-2 text-danger"></i>Biaya per Kategori Bulan Ini</span>
            </div>
            <div class="card-body p-4 d-flex align-items-center justify-content-center">
                <canvas id="chartPengeluaran" style="max-height: 280px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Tables & Alerts -->
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-industry me-2 text-primary"></i>5 Aktivitas Produksi Terbaru</span>
                <a href="{{ route('produksi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Bersih</th>
                            <th>Karyawan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksiTerbaru as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_produksi)->format('d/m/Y') }}</td>
                            <td class="col-kode text-primary fw-bold">{{ $item->kode_produksi }}</td>
                            <td class="fw-semibold">{{ $item->produk->nama_produk ?? '-' }}</td>
                            <td class="col-nominal fw-bold text-success">{{ number_format($item->jumlah_bersih, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? 'unit' }}</td>
                            <td>{{ $item->karyawan->name ?? '-' }}</td>
                            <td>
                                @if($item->status === 'draft')
                                    <span class="badge rounded-pill bg-warning text-dark"><i class="fas fa-clock me-1"></i> Draft</span>
                                @else
                                    <span class="badge rounded-pill bg-success"><i class="fas fa-check-circle me-1"></i> Terverifikasi</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada aktivitas produksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Stok Kritis</span>
                <a href="{{ route('stok.index') }}" class="btn btn-sm btn-outline-secondary">Kelola Stok</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($produkStokRendah as $stok)
                        <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $stok->produk->nama_produk ?? '-' }}</h6>
                                <small class="text-danger">Min: {{ number_format($stok->produk->stok_minimum, 0, ',', '.') }} unit</small>
                            </div>
                            <span class="badge bg-danger rounded-pill fs-6 px-3 py-2">
                                {{ number_format($stok->jumlah_stok, 0, ',', '.') }} unit
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center py-5 text-muted">
                            <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                            <p class="mb-0 small">Semua stok produk berada di atas batas minimum.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts-cdn')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Line Chart Produksi
    const ctxProd = document.getElementById('chartProduksi');
    if (ctxProd) {
        new Chart(ctxProd, {
            type: 'line',
            data: {
                labels: {!! json_encode($grafikProduksi->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
                datasets: [{
                    label: 'Produksi Bersih (Unit)',
                    data: {!! json_encode($grafikProduksi->pluck('total')) !!},
                    borderColor: '#1B6B3A',
                    backgroundColor: 'rgba(27, 107, 58, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#1B6B3A'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Doughnut Chart Pengeluaran
    const ctxExp = document.getElementById('chartPengeluaran');
    if (ctxExp) {
        new Chart(ctxExp, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($grafikPengeluaran->map(fn($g) => $g->kategori->nama_kategori ?? 'Lainnya')) !!},
                datasets: [{
                    data: {!! json_encode($grafikPengeluaran->pluck('total')) !!},
                    backgroundColor: ['#1B6B3A', '#F59E0B', '#DC3545', '#0DCAF0', '#6C757D', '#6610f2'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                }
            }
        });
    }
});
</script>
@endpush
