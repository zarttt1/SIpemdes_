<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TanggapanController extends Controller
{
    // Petugas - Tambah tanggapan
    public function store(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'isi_tanggapan' => 'required|string',
        ]);

        Tanggapan::create([
            'id_pengaduan' => $pengaduan->id_pengaduan,
            'id_petugas' => Auth::guard('petugas')->id(),
            'tanggal_tanggapan' => now(),
            'isi_tanggapan' => $request->isi_tanggapan,
        ]);

        // Update status pengaduan jika masih menunggu
        if ($pengaduan->status === 'menunggu') {
            $pengaduan->update(['status' => 'diproses']);
        }

        return back()->with('success', 'Tanggapan berhasil ditambahkan!');
    }
}
