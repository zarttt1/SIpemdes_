<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanggapan', function (Blueprint $table) {
            // 1. Primary Key
            $table->id('id_tanggapan');

            // 2. Foreign Keys (Kolomnya saja)
            $table->unsignedBigInteger('id_pengaduan');
            $table->unsignedBigInteger('id_petugas');

            // 3. Data Utama
            $table->dateTime('tanggal_tanggapan'); // Pilih satu: dateTime atau date
            $table->text('isi_tanggapan');
            
            // Opsional: Kolom status biasanya ada di tabel 'pengaduan', bukan 'tanggapan'.
            // Tapi jika kamu memang mau menyimpannya di sini juga, biarkan baris ini:
            // $table->enum('status', ['menunggu', 'proses', 'selesai'])->default('menunggu');

            // 4. Timestamps (created_at, updated_at)
            $table->timestamps();

            // 5. Definisi Foreign Key Constraints
            $table->foreign('id_pengaduan')
                ->references('id_pengaduan')
                ->on('pengaduan')
                ->onDelete('cascade');

            $table->foreign('id_petugas')
                ->references('id_petugas') // Sesuaikan dengan PK tabel petugas
                ->on('petugas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanggapan');
    }
};