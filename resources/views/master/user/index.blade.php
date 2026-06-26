@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-users me-2 text-primary"></i>Manajemen Pengguna</h4>
        <p class="text-muted small mb-0">Daftar hak akses pengguna sistem (Owner vs Karyawan).</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="fas fa-user-plus me-1"></i> Tambah Pengguna
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Pengguna Aktif</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Pengguna</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role (RBAC)</th>
                    <th>Status</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $key => $usr)
                <tr>
                    <td>{{ $users->firstItem() + $key }}</td>
                    <td class="fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle text-primary fs-5"></i>
                        {{ $usr->name }}
                    </td>
                    <td class="font-monospace text-primary">{{ $usr->username }}</td>
                    <td>{{ $usr->email }}</td>
                    <td>
                        <span class="badge rounded-pill {{ $usr->role === 'owner' ? 'bg-primary' : 'bg-warning text-dark' }} text-uppercase px-3">
                            {{ $usr->role }}
                        </span>
                    </td>
                    <td>
                        @if($usr->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $usr->id }}" title="Edit"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#modalResetPw{{ $usr->id }}" title="Reset PW"><i class="fas fa-key"></i></button>
                        @if($usr->id !== auth()->id())
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusForm('del-usr-{{ $usr->id }}')" title="Hapus"><i class="fas fa-trash"></i></button>
                        <form id="del-usr-{{ $usr->id }}" action="{{ route('master.user.destroy', $usr->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endif
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEditUser{{ $usr->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('master.user.update', $usr->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title fs-6 fw-bold">Edit Akun: {{ $usr->name }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ $usr->name }}" required>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Username <span class="text-danger">*</span></label>
                                            <input type="text" name="username" class="form-control font-monospace" value="{{ $usr->username }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" value="{{ $usr->email }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Role Sistem <span class="text-danger">*</span></label>
                                        <select name="role" class="form-select" required>
                                            <option value="owner" {{ $usr->role === 'owner' ? 'selected' : '' }}>Owner (Super Admin)</option>
                                            <option value="karyawan" {{ $usr->role === 'karyawan' ? 'selected' : '' }}>Karyawan Operasional</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Password Baru (Opsional)</label>
                                        <input type="password" name="password" class="form-control" placeholder="kosongkan jika tetap">
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_act_u_{{ $usr->id }}" value="1" {{ $usr->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-semibold" for="is_act_u_{{ $usr->id }}">Akun Aktif</label>
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

                <!-- Modal Reset PW -->
                <div class="modal fade" id="modalResetPw{{ $usr->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog max-w-sm">
                        <div class="modal-content">
                            <form action="{{ route('master.user.reset-password', $usr->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header bg-warning text-dark">
                                    <h5 class="modal-title fs-6 fw-bold"><i class="fas fa-key me-2"></i>Reset Password Akun</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <p class="small text-muted mb-3">Reset password untuk pengguna <strong>{{ $usr->name }}</strong>.</p>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Password Baru <span class="text-danger">*</span></label>
                                        <input type="text" name="password_baru" class="form-control font-monospace" value="sumberbawang123" required>
                                        <div class="form-text small">Password default: sumberbawang123</div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning fw-bold">Reset Password Sekarang</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('master.user.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6 fw-bold">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="cth: Ahmad Fauzi" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control font-monospace" placeholder="cth: fauzi" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="cth: fauzi@sbt.com" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Role Sistem <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="karyawan">Karyawan Operasional</option>
                            <option value="owner">Owner (Super Admin)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="min. 6 karakter" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
