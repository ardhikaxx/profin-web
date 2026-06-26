# rule-sistem.md
# Blueprint Arsitektur Sistem — Sistem Informasi Produksi dan Keuangan Terintegrasi
# UD. Sumber Bawang Timur

---

## 1. IDENTITAS PROYEK

| Atribut | Detail |
|---|---|
| Nama Sistem | Sistem Informasi Produksi dan Keuangan Terintegrasi |
| Nama Usaha | UD. Sumber Bawang Timur |
| Framework | Laravel 12 |
| PHP | >= 8.2 |
| Database | MySQL 8.x |
| Front-End Styling | Bootstrap 5 (CDN) |
| Icons | Font Awesome 6 (CDN) |
| Alert & Konfirmasi | SweetAlert2 (CDN) |
| Bahasa Antarmuka | Indonesia |

---

## 2. TUJUAN SISTEM

Sistem ini dibangun bukan sekadar aplikasi pencatatan digital, melainkan sebagai **Sistem Informasi Produksi dan Keuangan Terintegrasi** yang menghubungkan seluruh aktivitas produksi dengan transaksi keuangan secara otomatis, sehingga setiap aktivitas produksi menghasilkan informasi keuangan yang dapat digunakan untuk menyusun laporan usaha secara real-time.

### Masalah yang Diselesaikan
- Pencatatan produksi dan keuangan masih manual menggunakan buku tulis
- Data produksi dan keuangan berdiri sendiri, tidak terhubung
- Proses rekapitulasi membutuhkan waktu lama dan rawan kesalahan
- Owner kesulitan memperoleh laporan akurat dan tepat waktu
- Tidak ada kontrol stok produk jadi secara real-time
- Tidak ada audit trail atas perubahan data

---

## 3. ARSITEKTUR SISTEM

### 3.1 Pola Arsitektur
```
MVC (Model - View - Controller) dengan Service Layer
Laravel 12 — Monolithic Full-Stack Application
```

### 3.2 Struktur Direktori Laravel

```
app/
├── Console/
├── Exceptions/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── AuthController.php
│   │   ├── Dashboard/
│   │   │   └── DashboardController.php
│   │   ├── Master/
│   │   │   ├── ProdukController.php
│   │   │   ├── KategoriPengeluaranController.php
│   │   │   ├── SatuanController.php
│   │   │   └── UserController.php
│   │   ├── Produksi/
│   │   │   └── ProduksiController.php
│   │   ├── Stok/
│   │   │   └── StokController.php
│   │   ├── Pengeluaran/
│   │   │   └── PengeluaranController.php
│   │   ├── Laporan/
│   │   │   ├── LaporanProduksiController.php
│   │   │   ├── LaporanStokController.php
│   │   │   ├── LaporanPengeluaranController.php
│   │   │   └── LaporanLabaRugiController.php
│   │   └── AuditLog/
│   │       └── AuditLogController.php
│   ├── Middleware/
│   │   ├── RoleMiddleware.php
│   │   └── AuditLogMiddleware.php
│   └── Requests/
│       ├── ProduksiRequest.php
│       ├── PengeluaranRequest.php
│       └── ProdukRequest.php
├── Models/
│   ├── User.php
│   ├── Produk.php
│   ├── Satuan.php
│   ├── KategoriPengeluaran.php
│   ├── Produksi.php
│   ├── Stok.php
│   ├── Pengeluaran.php
│   └── AuditLog.php
├── Services/
│   ├── ProduksiService.php
│   ├── StokService.php
│   ├── PengeluaranService.php
│   ├── LaporanService.php
│   └── AuditLogService.php
└── Exports/
    ├── LaporanProduksiExport.php
    ├── LaporanStokExport.php
    ├── LaporanPengeluaranExport.php
    └── LaporanLabaRugiExport.php

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   ├── sidebar.blade.php
│   │   └── navbar.blade.php
│   ├── auth/
│   │   └── login.blade.php
│   ├── dashboard/
│   │   └── index.blade.php
│   ├── master/
│   │   ├── produk/
│   │   ├── kategori/
│   │   ├── satuan/
│   │   └── user/
│   ├── produksi/
│   ├── stok/
│   ├── pengeluaran/
│   ├── laporan/
│   └── audit-log/
└── js/
    └── sweetalert-helpers.js

routes/
├── web.php
└── auth.php

database/
├── migrations/
└── seeders/
```

