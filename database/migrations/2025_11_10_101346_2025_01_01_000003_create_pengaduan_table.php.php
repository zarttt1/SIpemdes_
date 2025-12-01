<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id('id_pengaduan');
            $table->unsignedBigInteger('id_masyarakat');
            $table->dateTime('tanggal_pengaduan')->default(now()); 
            $table->text('isi_laporan');
            $table->string('foto');
            
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->timestamps();

            $table->foreign('id_masyarakat')
                ->references('id_masyarakat')
                ->on('masyarakat')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
