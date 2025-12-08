<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Tanggapan; // <--- PENTING: Jangan lupa import model ini
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
        try {
            $id = Auth::user()->id_masyarakat ?? null;

            if (!$id) {
                return redirect()->route('login')->with('error', 'Anda tidak terautentikasi sebagai masyarakat.');
            }

            $pengaduan = Pengaduan::where('id_masyarakat', $id)
                ->with('tanggapan.petugas') // Load relasi untuk notifikasi
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('masyarakat.pengaduan.index', compact('pengaduan'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('masyarakat.pengaduan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'isi_laporan' => 'required|string',
            'foto' => 'nullable|image|max:2048|mimes:jpg,jpeg,png',
        ], [
            'isi_laporan.required' => 'Isi laporan tidak boleh kosong.',
            'foto.image' => 'File yang diupload harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        try {
            $fotoPath = $request->hasFile('foto')
                ? $request->file('foto')->store('pengaduan', 'public')
                : null;

            $pengaduan = Pengaduan::create([
                'id_masyarakat' => Auth::user()->id_masyarakat,
                'tanggal_pengaduan' => now(),
                'isi_laporan' => $request->isi_laporan,
                'foto' => $fotoPath,
                'status' => 'menunggu',
            ]);

            $this->logAction('create', 'Pengaduan', $pengaduan->id_pengaduan);

            return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim pengaduan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)
                ->where('id_masyarakat', Auth::user()->id_masyarakat)
                ->with('tanggapan.petugas')
                ->firstOrFail();

            return view('masyarakat.pengaduan.show', compact('pengaduan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        }
    }

    // === [MASYARAKAT] PAGE RIWAYAT TANGGAPAN ===
    public function showTanggapan($id)
    {
        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)
                ->where('id_masyarakat', Auth::user()->id_masyarakat)
                ->with(['tanggapan.petugas'])
                ->firstOrFail();

            return view('masyarakat.pengaduan.tanggapan', compact('pengaduan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        }
    }

    public function edit($id)
    {
        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)
                ->where('id_masyarakat', Auth::user()->id_masyarakat)
                ->firstOrFail();

            return view('masyarakat.pengaduan.edit', compact('pengaduan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'isi_laporan' => 'required|string',
            'foto' => 'nullable|image|max:2048|mimes:jpg,jpeg,png',
        ]);

        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)
                ->where('id_masyarakat', Auth::user()->id_masyarakat)
                ->firstOrFail();

            $oldValues = $pengaduan->getAttributes();

            if ($request->hasFile('foto')) {
                if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
                    Storage::disk('public')->delete($pengaduan->foto);
                }
                $pengaduan->foto = $request->file('foto')->store('pengaduan', 'public');
            }

            $pengaduan->isi_laporan = $request->isi_laporan;
            $pengaduan->save();

            $this->logAction('update', 'Pengaduan', $pengaduan->id_pengaduan, $oldValues, $pengaduan->getChanges());

            return redirect()->route('pengaduan.show', $pengaduan->id_pengaduan)
                ->with('success', 'Pengaduan berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaduan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)
                ->where('id_masyarakat', Auth::user()->id_masyarakat)
                ->firstOrFail();

            if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
                Storage::disk('public')->delete($pengaduan->foto);
            }

            $this->logAction('delete', 'Pengaduan', $pengaduan->id_pengaduan);
            $pengaduan->delete();

            return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pengaduan: ' . $e->getMessage());
        }
    }

    /**
     * ========================
     * PETUGAS / ADMIN
     * ========================
     */

    public function indexPetugas()
    {
        try {
            $totalPengaduan = Pengaduan::count();
            $pengaduanBaru = Pengaduan::where('status', 'menunggu')->count();
            $diproses = Pengaduan::where('status', 'proses')->count();
            $selesai = Pengaduan::where('status', 'selesai')->count();

            $pengaduan = Pengaduan::with('masyarakat')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('dashboard.petugas', compact(
                'pengaduan',
                'totalPengaduan',
                'pengaduanBaru',
                'diproses',
                'selesai'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showPetugas($id)
    {
        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)
                ->with('masyarakat', 'tanggapan.petugas')
                ->firstOrFail();

            return view('petugas.pengaduan.show', compact('pengaduan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        }
    }

    // === [PETUGAS] PAGE KHUSUS BERI TANGGAPAN ===
    public function createTanggapan($id)
    {
        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)
                ->with(['masyarakat', 'tanggapan.petugas'])
                ->firstOrFail();

            return view('petugas.pengaduan.tanggapan', compact('pengaduan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        }
    }

    // === [PETUGAS] UPDATE STATUS & KIRIM TANGGAPAN ===
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        try {
            $request->validate([
                'status' => 'required|in:menunggu,proses,selesai',
                'isi_tanggapan' => 'required|string', // Validasi input tanggapan wajib diisi
            ]);

            $oldStatus = $pengaduan->status;
            
            // 1. Update Status
            $pengaduan->update(['status' => $request->status]);

            // 2. Simpan Tanggapan
            Tanggapan::create([
                'id_pengaduan' => $pengaduan->id_pengaduan,
                'id_petugas'   => Auth::guard('petugas')->user()->id_petugas, // Pastikan guard 'petugas' benar
                'tanggal_tanggapan' => now(),
                'isi_tanggapan' => $request->isi_tanggapan,
            ]);

            // 3. Log Action
            $this->logAction('update', 'Pengaduan', $pengaduan->id_pengaduan,
                ['status' => $oldStatus],
                ['status' => $request->status, 'tanggapan' => 'Ditambahkan']
            );

            // Redirect kembali ke halaman beri tanggapan agar bisa lihat hasil
            return redirect()->route('petugas.pengaduan.tanggapan', $pengaduan->id_pengaduan)
                ->with('success', 'Status diperbarui dan tanggapan berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    // === ADMIN SECTION ===

    public function indexAdmin()
    {
        try {
            $pengaduan = Pengaduan::with('masyarakat')
                ->latest()
                ->paginate(10);

            return view('admin.pengaduan.index', compact('pengaduan'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showAdmin($id)
    {
        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)
                ->with('masyarakat', 'tanggapan.petugas')
                ->firstOrFail();

            return view('admin.pengaduan.show', compact('pengaduan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        }
    }

    public function editAdmin($id)
    {
        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)->firstOrFail();
            return view('admin.pengaduan.edit', compact('pengaduan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        }
    }

    public function updateAdmin(Request $request, $id)
    {
        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)->firstOrFail();

            $request->validate([
                'isi_laporan' => 'required|string|min:20',
                'status' => 'required|in:menunggu,proses,selesai',
                'foto' => 'nullable|image|max:2048',
            ]);

            $oldValues = $pengaduan->getAttributes();

            if ($request->hasFile('foto')) {
                if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
                    Storage::disk('public')->delete($pengaduan->foto);
                }
                $pengaduan->foto = $request->file('foto')->store('pengaduan', 'public');
            }

            $pengaduan->update([
                'isi_laporan' => $request->isi_laporan,
                'status' => $request->status,
            ]);

            $this->logAction('update', 'Pengaduan', $pengaduan->id_pengaduan, $oldValues, $pengaduan->getChanges());

            return redirect()->route('admin.pengaduan.show', $pengaduan->id_pengaduan)
                ->with('success', 'Pengaduan berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaduan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyAdmin($id)
    {
        try {
            $pengaduan = Pengaduan::where('id_pengaduan', $id)->firstOrFail();

            if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
                Storage::disk('public')->delete($pengaduan->foto);
            }

            $this->logAction('delete', 'Pengaduan', $pengaduan->id_pengaduan);
            $pengaduan->tanggapan()->delete();
            $pengaduan->delete();

            return redirect()->route('admin.pengaduan.index')
                ->with('success', 'Pengaduan berhasil dihapus!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Pengaduan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pengaduan: ' . $e->getMessage());
        }
    }
}