---

## 4. SKEMA DATABASE

### 4.1 Tabel `users`
```sql
id              BIGINT UNSIGNED PK AUTO_INCREMENT
name            VARCHAR(100) NOT NULL
username        VARCHAR(50)  NOT NULL UNIQUE
email           VARCHAR(100) NOT NULL UNIQUE
password        VARCHAR(255) NOT NULL
role            ENUM('owner','karyawan') NOT NULL DEFAULT 'karyawan'
is_active       TINYINT(1)   NOT NULL DEFAULT 1
foto            VARCHAR(255) NULL
remember_token  VARCHAR(100) NULL
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

### 4.2 Tabel `satuans`
```sql
id          BIGINT UNSIGNED PK AUTO_INCREMENT
nama_satuan VARCHAR(50)  NOT NULL
keterangan  VARCHAR(100) NULL
created_at  TIMESTAMP NULL
updated_at  TIMESTAMP NULL
```

### 4.3 Tabel `produks`
```sql
id              BIGINT UNSIGNED PK AUTO_INCREMENT
kode_produk     VARCHAR(20)    NOT NULL UNIQUE
nama_produk     VARCHAR(100)   NOT NULL
satuan_id       FK → satuans.id
harga_estimasi  DECIMAL(15,2)  NULL DEFAULT 0
stok_minimum    INT            NOT NULL DEFAULT 0
deskripsi       TEXT           NULL
is_active       TINYINT(1)     NOT NULL DEFAULT 1
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

