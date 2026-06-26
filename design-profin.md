# design-sistem.md
# Blueprint Desain UI/UX — Sistem Informasi Produksi dan Keuangan Terintegrasi
# UD. Sumber Bawang Timur

---

## 1. FILOSOFI DESAIN

Sistem ini dirancang untuk digunakan oleh karyawan lapangan dan owner UMKM. Prinsip desain yang diterapkan:

- **Simpel & Fungsional** — Tampilan bersih tanpa elemen dekoratif berlebihan. Setiap elemen ada karena memiliki fungsi.
- **Mudah Dipahami** — Label berbahasa Indonesia, ikon intuitif, feedback visual langsung.
- **Mobile-Friendly** — Karyawan bisa input produksi dari HP. Gunakan layout responsif Bootstrap 5.
- **Konsisten** — Warna, tipografi, komponen, dan pola interaksi seragam di seluruh halaman.
- **Feedback Jelas** — Setiap aksi (simpan, hapus, verifikasi) memberikan respon visual melalui SweetAlert2.

---

## 2. DESIGN TOKENS

### 2.1 Palet Warna

```css
/* Warna Utama — Hijau Tua (identik dengan produk pertanian/agribisnis) */
--color-primary:        #1B6B3A;   /* Hijau tua — tombol utama, sidebar aktif, badge */
--color-primary-dark:   #144F2C;   /* Hover state tombol primary */
--color-primary-light:  #D4EDDA;   /* Background card info, badge success ringan */

/* Warna Aksen */
--color-accent:         #F59E0B;   /* Kuning amber — highlight, icon warning, badge pending */
--color-accent-dark:    #D97706;

/* Warna Status */
--color-success:        #198754;   /* Bootstrap success — verifikasi, stok normal */
--color-danger:         #DC3545;   /* Bootstrap danger — hapus, stok minimum, laba negatif */
--color-warning:        #FFC107;   /* Bootstrap warning — draft, perlu perhatian */
--color-info:           #0DCAF0;   /* Bootstrap info — informasi umum */

/* Warna Netral */
--color-sidebar-bg:     #1B2631;   /* Sidebar gelap */
--color-sidebar-text:   #B2BABB;   /* Teks menu sidebar */
--color-sidebar-active: #1B6B3A;   /* Menu aktif sidebar */
--color-body-bg:        #F4F6F9;   /* Background halaman utama */
--color-card-bg:        #FFFFFF;   /* Background card/panel */
--color-border:         #DEE2E6;   /* Border tabel, input, card */
--color-text-primary:   #212529;   /* Teks utama */
--color-text-secondary: #6C757D;   /* Teks sekunder, label, hint */
--color-text-muted:     #ADB5BD;   /* Placeholder, teks nonaktif */
```

### 2.2 Tipografi

```css
/* Google Fonts — import di <head> */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

--font-primary: 'Plus Jakarta Sans', sans-serif;   /* Seluruh teks UI */
--font-mono:    'JetBrains Mono', monospace;        /* Kode transaksi, angka nominal */

/* Ukuran */
--font-size-xs:   11px;
--font-size-sm:   13px;
--font-size-base: 14px;
--font-size-md:   15px;
--font-size-lg:   16px;
--font-size-xl:   18px;
--font-size-2xl:  20px;
--font-size-3xl:  24px;

/* Weight */
--font-regular:   400;
--font-medium:    500;
--font-semibold:  600;
--font-bold:      700;
```

### 2.3 Spacing & Radius

```css
--spacing-xs:   4px;
--spacing-sm:   8px;
--spacing-md:   16px;
--spacing-lg:   24px;
--spacing-xl:   32px;
--spacing-2xl:  48px;

--radius-sm:    4px;
--radius-md:    8px;
--radius-lg:    12px;
--radius-xl:    16px;
--radius-pill:  50px;
```

### 2.4 Shadow

```css
--shadow-card:   0 1px 4px rgba(0,0,0,.08), 0 2px 8px rgba(0,0,0,.04);
--shadow-modal:  0 8px 32px rgba(0,0,0,.18);
--shadow-btn:    0 2px 4px rgba(27,107,58,.25);
```

---

