@extends('layouts.app')
@section('title', 'Master Satuan')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-ruler me-2 text-primary"></i>Master Satuan Ukur</h4>
        <p class="text-muted small mb-0">Kelola satuan kemasan/berat yang digunakan dalam transaksi produk dan stok.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSatuan">
            <i class="fas fa-plus me-1"></i> Tambah Satuan
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Satuan Tersedia</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Satuan</th>
                    <th>Keterangan</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($satuans as $key => $sat)
                <tr>
                    <td>{{ $satuans->firstItem() + $key }}</td>
                    <td class="fw-bold text-primary">{{ $sat->nama_satuan }}</td>
                    <td>{{ $sat->keterangan ?? '-' }}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEditSatuan{{ $sat->id }}"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusForm('del-sat-{{ $sat->id }}')"><i class="fas fa-trash"></i></button>
                        <form id="del-sat-{{ $sat->id }}" action="{{ route('master.satuan.destroy', $sat->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEditSatuan{{ $sat->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('master.satuan.update', $sat->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title fs-6 fw-bold">Edit Satuan: {{ $sat->nama_satuan }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Nama Satuan <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_satuan" class="form-control" value="{{ $sat->nama_satuan }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Keterangan</label>
                                        <input type="text" name="keterangan" class="form-control" value="{{ $sat->keterangan }}" placeholder="opsional">
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
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data master satuan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($satuans->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $satuans->links() }}
    </div>
    @endif
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahSatuan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('master.satuan.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6 fw-bold">Tambah Satuan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_satuan" class="form-control" placeholder="cth: Kg, Sak, Kwintal" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="opsional">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Satuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
