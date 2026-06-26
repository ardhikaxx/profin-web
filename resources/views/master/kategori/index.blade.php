@extends('layouts.app')
@section('title', 'Kategori Pengeluaran')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-tags me-2 text-primary"></i>Kategori Pengeluaran</h4>
        <p class="text-muted small mb-0">Klasifikasi pos biaya operasional usaha (contoh: Bahan Baku, Tenaga Kerja, Listrik).</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
            <i class="fas fa-plus me-1"></i> Tambah Kategori
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Kategori Biaya</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $key => $kat)
                <tr>
                    <td>{{ $kategoris->firstItem() + $key }}</td>
                    <td class="fw-bold text-primary">{{ $kat->nama_kategori }}</td>
                    <td>{{ $kat->deskripsi ?? '-' }}</td>
                    <td>
                        @if($kat->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEditKat{{ $kat->id }}"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusForm('del-kat-{{ $kat->id }}')"><i class="fas fa-trash"></i></button>
                        <form id="del-kat-{{ $kat->id }}" action="{{ route('master.kategori.destroy', $kat->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEditKat{{ $kat->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('master.kategori.update', $kat->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title fs-6 fw-bold">Edit Kategori: {{ $kat->nama_kategori }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_kategori" class="form-control" value="{{ $kat->nama_kategori }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3">{{ $kat->deskripsi }}</textarea>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_act_{{ $kat->id }}" value="1" {{ $kat->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-semibold" for="is_act_{{ $kat->id }}">Kategori Aktif</label>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data master kategori pengeluaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kategoris->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $kategoris->links() }}
    </div>
    @endif
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('master.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6 fw-bold">Tambah Kategori Biaya</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="cth: Bahan Bakar, Transportasi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="penjelasan opsional"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