## 3. LAYOUT UTAMA

### 3.1 Struktur Layout (Authenticated)

```
┌─────────────────────────────────────────────────────┐
│  SIDEBAR (fixed, 260px)  │  KONTEN UTAMA            │
│                          │  ┌───────────────────┐   │
│  [Logo + Nama Sistem]    │  │  TOPBAR NAVBAR    │   │
│                          │  └───────────────────┘   │
│  [Avatar + Nama User]    │  ┌───────────────────┐   │
│  [Role Badge]            │  │                   │   │
│                          │  │   PAGE CONTENT    │   │
│  ── MENU UTAMA ──        │  │                   │   │
│  • Dashboard             │  └───────────────────┘   │
│  • Produksi              │                           │
│  • Pengeluaran           │  ┌───────────────────┐   │
│  • Stok                  │  │  FOOTER           │   │
│                          │  └───────────────────┘   │
│  ── LAPORAN ──           │                           │
│  • Lap. Produksi         │                           │
│  • Lap. Stok             │                           │
│  • Lap. Pengeluaran      │                           │
│  • Lap. Laba Rugi        │                           │
│                          │                           │
│  ── MASTER DATA ──       │                           │
│  • Produk                │                           │
│  • Satuan                │                           │
│  • Kategori              │                           │
│  • Pengguna              │                           │
│                          │                           │
│  ── SISTEM ──            │                           │
│  • Audit Log             │                           │
│  • [Logout]              │                           │
└─────────────────────────────────────────────────────┘
```

**Catatan:**
- Karyawan: menu Laporan, Master Data, Audit Log **tidak ditampilkan**
- Di mobile (< 768px): sidebar collapse menjadi hamburger menu

### 3.2 Topbar Navbar

```
[☰ Toggle Sidebar]   [Breadcrumb: Dashboard / Produksi]       [🔔 Notifikasi] [Avatar ▾]
```

- Notifikasi bell: badge merah jika ada stok di bawah minimum atau data pending verifikasi (owner)
- Dropdown avatar: menu Profil, Logout

---

## 4. HALAMAN-HALAMAN SISTEM

---

### 4.1 Halaman Login

**Layout:** Full-page centered, tidak menggunakan sidebar.

```
┌─────────────────────────────────────────────────────┐
│                                                     │
│         [Logo / Ikon Usaha]                        │
│    SISTEM INFORMASI PRODUKSI & KEUANGAN            │
│         UD. Sumber Bawang Timur                    │
│                                                     │
│  ┌─────────────────────────────────────────┐       │
│  │  Email / Username                       │       │
│  │  [                                    ] │       │
│  │                                         │       │
│  │  Password                               │       │
│  │  [                            ] [👁]    │       │
│  │                                         │       │
│  │  [☑] Ingat Saya                        │       │
│  │                                         │       │
│  │  [       MASUK KE SISTEM       ]        │       │
│  └─────────────────────────────────────────┘       │
│                                                     │
│  © 2025 UD. Sumber Bawang Timur                    │
└─────────────────────────────────────────────────────┘
```

**Detail:**
- Background: gradient halus `#1B6B3A` → `#144F2C` atau background foto gudang/produksi dengan overlay gelap
- Card login: `bg-white`, `border-radius: 16px`, `box-shadow: var(--shadow-modal)`
- Tombol Masuk: `btn-primary` warna `--color-primary`, full-width, font-weight 600
- Ikon mata untuk toggle password visibility
- Validasi error: tampil dengan `invalid-feedback` Bootstrap + shake animation ringan
- Setelah login gagal 3x: tampilkan SweetAlert2 error dengan pesan "Terlalu banyak percobaan"

---

### 4.2 Dashboard

**Akses:** Owner (full), Karyawan (terbatas)

#### Widget Statistik (Row pertama — 4 kolom di desktop, 2 di tablet, 1 di mobile):

