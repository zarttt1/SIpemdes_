<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Petugas::create([
            'nama' => 'Admin Desa',
            'email' => 'admin@desa.local',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'level' => 'admin',
            'status' => 'aktif',
        ]);

        Petugas::create([
            'nama' => 'Petugas Desa 1',
            'email' => 'petugas1@desa.local',
            'username' => 'petugas1',
            'password' => Hash::make('password123'),
            'level' => 'petugas',
            'status' => 'aktif',
        ]);

        Petugas::create([
            'nama' => 'Petugas Desa 2',
            'email' => 'petugas2@desa.local',
            'username' => 'petugas2',
            'password' => Hash::make('password123'),
            'level' => 'petugas',
            'status' => 'aktif',
        ]);
    }
}
