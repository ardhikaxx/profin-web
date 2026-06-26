@extends('layouts.app')
@section('title', 'Audit Log Sistem')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-shield-alt me-2 text-primary"></i>Audit Log Sistem</h4>
        <p class="text-muted small mb-0">Riwayat pencatatan aktivitas pengguna dan perubahan data sistem secara mendetail (tersedia bagi Owner).</p>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('audit-log.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Sampai</label>
                    <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Pengguna</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">Semua User</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->role }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Modul</label>
                    <select name="modul" class="form-select form-select-sm">
                        <option value="">Semua Modul</option>
                        @foreach($moduls as $m)
                            <option value="{{ $m }}" {{ request('modul') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Aksi</label>
                    <select name="aksi" class="form-select form-select-sm">
                        <option value="">Semua Aksi</option>
                        <option value="login" {{ request('aksi') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('aksi') == 'logout' ? 'selected' : '' }}>Logout</option>
                        <option value="create" {{ request('aksi') == 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('aksi') == 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('aksi') == 'delete' ? 'selected' : '' }}>Delete</option>
                        <option value="verify" {{ request('aksi') == 'verify' ? 'selected' : '' }}>Verify</option>
                        <option value="export" {{ request('aksi') == 'export' ? 'selected' : '' }}>Export</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('audit-log.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Riwayat Audit Log</span>
        <span class="badge bg-primary rounded-pill">{{ $logs->total() }} Data Terpapar</span>
    </div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 140px;">Waktu</th>
                    <th>User</th>
                    <th>IP Address</th>
                    <th>Modul</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th class="text-center" style="width: 80px;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="col-kode">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="fw-semibold">{{ $log->nama_user ?? ($log->user->name ?? 'System/Guest') }}</td>
                    <td class="col-kode text-muted">{{ $log->ip_address ?? '-' }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $log->modul }}</span></td>
                    <td>
                        @php
                            $badgeColor = match($log->aksi) {
                                'login', 'create' => 'bg-success',
                                'update', 'verify' => 'bg-info text-dark',
                                'delete' => 'bg-danger',
                                'logout', 'export' => 'bg-warning text-dark',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeColor }} text-uppercase">{{ $log->aksi }}</span>
                    </td>
                    <td class="small">{{ $log->deskripsi ?? '-' }}</td>
                    <td class="text-center">
                        @if($log->data_lama || $log->data_baru)
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDetailModal({{ $log->id }})" title="Lihat Diff">
                            <i class="fas fa-code"></i>
                        </button>
                        @else
                        <span class="text-muted small">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat aktivitas sistem.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $logs->links() }}
    </div>
    @endif
</div>

<!-- Modal Detail Audit Log -->
<div class="modal fade" id="modalDetailAudit" tabindex="-1" aria-labelledby="modalDetailAuditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fs-6 fw-bold" id="modalDetailAuditLabel"><i class="fas fa-file-code me-2"></i>Detail Perubahan Data (Diff)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalDetailAuditBody">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showDetailModal(id) {
    const modalEl = document.getElementById('modalDetailAudit');
    const modal = new bootstrap.Modal(modalEl);
    const bodyEl = document.getElementById('modalDetailAuditBody');
    
    bodyEl.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2 small text-muted">Memuat detail...</p></div>';
    modal.show();

    fetch(`/audit-log/${id}`)
        .then(res => res.json())
        .then(data => {
            let html = `<div class="mb-3 small">
                <strong>Pengguna:</strong> ${data.nama_user || '-'} <br>
                <strong>Waktu:</strong> ${new Date(data.created_at).toLocaleString('id-ID')} <br>
                <strong>Keterangan:</strong> ${data.deskripsi || '-'}
            </div>`;

            html += `<div class="row g-3">`;
            
            if (data.data_lama) {
                let lamaFormatted = typeof data.data_lama === 'string' ? JSON.parse(data.data_lama) : data.data_lama;
                html += `<div class="col-md-6">
                    <div class="card border-danger h-100 mb-0">
                        <div class="card-header bg-danger text-white py-1 small fw-bold">Data Sebelumnya</div>
                        <div class="card-body p-2 bg-light font-monospace" style="font-size: 11px; white-space: pre-wrap; overflow-x: auto;">${JSON.stringify(lamaFormatted, null, 2)}</div>
                    </div>
                </div>`;
            }

            if (data.data_baru) {
                let baruFormatted = typeof data.data_baru === 'string' ? JSON.parse(data.data_baru) : data.data_baru;
                html += `<div class="col-md-${data.data_lama ? '6' : '12'}">
                    <div class="card border-success h-100 mb-0">
                        <div class="card-header bg-success text-white py-1 small fw-bold">Data Baru / Terupdate</div>
                        <div class="card-body p-2 bg-light font-monospace" style="font-size: 11px; white-space: pre-wrap; overflow-x: auto;">${JSON.stringify(baruFormatted, null, 2)}</div>
                    </div>
                </div>`;
            }

            html += `</div>`;
            bodyEl.innerHTML = html;
        })
        .catch(err => {
            bodyEl.innerHTML = '<div class="alert alert-danger mb-0">Gagal memuat rincian data.</div>';
            console.error(err);
        });
}
</script>
@endpush