```
┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐
│  📦              │ │  🏭              │ │  💰              │ │  📊              │
│  Total Produksi  │ │  Stok Produk     │ │  Pengeluaran     │ │  Estimasi Laba   │
│  Hari Ini        │ │  Keseluruhan     │ │  Bulan Ini       │ │  Bulan Ini       │
│                  │ │                  │ │                  │ │                  │
│  [ANGKA BESAR]   │ │  [ANGKA BESAR]   │ │  Rp [NOMINAL]    │ │  Rp [NOMINAL]    │
│  unit/kg         │ │  unit/kg total   │ │                  │ │  [hijau/merah]   │
│  ↑ +12% vs kmrn  │ │  ⚠ 2 stok rendah│ │  ↑ vs bln lalu   │ │                  │
└──────────────────┘ └──────────────────┘ └──────────────────┘ └──────────────────┘
```

**Warna card:**
- Produksi Hari Ini: border-left `4px solid --color-primary`
- Stok Produk: border-left `4px solid --color-info`
- Pengeluaran: border-left `4px solid --color-danger`
- Estimasi Laba: border-left `4px solid --color-success` (jika positif) / `--color-danger` (jika negatif)

#### Row Kedua (2 widget besar):

```
┌──────────────────────────────────────┐  ┌───────────────────────────────────┐
│  GRAFIK PRODUKSI 30 HARI TERAKHIR   │  │  PENGELUARAN PER KATEGORI         │
│  (Chart.js — Line Chart)            │  │  (Chart.js — Doughnut Chart)      │
│                                      │  │                                   │
│  [grafik garis produksi harian]      │  │  [pie chart kategori biaya]       │
│                                      │  │  Legend di bawah                  │
└──────────────────────────────────────┘  └───────────────────────────────────┘
```

#### Row Ketiga:

```
┌─────────────────────────────────────┐  ┌──────────────────────────────────┐
│  DATA PRODUKSI TERBARU              │  │  STOK DI BAWAH MINIMUM           │
│  (Tabel 5 baris terakhir)           │  │  (List produk + progress bar)    │
│  [Lihat Semua →]                    │  │                                  │
└─────────────────────────────────────┘  └──────────────────────────────────┘
```

---

### 4.3 Halaman Input Produksi

**URL:** `/produksi/create`