### 4.4 Tabel `kategori_pengeluarans`
```sql
id              BIGINT UNSIGNED PK AUTO_INCREMENT
nama_kategori   VARCHAR(100) NOT NULL
deskripsi       TEXT         NULL
is_active       TINYINT(1)   NOT NULL DEFAULT 1
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

### 4.5 Tabel `produksis`
```sql
id                  BIGINT UNSIGNED PK AUTO_INCREMENT
kode_produksi       VARCHAR(30)    NOT NULL UNIQUE
tanggal_produksi    DATE           NOT NULL
produk_id           FK → produks.id
jumlah_produksi     INT            NOT NULL
jumlah_gagal        INT            NOT NULL DEFAULT 0
jumlah_bersih       INT AS (jumlah_produksi - jumlah_gagal) VIRTUAL
satuan_id           FK → satuans.id
karyawan_id         FK → users.id
keterangan          TEXT           NULL
status              ENUM('draft','terverifikasi') NOT NULL DEFAULT 'draft'
verified_by         FK → users.id NULL
verified_at         TIMESTAMP NULL
created_at          TIMESTAMP NULL
updated_at          TIMESTAMP NULL
```

### 4.6 Tabel `stoks`
```sql
id          BIGINT UNSIGNED PK AUTO_INCREMENT
produk_id   FK → produks.id UNIQUE
jumlah_stok INT    NOT NULL DEFAULT 0
updated_at  TIMESTAMP NULL
```

> **Catatan:** Satu baris per produk. Update dilakukan melalui `StokService` setiap kali produksi disimpan atau stok dikurangi manual.

### 4.7 Tabel `stok_histories`
```sql
id              BIGINT UNSIGNED PK AUTO_INCREMENT
produk_id       FK → produks.id
jenis           ENUM('masuk','keluar','koreksi') NOT NULL
jumlah          INT    NOT NULL
stok_sebelum    INT    NOT NULL
stok_sesudah    INT    NOT NULL
referensi_tipe  VARCHAR(50)  NULL   -- 'Produksi', 'Penjualan', 'Manual'
referensi_id    BIGINT UNSIGNED NULL
keterangan      TEXT   NULL
user_id         FK → users.id
created_at      TIMESTAMP NULL
```

### 4.8 Tabel `pengeluarans`
```sql
id                      BIGINT UNSIGNED PK AUTO_INCREMENT
kode_transaksi          VARCHAR(30)    NOT NULL UNIQUE
tanggal_pengeluaran     DATE           NOT NULL
kategori_pengeluaran_id FK → kategori_pengeluarans.id
jumlah                  DECIMAL(15,2)  NOT NULL
keterangan              TEXT           NULL
bukti_foto              VARCHAR(255)   NULL
karyawan_id             FK → users.id
status                  ENUM('draft','terverifikasi') NOT NULL DEFAULT 'draft'
verified_by             FK → users.id NULL
verified_at             TIMESTAMP NULL
created_at              TIMESTAMP NULL
updated_at              TIMESTAMP NULL
```

### 4.9 Tabel `audit_logs`
```sql
id              BIGINT UNSIGNED PK AUTO_INCREMENT
user_id         FK → users.id NULL
nama_user       VARCHAR(100) NULL
ip_address      VARCHAR(45)  NULL
user_agent      TEXT         NULL
modul           VARCHAR(50)  NOT NULL   -- 'Produksi', 'Pengeluaran', 'Stok', dll.
aksi            VARCHAR(50)  NOT NULL   -- 'login', 'create', 'update', 'delete', 'verify', 'export'
deskripsi       TEXT         NULL
data_lama       JSON         NULL
data_baru       JSON         NULL
created_at      TIMESTAMP NULL
```

---

## 5. MODUL SISTEM

### 5.1 Modul Autentikasi
**Domain:** `Authentication`
**Controller:** `AuthController`

| Fitur | Keterangan |
|---|---|
| Login | Form email/username + password, ingat saya |
| Logout | Konfirmasi SweetAlert2, hapus session |
| Ubah Profil | Nama, email, foto, ganti password |
| Guard | `web` bawaan Laravel |
| Middleware | `auth`, `RoleMiddleware` |

**Role:**
- `owner` — akses penuh seluruh sistem
- `karyawan` — akses operasional terbatas

---

### 5.2 Modul Master Data
**Domain:** `Master`

#### 5.2.1 Master Produk
- CRUD produk (kode, nama, satuan, harga estimasi, stok minimum)
- Soft delete / nonaktifkan produk
- Hanya owner yang dapat mengelola

#### 5.2.2 Master Satuan
- CRUD satuan (kg, ikat, karung, dll.)
- Hanya owner

#### 5.2.3 Master Kategori Pengeluaran
- CRUD kategori (Bahan Baku, Transportasi, Listrik, Perawatan Alat, Lain-lain)
- Hanya owner

#### 5.2.4 Master Pengguna
- CRUD user (nama, username, email, password, role, status aktif)
- Reset password karyawan
- Hanya owner

---

### 5.3 Modul Produksi
**Domain:** `Produksi`
**Controller:** `ProduksiController`
**Service:** `ProduksiService`

#### Alur Input Produksi:
```
Karyawan Input Form Produksi
    → Validasi data (ProduksiRequest)
    → Simpan ke tabel produksis (status = 'draft')
    → ProduksiService::prosesStok()
        → Tambah stok di tabel stoks
        → Catat di stok_histories (jenis = 'masuk')
    → AuditLogService::catat('Produksi', 'create', ...)
    → Flash success → redirect index
