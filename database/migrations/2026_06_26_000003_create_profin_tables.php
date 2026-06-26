<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satuans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan', 50);
            $table->string('keterangan', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk', 20)->unique();
            $table->string('nama_produk', 100);
            $table->foreignId('satuan_id')->constrained('satuans')->onDelete('cascade');
            $table->decimal('harga_estimasi', 15, 2)->nullable()->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('kategori_pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('produksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produksi', 30)->unique();
            $table->date('tanggal_produksi');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->integer('jumlah_produksi');
            $table->integer('jumlah_gagal')->default(0);
            $table->integer('jumlah_bersih')->virtualAs('jumlah_produksi - jumlah_gagal');
            $table->foreignId('satuan_id')->constrained('satuans')->onDelete('cascade');
            $table->foreignId('karyawan_id')->constrained('users')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'terverifikasi'])->default('draft');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('stoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->unique()->constrained('produks')->onDelete('cascade');
            $table->integer('jumlah_stok')->default(0);
            $table->timestamps();
        });

        Schema::create('stok_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->enum('jenis', ['masuk', 'keluar', 'koreksi']);
            $table->integer('jumlah');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->string('referensi_tipe', 50)->nullable();
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique();
            $table->date('tanggal_pengeluaran');
            $table->foreignId('kategori_pengeluaran_id')->constrained('kategori_pengeluarans')->onDelete('cascade');
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('bukti_foto', 255)->nullable();
            $table->foreignId('karyawan_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'terverifikasi'])->default('draft');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama_user', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('modul', 50);
            $table->string('aksi', 50);
            $table->text('deskripsi')->nullable();
            $table->json('data_lama')->nullable();
            $table->json('data_baru')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('pengeluarans');
        Schema::dropIfExists('stok_histories');
        Schema::dropIfExists('stoks');
        Schema::dropIfExists('produksis');
        Schema::dropIfExists('kategori_pengeluarans');
        Schema::dropIfExists('produks');
        Schema::dropIfExists('satuans');
    }
};
