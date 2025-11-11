<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel pengaduan.
     */
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id('id_pengaduan');
            $table->unsignedBigInteger('id_masyarakat');
            $table->dateTime('tanggal_pengaduan')->default(now()); // auto isi waktu saat insert
            $table->text('isi_laporan');
            $table->string('foto')->nullable();
            // ✅ Sesuaikan enum agar sesuai controller & logika aplikasi
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->timestamps();

            // Relasi ke tabel masyarakat
            $table->foreign('id_masyarakat')
                ->references('id_masyarakat')
                ->on('masyarakat')
                ->onDelete('cascade');
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