```
┌─────────────────────────────────────────────────────────────────┐
│  📋 Input Data Produksi                                         │
│  Breadcrumb: Dashboard / Produksi / Tambah                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  FORM INPUT PRODUKSI                                     │  │
│  │                                                          │  │
│  │  Tanggal Produksi *        Produk *                      │  │
│  │  [📅 Date Picker     ]    [▼ Pilih Produk          ]    │  │
│  │                                                          │  │
│  │  Jumlah Produksi *         Satuan                        │  │
│  │  [          ] unit        [auto-fill dari produk  ]      │  │
│  │                                                          │  │
│  │  Jumlah Gagal/Rusak        Jumlah Bersih (otomatis)      │  │
│  │  [          ] unit        [= produksi - gagal     ]      │  │
│  │                                                          │  │
│  │  Karyawan                                                │  │
│  │  [auto-fill nama login | dropdown jika owner      ]      │  │
│  │                                                          │  │
│  │  Keterangan (opsional)                                   │  │
│  │  [                                                ]      │  │
│  │  [                                                ]      │  │
│  │                                                          │  │
│  │  [  Batal  ]              [  💾 Simpan Data  ]          │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

**Detail Interaksi:**
- Pilih Produk → Satuan auto-fill (AJAX atau data-attribute)
- Jumlah Gagal diisi → Jumlah Bersih auto-hitung secara live (JavaScript)
- Jumlah Bersih tampil sebagai `readonly` field dengan styling berbeda
- Tanggal default = hari ini
- Tombol Simpan: `btn-success` + ikon `fa-save`
- Tombol Batal: `btn-secondary` + konfirmasi SweetAlert2 jika form sudah diisi

---

### 4.4 Halaman Daftar Produksi

**URL:** `/produksi`

```
┌─────────────────────────────────────────────────────────────────┐
│  🏭 Data Produksi                                               │
│  Breadcrumb: Dashboard / Produksi                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──── FILTER ─────────────────────────────────────────────┐   │
│  │ [📅 Dari Tanggal] [📅 Sampai] [▼ Produk] [▼ Status] [🔍]│   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                 │
│  [+ Tambah Produksi]                        [Export ▼ PDF Excel]│
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │ No │ Tanggal │ Kode │ Produk │ Jml │ Gagal│ Bersih│ Kry │ St│   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │  1 │ 14 Jun  │PRD-..│ Bawang │ 100 │   5  │   95  │ Ali │ ✅│   │
│  │  2 │ 14 Jun  │PRD-..│ Bawang │  80 │   0  │   80  │ Budi│ ⏳│   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │  Aksi: [👁 Detail] [✏ Edit] [✅ Verifikasi] [🗑 Hapus]  │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                 │
│  Menampilkan 1-10 dari 47 data          [< 1 2 3 4 5 >]        │
└─────────────────────────────────────────────────────────────────┘
```

**Status Badge:**
- `draft` → `<span class="badge bg-warning text-dark">⏳ Draft</span>`
- `terverifikasi` → `<span class="badge bg-success">✅ Terverifikasi</span>`

**Tombol Aksi:**
- Edit: tampil jika status `draft` (atau jika role owner)
- Verifikasi: tampil hanya jika role owner dan status `draft`
- Hapus: konfirmasi SweetAlert2 confirmDelete, hanya owner

---

### 4.5 Halaman Input Pengeluaran

**URL:** `/pengeluaran/create`

Struktur form serupa dengan input produksi:

```
│  Tanggal *                    Kategori *
│  [📅 Date Picker        ]    [▼ Pilih Kategori       ]
│
│  Jumlah (Rp) *
│  [Rp              ]   ← font mono, format angka otomatis
│
│  Keterangan *
│  [                                                    ]
│
│  Bukti Foto (opsional)
│  [📎 Pilih File] atau [Drag & Drop area]
│  Preview thumbnail jika sudah dipilih
│
│  [  Batal  ]                     [  💾 Simpan  ]
```

**Detail:**
- Input nominal Rp: format otomatis ribuan dengan JavaScript (`1000000` → `1.000.000`)
- Upload foto: preview thumbnail langsung setelah dipilih
- Validasi: nominal harus > 0, kategori wajib dipilih

---

### 4.6 Halaman Stok

**URL:** `/stok`

```
┌─────────────────────────────────────────────────────────────────┐
│  📦 Manajemen Stok                                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  RINGKASAN STOK                                                 │
│  ┌───────────┐  ┌───────────┐  ┌───────────┐                  │
│  │ Total     │  │ Stok      │  │ Stok      │                  │
│  │ Produk    │  │ Normal    │  │ Kritis    │                  │
│  │    8      │  │    6      │  │    2      │                  │
│  └───────────┘  └───────────┘  └───────────┘                  │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Produk      │ Stok │ Min │ Status   │ Progress    │ Aksi │  │
│  ├──────────────────────────────────────────────────────────┤  │
│  │  Bawang Merah│  450 │  50 │ ✅ Normal │ ████████░░ │ [▼] │  │
│  │  Bawang Putih│   20 │  50 │ ⚠ Kritis │ ███░░░░░░░ │ [▼] │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  [📊 Lihat Histori Stok]                                       │
└─────────────────────────────────────────────────────────────────┘
```

**Progress bar stok:**
- Hijau (`bg-success`): stok > 75% dari maksimum wajar
- Kuning (`bg-warning`): stok di antara minimum dan 75%
- Merah (`bg-danger`): stok ≤ stok minimum

**Dropdown aksi per produk (owner only):**
- Kurangi Stok (modal form: jumlah, keterangan, alasan)
- Koreksi Stok (modal form: jumlah aktual, keterangan)

---

### 4.7 Halaman Laporan Produksi

**URL:** `/laporan/produksi`

```
┌─────────────────────────────────────────────────────────────────┐
│  📊 Laporan Produksi                                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  FILTER LAPORAN                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Periode: [▼ Pilih Periode ▼]  atau  Dari: [📅] s/d [📅] │  │
│  │  Produk:  [▼ Semua Produk  ]   Karyawan: [▼ Semua     ]  │  │
│  │                              [🔍 Tampilkan Laporan]       │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  RINGKASAN                                                      │
│  Total Produksi: 2.450 unit  │  Gagal: 35  │  Bersih: 2.415   │
│                                                                 │
│  GRAFIK PRODUKSI PER HARI                                       │
│  [─────────────── Chart.js Bar Chart ─────────────────────]    │
│                                                                 │
│  DETAIL DATA                                                    │
│  [Tabel lengkap dengan semua kolom]                             │
│                                                                 │
│  [📄 Export PDF]  [📊 Export Excel]                             │
└─────────────────────────────────────────────────────────────────┘
```

---

### 4.8 Halaman Laporan Laba Rugi

**URL:** `/laporan/laba-rugi`

```
┌─────────────────────────────────────────────────────────────────┐
│  💹 Laporan Laba Rugi                                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Filter Periode: [▼ Bulan] [▼ Tahun]  [Tampilkan]             │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                 LAPORAN LABA RUGI                        │  │
│  │           UD. SUMBER BAWANG TIMUR                       │  │
│  │              Periode: Juni 2025                         │  │
│  ├──────────────────────────────────────────────────────────┤  │
│  │  ESTIMASI PENDAPATAN                                     │  │
│  │  Bawang Merah   450 kg × Rp 35.000     Rp 15.750.000    │  │
│  │  Bawang Putih   200 kg × Rp 45.000     Rp  9.000.000    │  │
│  │                              ──────────────────────      │  │
│  │  TOTAL ESTIMASI PENDAPATAN             Rp 24.750.000     │  │
│  │                                                          │  │
│  │  PENGELUARAN OPERASIONAL                                 │  │
│  │  Bahan Baku                            Rp  8.500.000     │  │
│  │  Transportasi                          Rp  1.200.000     │  │
│  │  Listrik                               Rp    450.000     │  │
│  │  Perawatan Alat                        Rp    300.000     │  │
│  │  Lain-lain                             Rp    150.000     │  │
│  │                              ──────────────────────      │  │
│  │  TOTAL PENGELUARAN                     Rp 10.600.000     │  │
│  │                              ══════════════════════      │  │
│  │  LABA BERSIH (SEMENTARA)               Rp 14.150.000     │  │
│  │                              [background: hijau muda]    │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  [📄 Export PDF]  [📊 Export Excel]                             │
└─────────────────────────────────────────────────────────────────┘
```

---

### 4.9 Halaman Audit Log

**URL:** `/audit-log`

```
┌─────────────────────────────────────────────────────────────────┐
│  🔍 Audit Log Sistem                                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Filter: [📅 Dari] [📅 s/d] [▼ User] [▼ Modul] [▼ Aksi] [🔍] │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Waktu       │ User  │ Modul      │ Aksi    │ Deskripsi  │  │
│  ├──────────────────────────────────────────────────────────┤  │
│  │  14 Jun 09:12│ Budi  │ Produksi   │ Create  │ Input PRD- │  │
│  │  14 Jun 09:45│ Owner │ Pengeluaran│ Verify  │ Verif EXP- │  │
│  │  14 Jun 10:01│ Ali   │ Auth       │ Login   │ Login sukses│  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  Klik baris → Modal detail (data lama vs data baru)            │
└─────────────────────────────────────────────────────────────────┘
```

---

## 5. KOMPONEN UI REUSABLE

### 5.1 Stat Card (Dashboard Widget)

```html
<div class="card stat-card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <p class="text-muted small mb-1">Total Produksi Hari Ini</p>
                <h3 class="fw-bold mb-0">245 <small class="fs-6 text-muted">kg</small></h3>
                <small class="text-success">
                    <i class="fas fa-arrow-up me-1"></i>+12% vs kemarin
                </small>
            </div>
            <div class="stat-icon bg-primary-light rounded-circle p-3">
                <i class="fas fa-boxes fa-lg text-primary"></i>
            </div>
        </div>
    </div>
    <div class="card-footer bg-transparent border-0 py-1">
        <div class="progress" style="height: 3px;">
            <div class="progress-bar bg-primary" style="width: 72%"></div>
        </div>
    </div>
