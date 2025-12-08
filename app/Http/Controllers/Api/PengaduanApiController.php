<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanApiController extends Controller
{
    // Get all pengaduan for authenticated masyarakat
    public function index()
    {
        $pengaduan = Pengaduan::where('id_masyarakat', Auth::id())
            ->with('masyarakat:id_masyarakat,nama,nik')
            ->latest('tanggal_pengaduan')
            ->paginate(10);

        return response()->json($pengaduan);
    }

    // Create new pengaduan
    public function store(Request $request)
    {
        $request->validate([
            'isi_laporan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pengaduan = new Pengaduan();
        $pengaduan->id_masyarakat = Auth::id();
        $pengaduan->tanggal_pengaduan = now();
        $pengaduan->isi_laporan = $request->isi_laporan;

        if ($request->hasFile('foto')) {
            $pengaduan->foto = $request->file('foto')->store('pengaduan', 'public');
        }

        $pengaduan->status = 'baru';
        $pengaduan->save();

        return response()->json([
            'message' => 'Pengaduan berhasil dibuat',
            'data' => $pengaduan
        ], 201);
    }

    // Get single pengaduan
    public function show(Pengaduan $pengaduan)
    {
        if (Auth::id() !== $pengaduan->id_masyarakat) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pengaduan->load(['masyarakat:id_masyarakat,nama,nik', 'tanggapan.petugas:id,nama']);

        return response()->json($pengaduan);
    }

    // Update pengaduan
    public function update(Request $request, Pengaduan $pengaduan)
    {
        if (Auth::id() !== $pengaduan->id_masyarakat) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($pengaduan->status !== 'baru') {
            return response()->json(['error' => 'Pengaduan yang sudah diproses tidak dapat diedit'], 400);
        }

        $request->validate([
            'isi_laporan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pengaduan->isi_laporan = $request->isi_laporan;

        if ($request->hasFile('foto')) {
            if ($pengaduan->foto) {
                Storage::disk('public')->delete($pengaduan->foto);
            }
            $pengaduan->foto = $request->file('foto')->store('pengaduan', 'public');
        }

        $pengaduan->save();

        return response()->json([
            'message' => 'Pengaduan berhasil diperbarui',
            'data' => $pengaduan
        ]);
    }

    // Delete pengaduan
    public function destroy(Pengaduan $pengaduan)
    {
        if (Auth::id() !== $pengaduan->id_masyarakat) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($pengaduan->status !== 'baru') {
            return response()->json(['error' => 'Pengaduan yang sudah diproses tidak dapat dihapus'], 400);
        }

        if ($pengaduan->foto) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        $pengaduan->delete();

        return response()->json(['message' => 'Pengaduan berhasil dihapus']);
    }

    // Petugas - Get all pengaduan
    public function indexPetugas()
    {
        $pengaduan = Pengaduan::with('masyarakat:id_masyarakat,nama,nik')
            ->latest('tanggal_pengaduan')
            ->paginate(20);

        return response()->json($pengaduan);
    }

    // Petugas - Update status
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai',
        ]);

        $pengaduan->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status berhasil diperbarui',
            'data' => $pengaduan
        ]);
    }
}
