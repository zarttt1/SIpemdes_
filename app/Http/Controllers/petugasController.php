<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    /**
     * Tampilkan profil petugas yang login
     */
    public function profile()
    {
        $petugas = Auth::guard('petugas')->user();
        $statistik = [
            'total_tanggapan' => Tanggapan::where('id_petugas', $petugas->id_petugas)->count(),
            'tanggapan_bulan_ini' => Tanggapan::where('id_petugas', $petugas->id_petugas)
                ->whereMonth('tanggal_tanggapan', now()->month)
                ->count(),
        ];
        
        return view('petugas.profile', compact('petugas', 'statistik'));
    }

    /**
     * Form edit profil petugas
     */
    public function editProfile()
    {
        $petugas = Auth::guard('petugas')->user();
        return view('petugas.edit-profile', compact('petugas'));
    }

    /**
     * Update profil petugas
     */
    public function updateProfile(Request $request)
    {
        $petugas = Auth::guard('petugas')->user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:petugas,email,' . $petugas->id_petugas . ',id_petugas',
            'username' => 'required|string|max:50|unique:petugas,username,' . $petugas->id_petugas . ',id_petugas',
            'password_lama' => 'nullable|required_with:password_baru',
            'password_baru' => 'nullable|min:8|confirmed',
        ]);

        // Validasi password lama jika ada perubahan password
        if ($request->filled('password_baru')) {
            if (!Hash::check($request->password_lama, $petugas->password)) {
                return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
            }
        }

        $petugas->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
        ]);

        if ($request->filled('password_baru')) {
            $petugas->update(['password' => Hash::make($request->password_baru)]);
        }

        return redirect()->route('petugas.profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Tampilkan laporan tanggapan petugas
     */
    public function laporan()
    {
        $petugas = Auth::guard('petugas')->user();
        $tanggapan = Tanggapan::where('id_petugas', $petugas->id_petugas)
            ->with('pengaduan')
            ->latest()
            ->paginate(10);
        
        return view('petugas.laporan', compact('tanggapan'));
    }
}
