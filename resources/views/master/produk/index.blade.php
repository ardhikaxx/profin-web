@extends('layouts.app')
@section('title', 'Kelola Produk')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-box me-2 text-primary"></i>Master Produk</h4>
        <p class="text-muted small mb-0">Kelola daftar produk jadi beserta parameter harga estimasi dan stok minimum.</p>
    </div>
    <div>
        <a href="{{ route('master.produk.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Tambah Produk</a>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Produk Aktif & Gudang</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>Satuan</th>
                    <th>Harga Estimasi</th>
                    <th>Stok Minimum</th>
                    <th>Status</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks as $item)
                <tr>
                    <td class="col-kode fw-bold text-primary">{{ $item->kode_produk }}</td>
                    <td class="fw-semibold">{{ $item->nama_produk }}</td>
                    <td><span class="badge bg-secondary">{{ $item->satuan->nama_satuan ?? '-' }}</span></td>
                    <td class="col-nominal text-success fw-bold">Rp {{ number_format($item->harga_estimasi, 0, ',', '.') }}</td>
                    <td class="col-nominal">{{ number_format($item->stok_minimum, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? 'unit' }}</td>
                    <td>
                        @if($item->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('master.produk.edit', $item->id) }}" class="btn btn-sm btn-outline-warning me-1"><i class="fas fa-edit"></i></a>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusForm('del-prod-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                        <form id="del-prod-{{ $item->id }}" action="{{ route('master.produk.destroy', $item->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data master produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produks->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $produks->links() }}
    </div>
    @endif
</div>
@endsection
