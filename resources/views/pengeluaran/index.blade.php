@extends('layouts.app')
@section('title', 'Daftar Pengeluaran')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-money-bill-wave me-2 text-danger"></i>Aktivitas Pengeluaran</h4>
        <p class="text-muted small mb-0">Pencatatan biaya operasional harian beserta upload bukti nota transaksi.</p>
    </div>
    <div>
        <a href="{{ route('pengeluaran.create') }}" class="btn btn-danger btn-sm"><i class="fas fa-plus me-1"></i> Input Pengeluaran</a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('pengeluaran.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Kategori Biaya</label>
                <select name="kategori_id" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('pengeluaran.index') }}" class="btn btn-light btn-sm border"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Transaksi Biaya</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Transaksi</th>
                    <th>Kategori Biaya</th>
                    <th>Keterangan</th>
                    <th>Nominal (Rp)</th>
                    <th>Bukti</th>
                    <th>Operator</th>
                    <th>Status</th>
                    <th class="text-center text-nowrap" style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengeluarans as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d/m/Y') }}</td>
                    <td class="col-kode fw-bold text-danger">{{ $item->kode_transaksi }}</td>
                    <td><span class="badge bg-secondary">{{ $item->kategori->nama_kategori ?? '-' }}</span></td>
                    <td class="small text-truncate" style="max-width: 200px;">{{ $item->keterangan }}</td>
                    <td class="col-nominal fw-bold text-danger">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td>
                        @if($item->bukti_foto)
                            <a href="{{ asset('storage/'.$item->bukti_foto) }}" target="_blank" class="badge bg-info text-dark text-decoration-none"><i class="fas fa-image me-1"></i> Lihat</a>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td>{{ $item->karyawan->name ?? '-' }}</td>
                    <td>
                        @if($item->status === 'draft')
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Draft</span>
                        @else
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Terverifikasi</span>
                        @endif
                    </td>
                    <td class="text-center text-nowrap">
                        <div class="btn-group btn-group-sm shadow-sm" role="group">
                            <a href="{{ route('pengeluaran.show', $item->id) }}" class="btn btn-outline-info" title="Detail"><i class="fas fa-eye"></i></a>
                            @if($item->status === 'draft' || auth()->user()->role === 'owner')
                                <a href="{{ route('pengeluaran.edit', $item->id) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif
                            @if(auth()->user()->role === 'owner')
                                @if($item->status === 'draft')
                                    <button type="button" class="btn btn-success" onclick="verifikasiForm('ver-exp-{{ $item->id }}')" title="Verifikasi"><i class="fas fa-check"></i></button>
                                @endif
                                <button type="button" class="btn btn-outline-danger" onclick="hapusForm('del-exp-{{ $item->id }}')" title="Hapus"><i class="fas fa-trash"></i></button>
                            @endif
                        </div>
                        @if(auth()->user()->role === 'owner')
                            @if($item->status === 'draft')
                            <form id="ver-exp-{{ $item->id }}" action="{{ route('pengeluaran.verifikasi', $item->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('PATCH')
                            </form>
                            @endif
                            <form id="del-exp-{{ $item->id }}" action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">Belum ada data aktivitas pengeluaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengeluarans->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $pengeluarans->links() }}
    </div>
    @endif
</div>
@endsection
