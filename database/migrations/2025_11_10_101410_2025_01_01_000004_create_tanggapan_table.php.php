<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanggapan', function (Blueprint $table) {
            $table->id('id_tanggapan');
            $table->unsignedBigInteger('id_pengaduan');
            $table->unsignedBigInteger('id_petugas');
            $table->dateTime('tanggal_tanggapan');
            $table->text('isi_tanggapan');
            $table->timestamps();

            $table->foreign('id_pengaduan')
                ->references('id_pengaduan')
                ->on('pengaduan')
                ->onDelete('cascade');

            $table->foreign('id_petugas')
                ->references('id_petugas')
                ->on('petugas')
                ->onDelete('cascade');

            $table->date('tanggal_tanggapan');
            $table->text('isi_tanggapan');
            $table->enum('status', ['baru','menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tanggapan');
    }
};