</div>
```

**CSS:**
```css
.stat-card {
    border-left: 4px solid var(--color-primary) !important;
    transition: transform .15s ease, box-shadow .15s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,.10) !important;
}
.stat-icon {
    width: 52px; height: 52px;
    display: flex; align-items: center; justify-content: center;
}
.bg-primary-light { background-color: var(--color-primary-light); }
```

---

### 5.2 Status Badge

```html
<!-- Draft -->
<span class="badge rounded-pill bg-warning text-dark px-3 py-1">
    <i class="fas fa-clock me-1"></i> Draft
</span>

<!-- Terverifikasi -->
<span class="badge rounded-pill bg-success px-3 py-1">
    <i class="fas fa-check-circle me-1"></i> Terverifikasi
</span>

<!-- Stok Kritis -->
<span class="badge rounded-pill bg-danger px-3 py-1">
    <i class="fas fa-exclamation-triangle me-1"></i> Stok Kritis
</span>
```

---

### 5.3 Tombol Aksi Tabel

```html
<!-- Grup tombol aksi dalam tabel -->
<div class="btn-group btn-group-sm" role="group">
    <!-- Detail -->
    <a href="{{ route('produksi.show', $item->id) }}"
       class="btn btn-outline-info" title="Detail">
        <i class="fas fa-eye"></i>
    </a>
    <!-- Edit — hanya jika draft atau owner -->
    @if($item->status === 'draft' || auth()->user()->role === 'owner')
    <a href="{{ route('produksi.edit', $item->id) }}"
       class="btn btn-outline-warning" title="Edit">
        <i class="fas fa-pen"></i>
    </a>
    @endif
    <!-- Verifikasi — hanya owner, hanya draft -->
    @if(auth()->user()->role === 'owner' && $item->status === 'draft')
    <button type="button" class="btn btn-outline-success"
            title="Verifikasi"
            onclick="SwalHelper.confirmVerify(() => verifikasi({{ $item->id }}))">
        <i class="fas fa-check-circle"></i>
    </button>
    @endif
    <!-- Hapus — hanya owner -->
    @if(auth()->user()->role === 'owner')
    <button type="button" class="btn btn-outline-danger"
            title="Hapus"
            onclick="SwalHelper.confirmDelete(() => hapus({{ $item->id }}))">
        <i class="fas fa-trash"></i>
    </button>
    @endif
