<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('masyarakat', function (Blueprint $table) {
            $table->id('id_masyarakat');
            $table->string('nik', 16)->unique();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('no_hp', 15);
            $table->string('username', 50)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('masyarakat');
    }
};
