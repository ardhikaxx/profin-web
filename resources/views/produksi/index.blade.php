@extends('layouts.app')
@section('title', 'Daftar Input Produksi')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-industry me-2 text-primary"></i>Aktivitas Produksi</h4>
        <p class="text-muted small mb-0">Daftar pencatatan hasil produksi harian beserta kalkulasi jumlah bersih dan status verifikasi.</p>
    </div>
    <div>
        <a href="{{ route('produksi.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Input Produksi Baru</a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('produksi.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Filter Produk</label>
                <select name="produk_id" class="form-select form-select-sm">
                    <option value="">Semua Produk</option>
                    @foreach($produks as $p)
                        <option value="{{ $p->id }}" {{ request('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_produk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('produksi.index') }}" class="btn btn-light btn-sm border"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Catatan Produksi</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Produk</th>
                    <th>Total Produksi</th>
                    <th>Gagal</th>
                    <th>Bersih (Masuk Gudang)</th>
                    <th>Operator</th>
                    <th>Status</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produksis as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_produksi)->format('d/m/Y') }}</td>
                    <td class="col-kode fw-bold text-primary">{{ $item->kode_produksi }}</td>
                    <td class="fw-semibold">{{ $item->produk->nama_produk ?? '-' }}</td>
                    <td class="col-nominal">{{ number_format($item->jumlah_produksi, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? 'unit' }}</td>
                    <td class="col-nominal text-danger">{{ number_format($item->jumlah_gagal, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? 'unit' }}</td>
                    <td class="col-nominal fw-bold text-success">{{ number_format($item->jumlah_bersih, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? 'unit' }}</td>
                    <td>{{ $item->karyawan->name ?? '-' }}</td>
                    <td>
                        @if($item->status === 'draft')
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Draft</span>
                        @else
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Terverifikasi</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('produksi.show', $item->id) }}" class="btn btn-sm btn-outline-info me-1" title="Detail"><i class="fas fa-eye"></i></a>
                        @if($item->status === 'draft' || auth()->user()->role === 'owner')
                            <a href="{{ route('produksi.edit', $item->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit"><i class="fas fa-edit"></i></a>
                        @endif
                        @if(auth()->user()->role === 'owner')
                            @if($item->status === 'draft')
                            <button type="button" class="btn btn-sm btn-success me-1" onclick="verifikasiForm('ver-prd-{{ $item->id }}')" title="Verifikasi"><i class="fas fa-check"></i></button>
                            <form id="ver-prd-{{ $item->id }}" action="{{ route('produksi.verifikasi', $item->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('PATCH')
                            </form>
                            @endif
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusForm('del-prd-{{ $item->id }}')" title="Hapus"><i class="fas fa-trash"></i></button>
                            <form id="del-prd-{{ $item->id }}" action="{{ route('produksi.destroy', $item->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">Belum ada data aktivitas produksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produksis->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $produksis->links() }}
    </div>
    @endif
</div>
@endsection