```

#### Hak Akses:
| Aksi | Karyawan | Owner |
|---|---|---|
| Input produksi | ✅ | ✅ |
| Edit produksi (status draft) | ✅ (milik sendiri) | ✅ |
| Edit produksi (terverifikasi) | ❌ | ✅ |
| Verifikasi produksi | ❌ | ✅ |
| Hapus produksi | ❌ | ✅ |
| Lihat semua data | ❌ | ✅ |
| Lihat data sendiri | ✅ | ✅ |

#### Form Input Produksi:
- Tanggal produksi
- Produk (dropdown dari master)
- Jumlah produksi
- Jumlah gagal / rusak (opsional, default 0)
- Satuan (auto-fill dari produk)
- Karyawan (auto-fill dari login, owner bisa pilih)
- Keterangan

#### Kode Produksi (Auto-Generate):
```
Format: PRD-YYYYMMDD-XXX
Contoh: PRD-20250615-001
```

---

### 5.4 Modul Stok
**Domain:** `Persediaan`
**Controller:** `StokController`
**Service:** `StokService`

#### Fungsi Utama:
- Menampilkan stok terkini seluruh produk
- Histori pergerakan stok (masuk, keluar, koreksi)
- Pengurangan stok manual (distribusi/penjualan) oleh owner
- Alert stok di bawah minimum (badge merah di sidebar + notifikasi dashboard)
- Koreksi stok (owner only) dengan keterangan wajib

#### Logika Stok:
```php
// StokService.php
public function tambahStok(int $produkId, int $jumlah, string $referensiTipe, int $referensiId, int $userId): void
{
    $stok = Stok::firstOrCreate(['produk_id' => $produkId], ['jumlah_stok' => 0]);
    $sebelum = $stok->jumlah_stok;
    $stok->increment('jumlah_stok', $jumlah);
    $this->catatHistori($produkId, 'masuk', $jumlah, $sebelum, $stok->jumlah_stok, $referensiTipe, $referensiId, $userId);
}

public function kurangiStok(int $produkId, int $jumlah, string $referensiTipe, int $referensiId, int $userId): void
{
    $stok = Stok::where('produk_id', $produkId)->firstOrFail();
    $sebelum = $stok->jumlah_stok;
    $stok->decrement('jumlah_stok', $jumlah);
    $this->catatHistori($produkId, 'keluar', $jumlah, $sebelum, $stok->jumlah_stok, $referensiTipe, $referensiId, $userId);
}
```

---

### 5.5 Modul Pengeluaran
**Domain:** `Keuangan`
**Controller:** `PengeluaranController`
**Service:** `PengeluaranService`

#### Alur Input Pengeluaran:
```
Karyawan/Owner Input Form Pengeluaran
    → Validasi (PengeluaranRequest)
    → Simpan ke tabel pengeluarans (status = 'draft')
    → AuditLogService::catat('Pengeluaran', 'create', ...)
    → Flash success → redirect index
