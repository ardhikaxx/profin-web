<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Satuan;
use App\Models\Produk;
use App\Models\KategoriPengeluaran;
use App\Models\Produksi;
use App\Models\Stok;
use App\Models\Pengeluaran;
use App\Services\StokService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users (Owner & Karyawan)
        $owner = User::create([
            'name'      => 'admin',
            'username'  => 'admin',
            'email'     => 'admin@gmail.com',
            'password'  => Hash::make('sumberbawang'),
            'role'      => 'owner',
            'is_active' => true,
        ]);

        $karyawan1 = User::create([
            'name'      => 'Ahmad Sodikin',
            'username'  => 'ahmad_prod',
            'email'     => 'ahmad@gmail.com',
            'password'  => Hash::make('sumberbawang'),
            'role'      => 'karyawan',
            'is_active' => true,
        ]);

        $karyawan2 = User::create([
            'name'      => 'Siti Rahmawati',
            'username'  => 'siti_admin',
            'email'     => 'siti@gmail.com',
            'password'  => Hash::make('sumberbawang'),
            'role'      => 'karyawan',
            'is_active' => true,
        ]);

        $karyawan3 = User::create([
            'name'      => 'Budi Santoso',
            'username'  => 'budi_ops',
            'email'     => 'budi@gmail.com',
            'password'  => Hash::make('sumberbawang'),
            'role'      => 'karyawan',
            'is_active' => true,
        ]);

        // 2. Seed Satuan
        $satKg = Satuan::create(['nama_satuan' => 'kg', 'keterangan' => 'Kilogram (Satuan berat baku agribisnis)']);
        $satKarung = Satuan::create(['nama_satuan' => 'karung', 'keterangan' => 'Karung jaring kapasitas 25kg / 50kg']);
        $satIkat = Satuan::create(['nama_satuan' => 'ikat', 'keterangan' => 'Ikat basah pasca panen ladang']);
        $satPack = Satuan::create(['nama_satuan' => 'pack', 'keterangan' => 'Kemasan plastik standing pouch 250gr']);
        $satLiter = Satuan::create(['nama_satuan' => 'liter', 'keterangan' => 'Satuan volume cairan (minyak/bbm)']);

        // 3. Seed Kategori Pengeluaran
        $katBahan = KategoriPengeluaran::create(['nama_kategori' => 'Bahan Baku', 'deskripsi' => 'Pembelian bawang merah mentah dari petani, minyak goreng, bumbu pengolah']);
        $katTrans = KategoriPengeluaran::create(['nama_kategori' => 'Transportasi', 'deskripsi' => 'Ongkos angkut muatan truk borongan, bensin pickup pengiriman barang']);
        $katListrik = KategoriPengeluaran::create(['nama_kategori' => 'Listrik & Air', 'deskripsi' => 'Tagihan listrik PLN gudang pengemasan dan air bersih pencucian']);
        $katAlat = KategoriPengeluaran::create(['nama_kategori' => 'Perawatan Alat', 'deskripsi' => 'Service mesin pengiris otomatis, ganti oli dinamo, perawatan timbangan digital']);
        $katLain = KategoriPengeluaran::create(['nama_kategori' => 'Lain-lain', 'deskripsi' => 'Konsumsi harian lembur karyawan, plastik kemasan, kebersihan gudang']);

        // 4. Seed Produk
        $prodSuper = Produk::create([
            'kode_produk'    => 'BMS-01',
            'nama_produk'    => 'Bawang Merah Super Sortiran A',
            'satuan_id'      => $satKg->id,
            'harga_estimasi' => 38500,
            'stok_minimum'   => 150,
            'deskripsi'      => 'Bawang merah ukuran besar sortiran kualitas super/ekspor warna merah segar kencang.',
            'is_active'      => true,
        ]);

        $prodSedang = Produk::create([
            'kode_produk'    => 'BMS-02',
            'nama_produk'    => 'Bawang Merah Sedang Sortiran B',
            'satuan_id'      => $satKg->id,
            'harga_estimasi' => 32000,
            'stok_minimum'   => 200,
            'deskripsi'      => 'Bawang merah ukuran sedang standar pasar tradisional daerah Jawa Timur.',
            'is_active'      => true,
        ]);

        $prodPutihKating = Produk::create([
            'kode_produk'    => 'BPK-01',
            'nama_produk'    => 'Bawang Putih Kating Bersih',
            'satuan_id'      => $satKg->id,
            'harga_estimasi' => 42000,
            'stok_minimum'   => 100,
            'deskripsi'      => 'Bawang putih kating siung padat aroma tajam tanpa bonggol kotor.',
            'is_active'      => true,
        ]);

        $prodPutihHonan = Produk::create([
            'kode_produk'    => 'BPH-01',
            'nama_produk'    => 'Bawang Putih Honan Siung',
            'satuan_id'      => $satKg->id,
            'harga_estimasi' => 34000,
            'stok_minimum'   => 120,
            'deskripsi'      => 'Bawang putih honan impor siung besar cocok untuk katering dan rumah makan.',
            'is_active'      => true,
        ]);

        $prodGoreng = Produk::create([
            'kode_produk'    => 'BGP-01',
            'nama_produk'    => 'Bawang Goreng Premium Pouch',
            'satuan_id'      => $satPack->id,
            'harga_estimasi' => 28000,
            'stok_minimum'   => 50,
            'deskripsi'      => 'Bawang merah goreng asli 100% renyah tanpa campuran tepung kemasan pouch 250 gram.',
            'is_active'      => true,
        ]);

        // 5. Seed Produksi & Stok Terintegrasi (30 hari terakhir)
        $stokService = app(StokService::class);
        $produksList = [$prodSuper, $prodSedang, $prodPutihKating, $prodPutihHonan, $prodGoreng];
        $karyawanList = [$karyawan1, $karyawan2, $karyawan3];

        $prdCounter = [];

        // Pastikan setiap produk diproduksi secara konsisten setiap beberapa hari
        foreach ($produksList as $p) {
            // Beri stok dasar produksi awal 30 hari lalu
            $tglAwal = now()->subDays(30)->format('Y-m-d');
            $dateStr = str_replace('-', '', $tglAwal);
            $prdCounter[$dateStr] = ($prdCounter[$dateStr] ?? 0) + 1;
            $kodePrd = "PRD-{$dateStr}-" . str_pad($prdCounter[$dateStr], 3, '0', STR_PAD_LEFT);

            $prodAwal = ($p->id === $prodGoreng->id) ? 150 : 500;
            $produksi = Produksi::create([
                'kode_produksi'    => $kodePrd,
                'tanggal_produksi' => $tglAwal,
                'produk_id'        => $p->id,
                'jumlah_produksi'  => $prodAwal,
                'jumlah_gagal'     => 10,
                'satuan_id'        => $p->satuan_id,
                'karyawan_id'      => $karyawan1->id,
                'keterangan'       => "Produksi kloter awal bulan",
                'status'           => 'terverifikasi',
                'verified_by'      => $owner->id,
                'verified_at'      => now()->subDays(30)->addHours(4),
            ]);

            $stokService->tambahStok($p->id, $prodAwal - 10, 'Produksi', $produksi->id, $karyawan1->id, "Stok awal produksi");
        }

        for ($i = 29; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $dateStr = str_replace('-', '', $tanggal);
            $prdCounter[$dateStr] = $prdCounter[$dateStr] ?? 0;

            // Buat 1 sampai 3 aktivitas produksi per hari
            $jmlAktivitas = rand(1, 3);
            for ($j = 0; $j < $jmlAktivitas; $j++) {
                $prdCounter[$dateStr]++;
                $kodePrd = "PRD-{$dateStr}-" . str_pad($prdCounter[$dateStr], 3, '0', STR_PAD_LEFT);
                
                $chosenProd = $produksList[array_rand($produksList)];
                $chosenKaryawan = $karyawanList[array_rand($karyawanList)];
                
                $totalProd = ($chosenProd->id === $prodGoreng->id) ? rand(30, 90) : rand(120, 350);
                $gagal     = rand(0, (int)($totalProd * 0.04)); // 0% - 4% gagal sortiran

                $isVerified = ($i > 2) ? true : (rand(0, 1) == 1); // data lama sudah terverifikasi

                $produksi = Produksi::create([
                    'kode_produksi'    => $kodePrd,
                    'tanggal_produksi' => $tanggal,
                    'produk_id'        => $chosenProd->id,
                    'jumlah_produksi'  => $totalProd,
                    'jumlah_gagal'     => $gagal,
                    'satuan_id'        => $chosenProd->satuan_id,
                    'karyawan_id'      => $chosenKaryawan->id,
                    'keterangan'       => "Panen & pengolahan harian kloter " . ($j+1),
                    'status'           => $isVerified ? 'terverifikasi' : 'draft',
                    'verified_by'      => $isVerified ? $owner->id : null,
                    'verified_at'      => $isVerified ? now()->subDays($i)->addHours(6) : null,
                ]);

                // Tambah ke sistem stok
                $bersih = $totalProd - $gagal;
                $stokService->tambahStok(
                    $chosenProd->id,
                    $bersih,
                    'Produksi',
                    $produksi->id,
                    $chosenKaryawan->id,
                    "Seeder Input Produksi {$kodePrd}"
                );
            }
        }

        // Simulasi penjualan/distribusi supaya ada barang stok kritis (Bawang Goreng min 50 -> sisa 20)
        $stokGoreng = Stok::where('produk_id', $prodGoreng->id)->value('jumlah_stok') ?? 0;
        if ($stokGoreng > 20) {
            $kurang = $stokGoreng - 20;
            $stokService->kurangiStok($prodGoreng->id, $kurang, 'Penjualan', null, $owner->id, 'Distribusi pesanan grosir oleh Owner ke Surabaya');
        }

        // Bawang Putih Kating min 100 -> sisa 45
        $stokKating = Stok::where('produk_id', $prodPutihKating->id)->value('jumlah_stok') ?? 0;
        if ($stokKating > 45) {
            $kurang = $stokKating - 45;
            $stokService->kurangiStok($prodPutihKating->id, $kurang, 'Penjualan', null, $owner->id, 'Pengiriman ke pasar induk Malang');
        }

        // 6. Seed Pengeluaran Operasional (30 hari terakhir)
        $expCounter = [];
        $kategoriList = [$katBahan, $katTrans, $katListrik, $katAlat, $katLain];

        for ($i = 29; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $dateStr = str_replace('-', '', $tanggal);
            $expCounter[$dateStr] = 0;

            $jmlExp = rand(1, 2);
            for ($j = 0; $j < $jmlExp; $j++) {
                $expCounter[$dateStr]++;
                $kodeExp = "EXP-{$dateStr}-" . str_pad($expCounter[$dateStr], 3, '0', STR_PAD_LEFT);
                
                $chosenKat = $kategoriList[array_rand($kategoriList)];
                $chosenKaryawan = $karyawanList[array_rand($karyawanList)];

                $nominal = match($chosenKat->id) {
                    $katBahan->id   => rand(2500000, 7500000),
                    $katTrans->id   => rand(350000, 1200000),
                    $katListrik->id => rand(450000, 950000),
                    $katAlat->id    => rand(150000, 600000),
                    default         => rand(50000, 250000),
                };

                $ket = match($chosenKat->id) {
                    $katBahan->id   => 'Pembelian borongan bawang merah ladang Probolinggo',
                    $katTrans->id   => 'Sewa pickup colt diesel kirim barang pesanan grosir',
                    $katListrik->id => 'Pembayaran tagihan listrik PLN bulanan & token air mesin',
                    $katAlat->id    => 'Service pisau pengiris dan ganti oli dinamo pengering',
                    default         => 'Uang makan lembur packing pesanan akhir pekan',
                };

                $isVerified = ($i > 1);

                Pengeluaran::create([
                    'kode_transaksi'          => $kodeExp,
                    'tanggal_pengeluaran'     => $tanggal,
                    'kategori_pengeluaran_id' => $chosenKat->id,
                    'jumlah'                  => $nominal,
                    'keterangan'              => $ket,
                    'karyawan_id'             => $chosenKaryawan->id,
                    'status'                  => $isVerified ? 'terverifikasi' : 'draft',
                    'verified_by'             => $isVerified ? $owner->id : null,
                    'verified_at'             => $isVerified ? now()->subDays($i)->addHours(4) : null,
                ]);
            }
        }
    }
}
