<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Menampilkan daftar pengaduan masyarakat yang sedang login
     */
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

    /**
     * Menampilkan form untuk membuat pengaduan baru
     */
    public function create()
    {
        return view('masyarakat.pengaduan.create');
    }

    /**
     * Menyimpan pengaduan baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'isi_laporan' => 'required|string', // tidak dibatasi panjang
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
            'status' => 'menunggu', // gunakan nilai valid di database
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim!');
    }

    /**
     * Menampilkan detail satu pengaduan
     */
    public function show($id)
    {
        $pengaduan = Pengaduan::where('id_pengaduan', $id)
            ->where('id_masyarakat', Auth::user()->id_masyarakat)
            ->firstOrFail();

        return view('masyarakat.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Menampilkan form edit pengaduan
     */
    public function edit($id)
    {
        $pengaduan = Pengaduan::where('id_pengaduan', $id)
            ->where('id_masyarakat', Auth::user()->id_masyarakat)
            ->firstOrFail();

        return view('masyarakat.pengaduan.edit', compact('pengaduan'));
    }

    /**
     * Memperbarui data pengaduan
     */
    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::where('id_pengaduan', $id)
            ->where('id_masyarakat', Auth::user()->id_masyarakat)
            ->firstOrFail();

        $request->validate([
            'isi_laporan' => 'required|string', // hapus batas max:255
            'foto' => 'nullable|image|max:2048',
        ]);

        // Hapus foto lama jika diganti
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

    /**
     * Menghapus pengaduan
     */
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
}
