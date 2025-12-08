<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Http\Requests\StorePengaduanRequest;
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

    public function store(StorePengaduanRequest $request)
    {
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

    public function update(StorePengaduanRequest $request, $id)
    {
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

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        try {
            $request->validate([
                'status' => 'required|in:menunggu,proses,selesai',
            ]);

            $oldStatus = $pengaduan->status;
            $pengaduan->update(['status' => $request->status]);

            $this->logAction('update', 'Pengaduan', $pengaduan->id_pengaduan, 
                ['status' => $oldStatus], 
                ['status' => $request->status]
            );

            return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

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
