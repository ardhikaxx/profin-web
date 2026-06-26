@extends('layouts.app')
@section('title', 'Laporan Persediaan Stok')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-warehouse me-2 text-primary"></i>Laporan Persediaan Stok</h4>
        <p class="text-muted small mb-0">Laporan posisi persediaan produk jadi aktual di gudang saat ini.</p>
    </div>
    <div>
        <a href="{{ route('laporan.stok.pdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm me-1"><i class="fas fa-file-pdf me-1"></i> Export PDF</a>
        <a href="{{ route('laporan.stok.excel', request()->query()) }}" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i> Export Excel</a>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Stok Produk Fisik Gudang</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Satuan</th>
                    <th class="text-end">Stok Tersedia</th>
                    <th class="text-end">Batas Minimum</th>
                    <th class="text-center">Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="col-kode fw-bold text-primary">{{ $item->produk->kode_produk ?? '-' }}</td>
                    <td class="fw-semibold">{{ $item->produk->nama_produk ?? '-' }}</td>
                    <td><span class="badge bg-secondary">{{ $item->produk->satuan->nama_satuan ?? '-' }}</span></td>
                    <td class="col-nominal text-end fs-6 fw-bold {{ $item->jumlah_stok <= ($item->produk->stok_minimum ?? 0) ? 'text-danger' : 'text-success' }}">
                        {{ number_format($item->jumlah_stok, 0, ',', '.') }}
                    </td>
                    <td class="col-nominal text-end text-muted">{{ number_format($item->produk->stok_minimum ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($item->jumlah_stok <= ($item->produk->stok_minimum ?? 0))
                            <span class="badge rounded-pill bg-danger"><i class="fas fa-exclamation-triangle me-1"></i> Stok Kritis</span>
                        @else
                            <span class="badge rounded-pill bg-success"><i class="fas fa-check me-1"></i> Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Data persediaan stok kosong.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($data->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $data->links() }}
    </div>
    @endif
</div>
@endsection
