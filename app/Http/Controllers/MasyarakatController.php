<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;

class MasyarakatController extends Controller
{
    /**
     * Tampilkan dashboard masyarakat.
     */
    public function dashboard()
    {
        $user = auth('web')->user();

        // Ambil semua pengaduan milik user ini
        $pengaduan = Pengaduan::where('id_masyarakat', $user->id_masyarakat)
            ->latest()
            ->get();

        // Hitung statistik
        $total = $pengaduan->count();
        $baru = $pengaduan->where('status', 'baru')->count();
        $diproses = $pengaduan->where('status', 'diproses')->count();
        $selesai = $pengaduan->where('status', 'selesai')->count();

        // Kirim ke view
        return view('dashboard.masyarakat', compact('pengaduan', 'total', 'baru', 'diproses', 'selesai'));
    }
}