</div>
```

---

### 5.4 Page Header (Konsisten di Setiap Halaman)

```html
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="fas fa-industry me-2 text-primary"></i>Data Produksi
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Produksi</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('produksi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Produksi
        </a>
    </div>
</div>
```

---

### 5.5 Tabel Data (Konsisten)

```css
.table-custom thead {
    background-color: var(--color-primary);
    color: #ffffff;
    font-size: var(--font-size-sm);
    font-weight: var(--font-semibold);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.table-custom tbody tr {
    transition: background-color .1s ease;
}
.table-custom tbody tr:hover {
    background-color: rgba(27,107,58,.04);
}
.table-custom td {
    vertical-align: middle;
    font-size: var(--font-size-sm);
}
/* Nomor dan kode produksi pakai font mono */
.table-custom .col-kode,
.table-custom .col-nominal {
    font-family: var(--font-mono);
    font-size: var(--font-size-xs);
}
```

---

### 5.6 Filter/Search Bar

```html
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" id="form-filter">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Sampai</label>
                    <input type="date" name="sampai" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Produk</label>
                    <select name="produk_id" class="form-select form-select-sm">
                        <option value="">Semua Produk</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="terverifikasi">Terverifikasi</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
```

---

## 6. SIDEBAR

```css
/* Sidebar */
.sidebar {
    width: 260px;
    min-height: 100vh;
    background-color: var(--color-sidebar-bg);
    position: fixed;
    top: 0; left: 0;
    z-index: 1000;
    overflow-y: auto;
    transition: transform .25s ease;
}

.sidebar .brand {
    padding: 20px 20px 16px;
    border-bottom: 1px solid rgba(255,255,255,.08);
}

.sidebar .brand-name {
    color: #ffffff;
    font-weight: var(--font-bold);
    font-size: var(--font-size-md);
    line-height: 1.3;
}

.sidebar .brand-sub {
    color: var(--color-sidebar-text);
    font-size: var(--font-size-xs);
}

.sidebar .user-info {
    padding: 16px 20px;
    border-bottom: 1px solid rgba(255,255,255,.08);
    display: flex; align-items: center; gap: 12px;
}

.sidebar .user-name {
    color: #ffffff;
    font-weight: var(--font-semibold);
    font-size: var(--font-size-sm);
}

.sidebar .user-role {
    display: inline-block;
    padding: 2px 10px;
    border-radius: var(--radius-pill);
    font-size: var(--font-size-xs);
    font-weight: var(--font-semibold);
    text-transform: uppercase;
    letter-spacing: .5px;
}
.role-owner    { background: var(--color-primary); color: #fff; }
.role-karyawan { background: var(--color-accent);  color: #fff; }

/* Menu group label */
.sidebar .menu-label {
    padding: 12px 20px 4px;
    color: rgba(178,186,187,.5);
    font-size: 10px;
    font-weight: var(--font-bold);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Menu item */
.sidebar .nav-link {
    padding: 9px 20px;
    color: var(--color-sidebar-text);
    font-size: var(--font-size-sm);
    font-weight: var(--font-medium);
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 0;
    transition: all .15s ease;
    position: relative;
}

.sidebar .nav-link:hover {
    color: #ffffff;
    background: rgba(255,255,255,.06);
}

.sidebar .nav-link.active {
    color: #ffffff;
    background: var(--color-sidebar-active);
    font-weight: var(--font-semibold);
}

.sidebar .nav-link.active::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--color-accent);
    border-radius: 0 2px 2px 0;
}

.sidebar .nav-link i {
    width: 18px;
    text-align: center;
    font-size: 14px;
    opacity: .8;
}

/* Badge notifikasi di sidebar */
.sidebar .nav-link .badge-count {
    margin-left: auto;
    background: var(--color-danger);
    color: #fff;
    font-size: 10px;
    font-weight: var(--font-bold);
    padding: 2px 6px;
    border-radius: var(--radius-pill);
}
```

---

## 7. AREA KONTEN UTAMA

```css
.main-content {
    margin-left: 260px;
    min-height: 100vh;
    background-color: var(--color-body-bg);
    transition: margin-left .25s ease;
}

.content-wrapper {
    padding: 24px;
}

/* Topbar */
.topbar {
    background: #ffffff;
    border-bottom: 1px solid var(--color-border);
    padding: 12px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 999;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}

/* Card umum */
.card {
    border: none;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-card);
    margin-bottom: var(--spacing-lg);
}

.card-header {
    background: transparent;
    border-bottom: 1px solid var(--color-border);
    padding: 16px 20px;
    font-weight: var(--font-semibold);
    font-size: var(--font-size-md);
}

/* Responsive */
@media (max-width: 767.98px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.show { transform: translateX(0); }
    .main-content { margin-left: 0; }
}
```

---

## 8. POLA INTERAKSI SWEETALERT2

Semua konfirmasi dan notifikasi menggunakan `SwalHelper` dari `public/js/sweetalert-helpers.js`.

### Pola Penggunaan:

| Situasi | Method | Tombol Trigger |
|---|---|---|
| Data berhasil disimpan | `SwalHelper.success()` | Auto via flash session |
| Data gagal disimpan | `SwalHelper.error()` | Auto via flash session |
| Konfirmasi hapus data | `SwalHelper.confirmDelete()` | Tombol hapus di tabel |
| Konfirmasi logout | `SwalHelper.confirmLogout()` | Tombol logout sidebar |
| Konfirmasi verifikasi | `SwalHelper.confirmVerify()` | Tombol verifikasi di tabel |
| Konfirmasi kurangi stok | `SwalHelper.confirmVerify()` | Tombol kurangi stok |
| Peringatan form belum tersimpan | `SwalHelper.warning()` | Tombol batal form |

---

## 9. ATURAN FORM

### 9.1 Validasi Client-Side (Feedback Bootstrap)
```html
<div class="mb-3">
    <label class="form-label fw-semibold small">
        Jumlah Produksi <span class="text-danger">*</span>
    </label>
    <input type="number"
           name="jumlah_produksi"
           class="form-control @error('jumlah_produksi') is-invalid @enderror"
           value="{{ old('jumlah_produksi') }}"
           min="1"
           required>
    @error('jumlah_produksi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```

### 9.2 Readonly / Disabled Field
```html
<!-- Jumlah bersih (auto-calc) -->
<div class="mb-3">
    <label class="form-label fw-semibold small">Jumlah Bersih (Otomatis)</label>
    <input type="text"
           id="jumlah_bersih_display"
           class="form-control bg-light fw-semibold text-success"
           readonly
           placeholder="Terisi otomatis">
</div>
```

### 9.3 Format Input Nominal
```javascript
// Auto format ribuan saat mengetik di input nominal
document.querySelectorAll('.input-nominal').forEach(el => {
    el.addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '');
        this.value = v ? parseInt(v).toLocaleString('id-ID') : '';
        // Simpan nilai asli ke hidden input
        document.getElementById(this.dataset.target).value = v;
    });
});
```

---

## 10. STRUKTUR VIEW BLADE

```
resources/views/
│
├── layouts/
│   ├── app.blade.php           ← Layout utama (sidebar + topbar + konten)
│   ├── auth.blade.php          ← Layout halaman login (tanpa sidebar)
│   ├── _sidebar.blade.php      ← Komponen sidebar
│   ├── _navbar.blade.php       ← Komponen topbar
│   └── _footer.blade.php       ← Footer
│
├── auth/
│   └── login.blade.php
│
├── dashboard/
│   └── index.blade.php
│
├── produksi/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
│
├── pengeluaran/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
│
├── stok/
│   ├── index.blade.php
│   └── histori.blade.php
│
├── master/
│   ├── produk/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── satuan/
│   ├── kategori/
│   └── user/
│
├── laporan/
│   ├── produksi.blade.php
│   ├── stok.blade.php
│   ├── pengeluaran.blade.php
│   └── laba-rugi.blade.php
│
├── audit-log/
│   ├── index.blade.php
│   └── show.blade.php
│
└── errors/
    ├── 403.blade.php
    ├── 404.blade.php
    └── 500.blade.php
```

---

## 11. CDN FINAL (URUTAN LOADING)

### Di `<head>` (CSS):
```html
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<!-- Bootstrap 5.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6.5 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
```

### Di atas `</body>` (JS):
```html
<!-- Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Chart.js (hanya di halaman dashboard & laporan) -->
@stack('scripts-cdn')
<!-- Custom JS -->
<script src="{{ asset('js/sweetalert-helpers.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
```

---

## 12. IKON FONT AWESOME PER MODUL

| Modul / Elemen | Ikon FA |
|---|---|
| Dashboard | `fa-chart-line` |
| Produksi | `fa-industry` |
| Pengeluaran | `fa-money-bill-wave` |
| Stok | `fa-boxes-stacked` |
| Laporan Produksi | `fa-clipboard-list` |
| Laporan Stok | `fa-warehouse` |
| Laporan Pengeluaran | `fa-file-invoice-dollar` |
| Laporan Laba Rugi | `fa-chart-pie` |
| Master Produk | `fa-box` |
| Master Satuan | `fa-ruler` |
| Master Kategori | `fa-tags` |
| Master User | `fa-users` |
| Audit Log | `fa-shield-halved` |
| Tambah data | `fa-plus` |
| Edit | `fa-pen` |
| Detail | `fa-eye` |
| Hapus | `fa-trash` |
| Verifikasi | `fa-check-circle` |
| Export PDF | `fa-file-pdf` |
| Export Excel | `fa-file-excel` |
| Logout | `fa-sign-out-alt` |
| Profil | `fa-user-circle` |
| Notifikasi | `fa-bell` |
| Stok kritis | `fa-exclamation-triangle` |
| Stok normal | `fa-check` |
| Filter | `fa-filter` |
| Reset filter | `fa-redo` |

---

*File ini merupakan dokumen desain UI/UX resmi untuk proyek Sistem Informasi Produksi dan Keuangan UD. Sumber Bawang Timur. Semua warna, tipografi, komponen, dan pola interaksi harus mengikuti panduan ini agar antarmuka konsisten di seluruh modul.*