```

#### Form Input Pengeluaran:
- Tanggal pengeluaran
- Kategori pengeluaran (dropdown)
- Jumlah (Rp)
- Keterangan
- Bukti foto (opsional, upload)

#### Kode Transaksi (Auto-Generate):
```
Format: EXP-YYYYMMDD-XXX
Contoh: EXP-20250615-001
```

#### Hak Akses:
| Aksi | Karyawan | Owner |
|---|---|---|
| Input pengeluaran | ✅ | ✅ |
| Edit (status draft) | ✅ (milik sendiri) | ✅ |
| Edit (terverifikasi) | ❌ | ✅ |
| Verifikasi | ❌ | ✅ |
| Hapus | ❌ | ✅ |
| Lihat semua | ❌ | ✅ |

---

### 5.6 Modul Laporan
**Domain:** `Laporan`
**Controller:** `LaporanProduksiController`, `LaporanStokController`, `LaporanPengeluaranController`, `LaporanLabaRugiController`
**Service:** `LaporanService`
**Akses:** Owner only

#### 5.6.1 Laporan Produksi
- Filter: tanggal, minggu, bulan, tahun, produk, karyawan
- Kolom: tanggal, kode, karyawan, produk, jumlah produksi, jumlah gagal, jumlah bersih, satuan, status
- Summary: total produksi, total gagal, total bersih per produk
- Grafik: bar chart produksi per periode (Chart.js CDN)
- Export: PDF (DomPDF) + Excel (Maatwebsite/Excel)

#### 5.6.2 Laporan Stok
- Snapshot stok saat ini per produk
- Filter: produk, status stok (normal / di bawah minimum)
- Kolom: produk, satuan, stok saat ini, stok minimum, status
- Histori pergerakan stok (filter tanggal, produk)
- Export: PDF + Excel

#### 5.6.3 Laporan Pengeluaran Operasional
- Filter: tanggal, bulan, tahun, kategori
- Kolom: tanggal, kode, kategori, karyawan, jumlah, keterangan, status
- Summary: total pengeluaran per kategori, total keseluruhan
- Grafik: pie chart per kategori (Chart.js)
- Export: PDF + Excel

#### 5.6.4 Laporan Laba Rugi
- Formula: **Laba Rugi = Estimasi Pendapatan − Total Pengeluaran**
- Estimasi Pendapatan = Σ (jumlah_bersih_produksi × harga_estimasi_produk)
- Filter: bulan, tahun, periode custom
- Tampilan: ringkasan pendapatan, rincian pengeluaran per kategori, laba/rugi bersih
- Export: PDF + Excel

> **Catatan:** Estimasi pendapatan dihitung dari harga estimasi produk × jumlah produksi bersih. Apabila modul penjualan ditambahkan di masa mendatang, pendapatan aktual dari penjualan akan menggantikan estimasi ini.

---

### 5.7 Modul Dashboard
**Domain:** `Dashboard`
**Controller:** `DashboardController`
**Akses:** Owner full, Karyawan view terbatas (hanya produksi & stok)

#### Widget Dashboard Owner:
| Widget | Sumber Data |
|---|---|
| Total Produksi Hari Ini | `produksis` WHERE tanggal = today |
| Jumlah Stok Produk | `stoks` SUM jumlah_stok |
| Total Pengeluaran Bulan Ini | `pengeluarans` WHERE bulan = this month |
| Estimasi Pendapatan Bulan Ini | Kalkulasi dari produksi × harga estimasi |
| Laba Sementara | Estimasi Pendapatan − Total Pengeluaran |
| Jumlah Transaksi Pengeluaran | COUNT pengeluarans bulan ini |
| Produk Stok Rendah | `stoks` WHERE jumlah_stok < stok_minimum |
| Data Produksi Pending Verifikasi | COUNT produksis WHERE status = 'draft' |

#### Grafik Dashboard:
- Grafik garis: produksi 30 hari terakhir (Chart.js)
- Grafik bar: pengeluaran per kategori bulan ini (Chart.js)
- Grafik area: perbandingan estimasi pendapatan vs pengeluaran 6 bulan (Chart.js)

#### Widget Dashboard Karyawan:
- Produksi hari ini (milik sendiri)
- Pengeluaran hari ini (milik sendiri)
- Stok seluruh produk (read only)

---

### 5.8 Modul Audit Log
**Domain:** `AuditLog`
**Controller:** `AuditLogController`
**Service:** `AuditLogService`
**Akses:** Owner only

#### Data yang Dicatat:
| Event | Modul |
|---|---|
| Login / Logout | Auth |
| Create / Update / Delete Produksi | Produksi |
| Verifikasi Produksi | Produksi |
| Create / Update / Delete Pengeluaran | Pengeluaran |
| Verifikasi Pengeluaran | Keuangan |
| Koreksi / Kurangi Stok | Stok |
| CRUD Master Data | Master |
| Export Laporan | Laporan |
| Create / Update / Delete User | Master |

#### Tampilan Audit Log:
- Filter: tanggal, user, modul, aksi
- Kolom: waktu, nama user, IP, modul, aksi, deskripsi
- Detail: tampil data lama vs data baru (JSON diff)

---

## 6. ROUTING

```php
// routes/web.php

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [AuthController::class, 'profil'])->name('profil');
    Route::put('/profil', [AuthController::class, 'updateProfil'])->name('profil.update');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Produksi (Karyawan + Owner)
    Route::resource('produksi', ProduksiController::class);
    Route::patch('produksi/{id}/verifikasi', [ProduksiController::class, 'verifikasi'])
         ->name('produksi.verifikasi')->middleware('role:owner');

    // Stok (Owner)
    Route::middleware('role:owner')->group(function () {
        Route::get('stok', [StokController::class, 'index'])->name('stok.index');
        Route::get('stok/histori', [StokController::class, 'histori'])->name('stok.histori');
        Route::post('stok/kurangi', [StokController::class, 'kurangi'])->name('stok.kurangi');
        Route::post('stok/koreksi', [StokController::class, 'koreksi'])->name('stok.koreksi');
    });

    // Pengeluaran (Karyawan + Owner)
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::patch('pengeluaran/{id}/verifikasi', [PengeluaranController::class, 'verifikasi'])
         ->name('pengeluaran.verifikasi')->middleware('role:owner');

    // Master Data (Owner only)
    Route::middleware('role:owner')->prefix('master')->name('master.')->group(function () {
        Route::resource('produk', ProdukController::class);
        Route::resource('satuan', SatuanController::class);
        Route::resource('kategori', KategoriPengeluaranController::class);
        Route::resource('user', UserController::class);
        Route::patch('user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
    });

    // Laporan (Owner only)
    Route::middleware('role:owner')->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('produksi', [LaporanProduksiController::class, 'index'])->name('produksi');
        Route::get('produksi/export-pdf', [LaporanProduksiController::class, 'exportPdf'])->name('produksi.pdf');
        Route::get('produksi/export-excel', [LaporanProduksiController::class, 'exportExcel'])->name('produksi.excel');

        Route::get('stok', [LaporanStokController::class, 'index'])->name('stok');
        Route::get('stok/export-pdf', [LaporanStokController::class, 'exportPdf'])->name('stok.pdf');
        Route::get('stok/export-excel', [LaporanStokController::class, 'exportExcel'])->name('stok.excel');

        Route::get('pengeluaran', [LaporanPengeluaranController::class, 'index'])->name('pengeluaran');
        Route::get('pengeluaran/export-pdf', [LaporanPengeluaranController::class, 'exportPdf'])->name('pengeluaran.pdf');
        Route::get('pengeluaran/export-excel', [LaporanPengeluaranController::class, 'exportExcel'])->name('pengeluaran.excel');

        Route::get('laba-rugi', [LaporanLabaRugiController::class, 'index'])->name('laba-rugi');
        Route::get('laba-rugi/export-pdf', [LaporanLabaRugiController::class, 'exportPdf'])->name('laba-rugi.pdf');
        Route::get('laba-rugi/export-excel', [LaporanLabaRugiController::class, 'exportExcel'])->name('laba-rugi.excel');
    });

    // Audit Log (Owner only)
    Route::middleware('role:owner')->group(function () {
        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
        Route::get('audit-log/{id}', [AuditLogController::class, 'show'])->name('audit-log.show');
    });
});
```

---

## 7. MIDDLEWARE

### 7.1 RoleMiddleware
```php
// app/Http/Middleware/RoleMiddleware.php

