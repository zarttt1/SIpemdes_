<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Masyarakat;

class MasyarakatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Masyarakat::create([
            'nik' => '1234567890109876',
            'nama' => 'Budi Santoso',
            'alamat' => 'Jl. Merdeka No. 123, Desa Makmur',
            'no_hp' => '081234567890',
            'username' => 'budi',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
        ]);

        Masyarakat::create([
            'nik' => '9876543210987654',
            'nama' => 'Siti Nurhaliza',
            'alamat' => 'Jl. Ahmad Yani No. 45, Desa Makmur',
            'no_hp' => '082345678901',
            'username' => 'siti',
            'email' => 'siti@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
