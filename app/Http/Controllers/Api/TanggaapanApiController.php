<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TanggapanApiController extends Controller
{
    // Create tanggapan
    public function store(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'isi_tanggapan' => 'required|string',
        ]);

        $tanggapan = Tanggapan::create([
            'id_pengaduan' => $pengaduan->id,
            'id_petugas' => Auth::guard('petugas')->id(),
            'tanggal_tanggapan' => now(),
            'isi_tanggapan' => $request->isi_tanggapan,
        ]);

        if ($pengaduan->status === 'baru') {
            $pengaduan->update(['status' => 'diproses']);
        }

        return response()->json([
            'message' => 'Tanggapan berhasil ditambahkan',
            'data' => $tanggapan->load('petugas:id,nama')
        ], 201);
    }

    // Update tanggapan
    public function update(Request $request, Tanggapan $tanggapan)
    {
        if (Auth::guard('petugas')->id() !== $tanggapan->id_petugas) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'isi_tanggapan' => 'required|string',
        ]);

        $tanggapan->update([
            'isi_tanggapan' => $request->isi_tanggapan,
        ]);

        return response()->json([
            'message' => 'Tanggapan berhasil diperbarui',
            'data' => $tanggapan
        ]);
    }

    // Delete tanggapan
    public function destroy(Tanggapan $tanggapan)
    {
        $petugas = Auth::guard('petugas')->user();
        
        if ($petugas->id !== $tanggapan->id_petugas && $petugas->level !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pengaduanId = $tanggapan->id_pengaduan;
        $tanggapan->delete();

        $pengaduan = Pengaduan::find($pengaduanId);
        if ($pengaduan && $pengaduan->tanggapan()->count() === 0 && $pengaduan->status === 'diproses') {
            $pengaduan->update(['status' => 'baru']);
        }

        return response()->json(['message' => 'Tanggapan berhasil dihapus']);
    }
}
