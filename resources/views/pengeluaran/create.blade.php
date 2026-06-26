@extends('layouts.app')
@section('title', 'Input Pengeluaran')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-plus-circle me-2 text-danger"></i>Input Pengeluaran Baru</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('pengeluaran.index') }}">Pengeluaran</a></li>
                <li class="breadcrumb-item active">Input Baru</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card max-w-2xl">
    <div class="card-header">Formulir Klaim Biaya Operasional</div>
    <div class="card-body p-4">
        <form action="{{ route('pengeluaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pengeluaran" class="form-control @error('tanggal_pengeluaran') is-invalid @enderror" value="{{ old('tanggal_pengeluaran', date('Y-m-d')) }}" required>
                    @error('tanggal_pengeluaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Kategori Biaya <span class="text-danger">*</span></label>
                    <select name="kategori_pengeluaran_id" class="form-select @error('kategori_pengeluaran_id') is-invalid @enderror" required>
                        <option value="">-- pilih kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_pengeluaran_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori_pengeluaran_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Nominal Biaya (Rp) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light fw-bold">Rp</span>
                    <input type="text" class="form-control fs-5 fw-bold text-danger input-nominal @error('jumlah') is-invalid @enderror" data-target="jumlah_val" value="{{ old('jumlah') ? number_format(old('jumlah'),0,',','.') : '' }}" placeholder="0" required>
                    <input type="hidden" name="jumlah" id="jumlah_val" value="{{ old('jumlah', 0) }}">
                </div>
                @error('jumlah') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Keterangan / Keperluan <span class="text-danger">*</span></label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="cth: Pembelian solar 20 liter untuk genset..." required>{{ old('keterangan') }}</textarea>
                @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold">Bukti Nota / Foto (Opsional)</label>
                <input type="file" name="bukti_foto" class="form-control @error('bukti_foto') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                <div class="form-text small">Format JPG, PNG. Maksimal 2MB. Foto nota pembelian atau struk bon.</div>
                @error('bukti_foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger px-4 fw-bold"><i class="fas fa-save me-2"></i> Simpan Transaksi</button>
                <a href="{{ route('pengeluaran.index') }}" class="btn btn-light border px-3">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
