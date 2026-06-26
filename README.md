# Sistem Informasi Produksi dan Keuangan Terintegrasi (Profin-Web)
### UD. Sumber Bawang Timur

![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-%3E%3D_8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

---

## 📖 Tentang Sistem

**Profin-Web** adalah Sistem Informasi Produksi dan Keuangan Terintegrasi yang dibangun khusus untuk **UD. Sumber Bawang Timur**. Sistem ini menghubungkan seluruh aktivitas pencatatan produksi harian dengan arus transaksi keuangan secara otomatis. Setiap input hasil produksi dan pengeluaran operasional di lapangan langsung terakumulasi secara *real-time* ke dalam buku besar dan laporan keuangan usaha.

### Masalah yang Diselesaikan:
- ❌ Pencatatan manual menggunakan buku tulis fisik yang rawan hilang dan rusak.
- ❌ Data produksi hasil panen/pengemasan tidak terhubung langsung dengan biaya operasional.
- ❌ Proses rekapitulasi laba rugi memakan waktu lama dan sering terjadi selisih perhitungan.
- ❌ Ketidaktahuan owner terhadap posisi stok produk jadi di gudang secara *real-time*.
- ❌ Tidak ada jejak rekam (*audit trail*) ketika terjadi perubahan atau penghapusan transaksi penting.

---

## 🌟 Fitur Utama

### 1. 🔐 Modul Autentikasi & Hak Akses
- **Multi-Role User**: Pemisahan hak akses yang ketat antara **Owner (Pemilik Usaha)** dan **Karyawan Lapangan**.
- **Perlindungan Route & Aksi**: Karyawan hanya dapat melakukan input dan edit draf, sedangkan verifikasi dan master data dikunci khusus untuk Owner.
- **Profil Pengguna**: Pembaruan biodata dan ubah kata sandi secara aman.

### 2. 📦 Modul Master Data (Khusus Owner)
- **Master Produk**: Kelola produk jadi agribisnis (Bawang Merah Super, Sedang, Kecil Sortiran C, dll.) beserta harga estimasi dan batas stok minimum.
- **Master Satuan**: Standarisasi satuan takaran baku (kg, karung, ikat, pack, liter).
- **Master Kategori Pengeluaran**: Pengelompokan biaya operasional (Bahan Baku, Transportasi, Listrik & Air, Perawatan Alat, dll.).
- **Manajemen Pengguna**: Tambah, edit, nonaktifkan akun karyawan, serta fitur *Reset Password*.

### 3. ⚙️ Modul Produksi
- **Pencatatan Hasil Produksi**: Karyawan menginput jumlah produksi kotor dan jumlah produk gagal/reject.
- **Kalkulasi Otomatis**: Sistem menghitung **Jumlah Bersih = Produksi - Gagal** secara langsung di UI maupun di backend.
- **Auto-Generate Kode**: Kode batch produksi otomatis (Contoh: `PRD-20260626-0001`).
- **Verifikasi Dua Tingkat**: Status transaksi berawal dari **Draft (Kuning)** $\rightarrow$ diverifikasi oleh Owner menjadi **Verified (Hijau)**. Penambahan stok gudang hanya terjadi setelah diverifikasi.

### 4. 📊 Modul Manajemen Stok (Khusus Owner)
- **Monitoring Real-Time**: Status stok otomatis dipantau (*Aman*, *Rendah*, atau *Kritis/Habis*).
- **Koreksi & Pengurangan Stok**: Catat penyesuaian barang susut, rusak di gudang, atau sampel gratis dengan menyertakan alasan.
- **Kartu Stok / Histori Lengkap**: Mencatat setiap pergerakan barang masuk (dari produksi verified) dan barang keluar.

### 5. 💸 Modul Pengeluaran Operasional
- **Input Biaya Lapangan**: Catat pengeluaran harian lengkap dengan unggah bukti nota/kwitansi (format gambar/PDF).
- **Auto-Generate Kode Transaksi**: Format terstruktur `EXP-20260626-0001`.
- **Verifikasi Pengeluaran**: Owner meninjau bukti nota sebelum menyetujui pengeluaran.

### 6. 📑 Modul Laporan Eksekutif (Khusus Owner)
- **Laporan Produksi**: Rekapitulasi total efisiensi dan tingkat kegagalan produksi.
- **Laporan Posisi Stok**: Nilai evaluasi aset barang jadi di gudang saat ini.
- **Laporan Pengeluaran**: Rincian biaya operasional berdasarkan rentang tanggal dan kategori.
- **Laporan Laba Rugi**: Konsolidasi estimasi pendapatan produksi dikurangi total pengeluaran operasional.
- **Export PDF & Excel**: Unduh laporan resmi berformat PDF elegan (via `barryvdh/laravel-dompdf`) dan spreadsheet Excel (`maatwebsite/excel`).

### 7. 📈 Modul Dashboard Eksekutif
- **Dashboard Owner**: Widget indikator kunci (Total Produksi Bersih, Total Pengeluaran, Nilai Stok Gudang, Laba Bersih Estimasi), grafik tren 7 hari terakhir (via Chart.js), serta peringatan stok minimum.
- **Dashboard Karyawan**: Tampilan simpel berisi statistik kontribusi input produksi dan pengeluaran hari ini.

### 8. 🛡️ Modul Audit Trail (Log Aktivitas)
- Rekam jejak transparan atas setiap penambahan, perubahan, penghapusan, verifikasi, dan ekspor data beserta IP Address dan timestamp.

---

## 🎨 Design System & UI/UX

Sistem ini menganut filosofi **Simpel, Fungsional, dan Konsisten**:
- **Palet Warna Agribisnis**: Dominasi Hijau Tua (`#1B6B3A`) merepresentasikan kesegaran produk pertanian, dipadu aksen Amber (`#F59E0B`).
- **Tipografi Modern**: Memadukan font *Plus Jakarta Sans* untuk keterbacaan antarmuka dan *JetBrains Mono* untuk nominal angka serta kode batch.
- **Feedback Interaktif**: Konfirmasi aksi vital (Hapus, Verifikasi) dikelola secara halus menggunakan animasi **SweetAlert2**.
- **Responsif Total**: Dukungan penuh *Mobile-First Bootstrap 5* sehingga karyawan bisa menginput data langsung dari HP di area gudang.

---

## 🚀 Panduan Instalasi & Menjalankan Lokal

### Prasyarat
- PHP >= 8.2
- MySQL Server >= 8.0
- Composer
- Node.js & NPM (untuk Vite aset bundling)

### Langkah Instalasi

1. **Clone Repositori & Masuk Direktori**
   ```bash
   git clone https://github.com/ardhikaxx/profin-web.git
   cd profin-web
   ```

2. **Install Dependensi PHP & JS**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file konfigurasi environment:
   ```bash
   cp .env.example .env
   ```
   Sesuaikan kredensial database di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=profin_web
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi & Seeding Database**
   Jalankan perintah ini untuk membuat struktur tabel sekaligus mengisi data awal (akun login, satuan, produk, kategori):
   ```bash
   php artisan migrate --seed
   ```

6. **Build Aset Front-End & Jalankan Server**
   ```bash
   npm run build
   php artisan serve
   ```
   Akses aplikasi melalui browser di `http://localhost:8000`.

---

## 🔑 Akun Login Default (Seeder)

Gunakan kredensial berikut untuk masuk ke dalam sistem setelah melakukan proses seeding:

| Role | Username | Email | Password | Keterangan |
|---|---|---|---|---|
| **Owner** | `admin` | `admin@gmail.com` | `bawang` | Akses penuh seluruh modul, verifikasi, laporan, dan master data |
| **Karyawan** | `ahmad_prod` | `ahmad@gmail.com` | `bawang` | Input produksi dan pengeluaran harian |
| **Karyawan** | `siti_admin` | `siti@gmail.com` | `bawang` | Input produksi dan pengeluaran harian |

---

## 📄 Lisensi

Sistem Informasi Produksi dan Keuangan Terintegrasi UD. Sumber Bawang Timur dikembangkan secara hak milik (*Proprietary*) untuk operasional internal usaha.
