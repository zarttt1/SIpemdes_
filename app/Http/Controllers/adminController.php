<?php

namespace App\Http\Controllers;

use App\Models\Masyarakat;
use App\Models\Petugas;
use App\Models\Pengaduan;
use App\Models\Tanggapan;
use App\Http\Requests\StoreMasyarakatRequest;
use App\Http\Requests\UpdateMasyarakatRequest;
use App\Http\Requests\StorePetugasRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.level')->except(['dashboard']);
    }

    // ========== MASYARAKAT ==========

    /**
     * Tampilkan daftar semua masyarakat
     */
    public function indexMasyarakat()
    {
        try {
            $masyarakat = Masyarakat::latest()->paginate(10);
            return view('admin.masyarakat.index', compact('masyarakat'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form tambah masyarakat
     */
    public function createMasyarakat()
    {
        return view('admin.masyarakat.create');
    }

    /**
     * Simpan data masyarakat baru
     */
    public function storeMasyarakat(StoreMasyarakatRequest $request)
    {
        try {
            Masyarakat::create([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.masyarakat.index')
                ->with('success', 'Masyarakat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan masyarakat: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan detail masyarakat
     */
    public function showMasyarakat($id)
    {
        try {
            $masyarakat = Masyarakat::findOrFail($id);
            $pengaduan = $masyarakat->pengaduan()->latest()->paginate(5);
            
            return view('admin.masyarakat.show', compact('masyarakat', 'pengaduan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Masyarakat tidak ditemukan.');
        }
    }

    /**
     * Form edit masyarakat
     */
    public function editMasyarakat($id)
    {
        try {
            $masyarakat = Masyarakat::findOrFail($id);
            return view('admin.masyarakat.edit', compact('masyarakat'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Masyarakat tidak ditemukan.');
        }
    }

    /**
     * Update data masyarakat
     */
    public function updateMasyarakat(UpdateMasyarakatRequest $request, $id)
    {
        try {
            $masyarakat = Masyarakat::findOrFail($id);

            $masyarakat->update([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $masyarakat->update(['password' => Hash::make($request->password)]);
            }

            return redirect()->route('admin.masyarakat.show', $masyarakat->id_masyarakat)
                ->with('success', 'Masyarakat berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Masyarakat tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui masyarakat: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus masyarakat
     */
    public function destroyMasyarakat($id)
    {
        try {
            $masyarakat = Masyarakat::findOrFail($id);
            
            // Hapus semua pengaduan & tanggapan yang terkait
            $pengaduan = $masyarakat->pengaduan()->get();
            foreach ($pengaduan as $p) {
                if ($p->foto && Storage::disk('public')->exists($p->foto)) {
                    Storage::disk('public')->delete($p->foto);
                }
                $p->tanggapan()->delete();
                $p->delete();
            }

            $masyarakat->delete();

            return redirect()->route('admin.masyarakat.index')
                ->with('success', 'Masyarakat berhasil dihapus!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Masyarakat tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus masyarakat: ' . $e->getMessage());
        }
    }

    // ========== PETUGAS ==========

    /**
     * Tampilkan daftar semua petugas
     */
    public function indexPetugas()
    {
        try {
            $petugas = Petugas::latest()->paginate(10);
            return view('admin.petugas.index', compact('petugas'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form tambah petugas
     */
    public function createPetugas()
    {
        return view('admin.petugas.create');
    }

    /**
     * Simpan data petugas baru
     */
    public function storePetugas(StorePetugasRequest $request)
    {
        try {
            Petugas::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'level' => $request->level,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.petugas.index')
                ->with('success', 'Petugas berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan petugas: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan detail petugas
     */
    public function showPetugas($id)
    {
        try {
            $petugas = Petugas::findOrFail($id);
            $tanggapan = $petugas->tanggapan()->latest()->paginate(10);
            
            return view('admin.petugas.show', compact('petugas', 'tanggapan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Petugas tidak ditemukan.');
        }
    }

    /**
     * Form edit petugas
     */
    public function editPetugas($id)
    {
        try {
            $petugas = Petugas::findOrFail($id);
            return view('admin.petugas.edit', compact('petugas'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Petugas tidak ditemukan.');
        }
    }

    /**
     * Update data petugas
     */
    public function updatePetugas(Request $request, $id)
    {
        try {
            $petugas = Petugas::findOrFail($id);

            $request->validate([
                'nama' => 'required|string|max:100',
                'email' => 'required|email|unique:petugas,email,' . $id . ',id_petugas',
                'username' => 'required|string|max:50|unique:petugas,username,' . $id . ',id_petugas',
                'password' => 'nullable|min:8',
                'level' => 'required|in:admin,petugas',
                'status' => 'required|in:aktif,nonaktif',
            ]);

            $petugas->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'username' => $request->username,
                'level' => $request->level,
                'status' => $request->status,
            ]);

            if ($request->filled('password')) {
                $petugas->update(['password' => Hash::make($request->password)]);
            }

            return redirect()->route('admin.petugas.show', $petugas->id_petugas)
                ->with('success', 'Petugas berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Petugas tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui petugas: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus petugas
     */
    public function destroyPetugas($id)
    {
        try {
            $petugas = Petugas::findOrFail($id);
            $petugas->delete();

            return redirect()->route('admin.petugas.index')
                ->with('success', 'Petugas berhasil dihapus!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Petugas tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus petugas: ' . $e->getMessage());
        }
    }

    // ========== TANGGAPAN ==========

    /**
     * Tampilkan daftar semua tanggapan
     */
    public function indexTanggapan()
    {
        try {
            $tanggapan = Tanggapan::with('pengaduan', 'petugas')
                ->latest()
                ->paginate(10);
            
            return view('admin.tanggapan.index', compact('tanggapan'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail tanggapan
     */
    public function showTanggapan($id)
    {
        try {
            $tanggapan = Tanggapan::with('pengaduan', 'petugas')->findOrFail($id);
            return view('admin.tanggapan.show', compact('tanggapan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Tanggapan tidak ditemukan.');
        }
    }

    /**
     * Hapus tanggapan
     */
    public function destroyTanggapan($id)
    {
        try {
            $tanggapan = Tanggapan::findOrFail($id);
            $tanggapan->delete();

            return redirect()->route('admin.tanggapan.index')
                ->with('success', 'Tanggapan berhasil dihapus!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Tanggapan tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus tanggapan: ' . $e->getMessage());
        }
    }

    // ========== DASHBOARD ==========

    /**
     * Dashboard admin dengan statistik
     */
    public function dashboard()
    {
        try {
            $totalMasyarakat = Masyarakat::count();
            $totalPetugas = Petugas::count();
            $totalPengaduan = Pengaduan::count();
            $totalTanggapan = Tanggapan::count();

            $pengaduanBaru = Pengaduan::where('status', 'menunggu')->count();
            $pengaduanProses = Pengaduan::where('status', 'proses')->count();
            $pengaduanSelesai = Pengaduan::where('status', 'selesai')->count();

            $recentPengaduan = Pengaduan::with('masyarakat')
                ->latest()
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'totalMasyarakat',
                'totalPetugas',
                'totalPengaduan',
                'totalTanggapan',
                'pengaduanBaru',
                'pengaduanProses',
                'pengaduanSelesai',
                'recentPengaduan'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat dashboard: ' . $e->getMessage());
        }
    }
}
