<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Produksi\ProduksiController;
use App\Http\Controllers\Stok\StokController;
use App\Http\Controllers\Pengeluaran\PengeluaranController;
use App\Http\Controllers\Master\ProdukController;
use App\Http\Controllers\Master\SatuanController;
use App\Http\Controllers\Master\KategoriPengeluaranController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Laporan\LaporanProduksiController;
use App\Http\Controllers\Laporan\LaporanStokController;
use App\Http\Controllers\Laporan\LaporanPengeluaranController;
use App\Http\Controllers\Laporan\LaporanLabaRugiController;
use App\Http\Controllers\AuditLog\AuditLogController;

// Auth Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [AuthController::class, 'profil'])->name('profil');
    Route::put('/profil', [AuthController::class, 'updateProfil'])->name('profil.update');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Produksi (Karyawan + Owner)
    Route::resource('produksi', ProduksiController::class);
    Route::patch('produksi/{produksi}/verifikasi', [ProduksiController::class, 'verifikasi'])
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
    Route::patch('pengeluaran/{pengeluaran}/verifikasi', [PengeluaranController::class, 'verifikasi'])
         ->name('pengeluaran.verifikasi')->middleware('role:owner');

    // Master Data (Owner only)
    Route::middleware('role:owner')->prefix('master')->name('master.')->group(function () {
        Route::resource('produk', ProdukController::class);
        Route::resource('satuan', SatuanController::class);
        Route::resource('kategori', KategoriPengeluaranController::class);
        Route::resource('user', UserController::class);
        Route::patch('user/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
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
        Route::get('audit-log/{audit_log}', [AuditLogController::class, 'show'])->name('audit-log.show');
    });
});
