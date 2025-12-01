<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * ========================
     * MASYARAKAT (USER)
     * ========================
     */

    // Menampilkan daftar pengaduan milik masyarakat
    public function index()
    {
        $id = Auth::user()->id_masyarakat ?? null;

        if (!$id) {
            return redirect()->route('login')->with('error', 'Anda tidak terautentikasi sebagai masyarakat.');
        }

        $pengaduan = Pengaduan::where('id_masyarakat', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('masyarakat.pengaduan.index', compact('pengaduan'));
    }

    public function create()
    {
        return view('masyarakat.pengaduan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'isi_laporan' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('pengaduan', 'public')
            : null;

        Pengaduan::create([
            'id_masyarakat' => Auth::user()->id_masyarakat,
            'tanggal_pengaduan' => now(),
            'isi_laporan' => $request->isi_laporan,
            'foto' => $fotoPath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim!');
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::where('id_pengaduan', $id)
            ->where('id_masyarakat', Auth::user()->id_masyarakat)
            ->firstOrFail();

        return view('masyarakat.pengaduan.show', compact('pengaduan'));
    }

    public function edit($id)
    {
        $pengaduan = Pengaduan::where('id_pengaduan', $id)
            ->where('id_masyarakat', Auth::user()->id_masyarakat)
            ->firstOrFail();

        return view('masyarakat.pengaduan.edit', compact('pengaduan'));
    }

    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::where('id_pengaduan', $id)
            ->where('id_masyarakat', Auth::user()->id_masyarakat)
            ->firstOrFail();

        $request->validate([
            'isi_laporan' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
                Storage::disk('public')->delete($pengaduan->foto);
            }

            $pengaduan->foto = $request->file('foto')->store('pengaduan', 'public');
        }

        $pengaduan->isi_laporan = $request->isi_laporan;
        $pengaduan->save();

        return redirect()->route('pengaduan.show', $pengaduan->id_pengaduan)
            ->with('success', 'Pengaduan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pengaduan = Pengaduan::where('id_pengaduan', $id)
            ->where('id_masyarakat', Auth::user()->id_masyarakat)
            ->firstOrFail();

        if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dihapus.');
    }



    /**
     * ========================
     * PETUGAS / ADMIN
     * ========================
     */

    // Menampilkan semua pengaduan untuk petugas
    public function indexPetugas()
    {
        // Data statistik
        $totalPengaduan = Pengaduan::count();
        $pengaduanBaru = Pengaduan::where('status', 'menunggu')->count();
        $diproses = Pengaduan::where('status', 'proses')->count();
        $selesai = Pengaduan::where('status', 'selesai')->count();

        // Data tabel
        $pengaduan = Pengaduan::with('masyarakat')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('dashboard.petugas', compact(
            'pengaduan',
            'totalPengaduan',
            'pengaduanBaru',
            'diproses',
            'selesai'
        ));
    }

    // 🔥 MENAMPILKAN DETAIL PENGADUAN UNTUK PETUGAS
    public function showPetugas($id)
    {
        $pengaduan = Pengaduan::with('masyarakat', 'tanggapan')
                    ->where('id_pengaduan', $id)
                    ->firstOrFail();

        return view('petugas.pengaduan.show', compact('pengaduan'));
    }

    // Petugas update status
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:menunggu,proses,selesai',
        ]);

        $pengaduan->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui!');
    }
}
