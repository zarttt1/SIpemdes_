<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    // Masyarakat - Lihat daftar pengaduan
    public function index()
    {
        $pengaduan = Pengaduan::where('id_masyarakat', Auth::guard('web')->id())
            ->latest('tanggal_pengaduan')
            ->paginate(10);

        return view('masyarakat.pengaduan.index', compact('pengaduan'));
    }

    // Masyarakat - Form buat pengaduan
    public function create()
    {
        return view('masyarakat.pengaduan.create');
    }

    // Masyarakat - Simpan pengaduan
    public function store(Request $request)
    {
        $request->validate([
            'isi_laporan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pengaduan = new Pengaduan();
        $pengaduan->id_masyarakat = Auth::guard('web')->id();
        $pengaduan->tanggal_pengaduan = now();
        $pengaduan->isi_laporan = $request->isi_laporan;

        if ($request->hasFile('foto')) {
            $pengaduan->foto = $request->file('foto')->store('pengaduan', 'public');
        }

        $pengaduan->status = 'baru';
        $pengaduan->save();

        return redirect()->route('pengaduan.show', $pengaduan->id)
            ->with('success', 'Pengaduan berhasil dibuat!');
    }

    // Masyarakat & Petugas - Lihat detail pengaduan
    public function show(Pengaduan $pengaduan)
    {
        // Cek otorisasi
        if (Auth::guard('web')->check()) {
            if (Auth::guard('web')->id() !== $pengaduan->id_masyarakat) {
                abort(403);
            }
        } elseif (!Auth::guard('petugas')->check()) {
            abort(401);
        }

        $tanggapan = $pengaduan->tanggapan()->latest()->get();

        return view('pengaduan.show', compact('pengaduan', 'tanggapan'));
    }

    public function edit(Pengaduan $pengaduan)
    {
        // Ensure user owns this pengaduan
        if (Auth::guard('web')->id() !== $pengaduan->id_masyarakat) {
            abort(403);
        }

        // Only allow editing if status is 'baru'
        if ($pengaduan->status !== 'baru') {
            return redirect()->route('pengaduan.show', $pengaduan->id)
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat diedit.');
        }

        return view('masyarakat.pengaduan.edit', compact('pengaduan'));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        // Ensure user owns this pengaduan
        if (Auth::guard('web')->id() !== $pengaduan->id_masyarakat) {
            abort(403);
        }

        // Only allow updating if status is 'baru'
        if ($pengaduan->status !== 'baru') {
            return redirect()->route('pengaduan.show', $pengaduan->id)
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat diedit.');
        }

        $request->validate([
            'isi_laporan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pengaduan->isi_laporan = $request->isi_laporan;

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($pengaduan->foto) {
                Storage::disk('public')->delete($pengaduan->foto);
            }
            $pengaduan->foto = $request->file('foto')->store('pengaduan', 'public');
        }

        $pengaduan->save();

        return redirect()->route('pengaduan.show', $pengaduan->id)
            ->with('success', 'Pengaduan berhasil diperbarui!');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        // Ensure user owns this pengaduan
        if (Auth::guard('web')->id() !== $pengaduan->id_masyarakat) {
            abort(403);
        }

        // Only allow deleting if status is 'baru'
        if ($pengaduan->status !== 'baru') {
            return redirect()->route('masyarakat.pengaduan.index')
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat dihapus.');
        }

        // Delete photo if exists
        if ($pengaduan->foto) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        $pengaduan->delete();

        return redirect()->route('masyarakat.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus!');
    }

    // Petugas - Daftar pengaduan
    public function indexPetugas()
    {
        $pengaduan = Pengaduan::with('masyarakat')
            ->latest('tanggal_pengaduan')
            ->paginate(20);

        return view('petugas.pengaduan.index', compact('pengaduan'));
    }

    // Petugas - Update status pengaduan
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai',
        ]);

        $pengaduan->update(['status' => $request->status]);

        return back()->with('success', 'Status pengaduan berhasil diperbarui!');
    }
}