public function handle(Request $request, Closure $next, string $role): Response
{
    if (!Auth::check() || Auth::user()->role !== $role) {
        abort(403, 'Akses ditolak. Anda tidak memiliki hak akses ke halaman ini.');
    }
    return $next($request);
}
```

**Registrasi di bootstrap/app.php:**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => RoleMiddleware::class,
    ]);
})
```

### 7.2 AuditLogMiddleware
Middleware otomatis mencatat akses halaman sensitif. Pencatatan detail (data lama/baru) dilakukan di dalam `AuditLogService` yang dipanggil secara eksplisit dari controller.

---

## 8. SERVICE LAYER

### 8.1 AuditLogService
```php
// app/Services/AuditLogService.php

class AuditLogService
{
    public function catat(
        string $modul,
        string $aksi,
        string $deskripsi,
        ?array $dataLama = null,
        ?array $dataBaru = null
    ): void {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'nama_user'  => Auth::user()?->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'modul'      => $modul,
            'aksi'       => $aksi,
            'deskripsi'  => $deskripsi,
            'data_lama'  => $dataLama ? json_encode($dataLama) : null,
            'data_baru'  => $dataBaru ? json_encode($dataBaru) : null,
        ]);
    }
}
```

### 8.2 ProduksiService
```php
// app/Services/ProduksiService.php

class ProduksiService
{
    public function __construct(
        protected StokService $stokService,
        protected AuditLogService $auditLog
    ) {}

    public function simpan(array $data): Produksi
    {
        $produksi = Produksi::create($data);
        $jumlahBersih = $produksi->jumlah_produksi - $produksi->jumlah_gagal;
        $this->stokService->tambahStok($produksi->produk_id, $jumlahBersih, 'Produksi', $produksi->id, Auth::id());
        $this->auditLog->catat('Produksi', 'create', "Input produksi {$produksi->kode_produksi}", null, $produksi->toArray());
        return $produksi;
    }

    public function update(Produksi $produksi, array $data): Produksi
    {
        $lama = $produksi->toArray();
        // Rollback stok lama
        $jumlahBersihLama = $produksi->jumlah_produksi - $produksi->jumlah_gagal;
        $this->stokService->kurangiStok($produksi->produk_id, $jumlahBersihLama, 'Koreksi', $produksi->id, Auth::id());
        // Update data
        $produksi->update($data);
        // Tambah stok baru
        $jumlahBersihBaru = $produksi->jumlah_produksi - $produksi->jumlah_gagal;
        $this->stokService->tambahStok($produksi->produk_id, $jumlahBersihBaru, 'Produksi', $produksi->id, Auth::id());
        $this->auditLog->catat('Produksi', 'update', "Update produksi {$produksi->kode_produksi}", $lama, $produksi->toArray());
        return $produksi;
    }
}
```

