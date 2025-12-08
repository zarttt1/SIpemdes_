<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use App\Http\Requests\StoreTanggapanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TanggapanController extends Controller
{
    /**
     * Tampilkan semua tanggapan (untuk admin)
     */
    public function index()
    {
<<<<<<< HEAD
        try {
            $tanggapan = Tanggapan::with('pengaduan', 'petugas')
                ->latest()
                ->paginate(10);
            
            return view('admin.tanggapan.index', compact('tanggapan'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
=======
        $request->validate([
            'isi_tanggapan' => 'required|string',
        ]);

        Tanggapan::create([
            'id_pengaduan' => $pengaduan->id,
            'id_petugas' => Auth::guard('petugas')->id(),
            'tanggal_tanggapan' => now(),
            'isi_tanggapan' => $request->isi_tanggapan,
        ]);

        // Update status pengaduan menjadi diproses jika masih baru
        if ($pengaduan->status === 'baru') {
            $pengaduan->update(['status' => 'diproses']);
>>>>>>> 5739e6f1e01310efbbc53dda653e2eabca4fc289
        }
    }

    /**
     * Petugas - Tambah tanggapan
     */
    public function store(StoreTanggapanRequest $request, Pengaduan $pengaduan)
    {
        try {
            $tanggapan = Tanggapan::create([
                'id_pengaduan' => $pengaduan->id_pengaduan,
                'id_petugas' => Auth::guard('petugas')->user()->id_petugas,
                'tanggal_tanggapan' => now(),
                'isi_tanggapan' => $request->isi_tanggapan,
            ]);

            // Update status pengaduan jika masih menunggu
            if ($pengaduan->status === 'menunggu') {
                $oldStatus = $pengaduan->status;
                $pengaduan->update(['status' => 'proses']);
                $this->logAction('update', 'Pengaduan', $pengaduan->id_pengaduan, 
                    ['status' => $oldStatus], 
                    ['status' => 'proses']
                );
            }

            $this->logAction('create', 'Tanggapan', $tanggapan->id);

            return back()->with('success', 'Tanggapan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan tanggapan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Admin - Form edit tanggapan
     */
    public function edit($id)
    {
        try {
            $tanggapan = Tanggapan::findOrFail($id);
            return view('admin.tanggapan.edit', compact('tanggapan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Tanggapan');
        }
    }

    /**
     * Admin - Update tanggapan
     */
    public function update(StoreTanggapanRequest $request, $id)
    {
        try {
            $tanggapan = Tanggapan::findOrFail($id);
            $oldValues = $tanggapan->getAttributes();

            $tanggapan->update([
                'isi_tanggapan' => $request->isi_tanggapan,
                'tanggal_tanggapan' => now(),
            ]);

            $this->logAction('update', 'Tanggapan', $tanggapan->id, $oldValues, $tanggapan->getChanges());

            return redirect()->route('admin.tanggapan.show', $tanggapan->id)
                ->with('success', 'Tanggapan berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Tanggapan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui tanggapan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Admin - Hapus tanggapan
     */
    public function destroy($id)
    {
        try {
            $tanggapan = Tanggapan::findOrFail($id);
            $this->logAction('delete', 'Tanggapan', $tanggapan->id);
            $tanggapan->delete();

            return redirect()->route('admin.tanggapan.index')
                ->with('success', 'Tanggapan berhasil dihapus!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleNotFound('Tanggapan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus tanggapan: ' . $e->getMessage());
        }
    }

    public function edit(Tanggapan $tanggapan)
    {
        // Ensure petugas owns this tanggapan
        if (Auth::guard('petugas')->id() !== $tanggapan->id_petugas) {
            abort(403);
        }

        return view('petugas.tanggapan.edit', compact('tanggapan'));
    }

    public function update(Request $request, Tanggapan $tanggapan)
    {
        // Ensure petugas owns this tanggapan
        if (Auth::guard('petugas')->id() !== $tanggapan->id_petugas) {
            abort(403);
        }

        $request->validate([
            'isi_tanggapan' => 'required|string',
        ]);

        $tanggapan->update([
            'isi_tanggapan' => $request->isi_tanggapan,
        ]);

        return redirect()->route('pengaduan.show', $tanggapan->id_pengaduan)
            ->with('success', 'Tanggapan berhasil diperbarui!');
    }

    public function destroy(Tanggapan $tanggapan)
    {
        // Ensure petugas owns this tanggapan or is admin
        $petugas = Auth::guard('petugas')->user();
        
        if ($petugas->id !== $tanggapan->id_petugas && $petugas->level !== 'admin') {
            abort(403);
        }

        $pengaduanId = $tanggapan->id_pengaduan;
        $tanggapan->delete();

        // Check if there are no more tanggapan, revert status to 'baru'
        $pengaduan = Pengaduan::find($pengaduanId);
        if ($pengaduan && $pengaduan->tanggapan()->count() === 0 && $pengaduan->status === 'diproses') {
            $pengaduan->update(['status' => 'baru']);
        }

        return back()->with('success', 'Tanggapan berhasil dihapus!');
    }
}