---

## 9. PAKET COMPOSER (DEPENDENCIES)

```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "laravel/tinker": "^2.9",
        "barryvdh/laravel-dompdf": "^3.0",
        "maatwebsite/excel": "^3.1"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.13",
        "laravel/sail": "^1.26",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.1",
        "phpunit/phpunit": "^11.0"
    }
}
```

**CDN Front-End (tidak menggunakan NPM/Vite):**
```html
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Font Awesome 6 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
```

---

## 10. SWEETALERT2 WRAPPER OBJECT

Semua alert dan konfirmasi menggunakan objek wrapper `SwalHelper` yang didefinisikan di `public/js/sweetalert-helpers.js` dan di-include di `layouts/app.blade.php`:

```javascript
// public/js/sweetalert-helpers.js

const SwalHelper = {
    success: (message, callback) => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            confirmButtonColor: '#198754',
            timer: 2500,
            timerProgressBar: true,
        }).then(() => { if (callback) callback(); });
    },

    error: (message) => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: message,
            confirmButtonColor: '#dc3545',
        });
    },

    warning: (message) => {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: message,
            confirmButtonColor: '#ffc107',
        });
    },

    confirmDelete: (callback) => {
        Swal.fire({
            icon: 'warning',
            title: 'Konfirmasi Hapus',
            text: 'Data yang dihapus tidak dapat dikembalikan. Lanjutkan?',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
        }).then((result) => { if (result.isConfirmed && callback) callback(); });
    },

    confirmLogout: (formId) => {
        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar dari sistem?',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-sign-out-alt me-1"></i> Ya, Logout',
            cancelButtonText: 'Batal',
        }).then((result) => { if (result.isConfirmed) document.getElementById(formId).submit(); });
    },

    confirmVerify: (callback) => {
        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Verifikasi',
            text: 'Data yang telah diverifikasi tidak dapat diubah oleh karyawan. Lanjutkan?',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Ya, Verifikasi',
            cancelButtonText: 'Batal',
        }).then((result) => { if (result.isConfirmed && callback) callback(); });
    },

    flashFromSession: () => {
        // Dipanggil di layout, membaca session flash dari Laravel
        const success = document.querySelector('meta[name="flash-success"]')?.content;
        const error   = document.querySelector('meta[name="flash-error"]')?.content;
        if (success) SwalHelper.success(success);
        if (error)   SwalHelper.error(error);
    }
};

document.addEventListener('DOMContentLoaded', SwalHelper.flashFromSession);
```

**Penggunaan di Blade (flash session):**
```php
// Di dalam <head> layout
@if(session('success'))
<meta name="flash-success" content="{{ session('success') }}">
@endif
@if(session('error'))
<meta name="flash-error" content="{{ session('error') }}">
@endif
```

---

## 11. ALUR BISNIS SISTEM (BUSINESS PROCESS FLOW)

```
[1] LOGIN
    └─ Autentikasi email + password
    └─ Redirect berdasarkan role

[2] INPUT PRODUKSI (Karyawan/Owner)
    └─ Form produksi harian
    └─ Validasi data
    └─ Simpan → status 'draft'
    └─ Stok bertambah otomatis
    └─ Audit log dicatat

[3] INPUT PENGELUARAN (Karyawan/Owner)
    └─ Form pengeluaran
    └─ Pilih kategori
    └─ Simpan → status 'draft'
    └─ Audit log dicatat

[4] VERIFIKASI (Owner)
    └─ Review data produksi / pengeluaran
    └─ Konfirmasi verifikasi
    └─ Status berubah → 'terverifikasi'
    └─ Data terkunci dari perubahan karyawan

[5] DASHBOARD (Real-Time)
    └─ Statistik produksi, stok, keuangan
    └─ Grafik tren produksi & pengeluaran
    └─ Alert stok minimum

[6] LAPORAN (Owner)
    └─ Filter periode
    └─ Lihat laporan produksi, stok, pengeluaran, laba rugi
    └─ Export PDF / Excel

[7] MONITORING & AUDIT
    └─ Audit log seluruh aktivitas pengguna
    └─ Detail perubahan data (lama vs baru)
```

---

## 12. ATURAN BISNIS KRITIS

1. **Stok tidak boleh negatif.** Validasi di `StokService::kurangiStok()` — throw exception jika stok < jumlah dikurangi.
2. **Data terverifikasi tidak dapat diedit karyawan.** Pengecekan di `ProduksiController::edit()` dan `PengeluaranController::edit()`.
3. **Karyawan hanya melihat data milik sendiri** pada halaman index produksi dan pengeluaran.
4. **Kode produksi dan kode transaksi unik dan auto-generate** — tidak boleh input manual.
5. **Hapus produksi akan rollback stok** secara otomatis melalui `ProduksiService::hapus()`.
6. **Audit log tidak boleh dihapus** — tabel `audit_logs` hanya insert, tidak ada endpoint delete.
7. **Password karyawan hanya bisa direset oleh owner**, bukan diubah sendiri kecuali melalui menu profil.

---

## 13. SEEDER DAN DATA AWAL

```
DatabaseSeeder
├── RoleSeeder           → 2 user awal (owner + 1 karyawan demo)
├── SatuanSeeder         → kg, ikat, karung, buah, liter
├── KategoriSeeder       → Bahan Baku, Transportasi, Listrik, Perawatan Alat, Lain-lain
└── ProdukSeeder         → 3–5 produk contoh bawang
```

**Kredensial Default:**
```
Owner   : owner@sbt.local  / password: owner123
Karyawan: karyawan@sbt.local / password: karyawan123
```

---

## 14. CHECKLIST KEAMANAN

- [x] CSRF token pada semua form POST/PUT/DELETE
- [x] Validasi input server-side via FormRequest
- [x] Role-based access control via Middleware
- [x] Password di-hash dengan Bcrypt
- [x] Rate limiting pada route login (throttle:5,1)
- [x] Audit log seluruh aksi sensitif
- [x] File upload validasi mime type (jpg, png, pdf) & max 2MB
- [x] Data karyawan difilter berdasarkan `Auth::id()` di controller
- [x] Foreign key constraint di database
- [x] `abort(403)` pada akses tidak sah

---

*File ini merupakan dokumen arsitektur teknis resmi untuk proyek Sistem Informasi Produksi dan Keuangan UD. Sumber Bawang Timur. Dibuat sebagai panduan pengembangan menggunakan Laravel 12.*
