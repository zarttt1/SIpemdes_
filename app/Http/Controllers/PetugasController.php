<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    // Admin only - List all petugas
    public function index()
    {
        $this->authorizeAdmin();
        
        $petugas = Petugas::latest()->paginate(20);
        return view('petugas.manage.index', compact('petugas'));
    }

    // Admin only - Create petugas form
    public function create()
    {
        $this->authorizeAdmin();
        return view('petugas.manage.create');
    }

    // Admin only - Store new petugas
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:petugas,email',
            'username' => 'required|string|max:50|unique:petugas,username',
            'password' => 'required|min:8|confirmed',
            'level' => 'required|in:admin,petugas',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Petugas::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'status' => $request->status,
        ]);

        return redirect()->route('petugas.manage.index')
            ->with('success', 'Petugas berhasil ditambahkan!');
    }

    // Admin only - Edit petugas form
    public function edit(Petugas $petugas)
    {
        $this->authorizeAdmin();
        return view('petugas.manage.edit', compact('petugas'));
    }

    // Admin only - Update petugas
    public function update(Request $request, Petugas $petugas)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('petugas')->ignore($petugas->id)],
            'username' => ['required', 'string', 'max:50', Rule::unique('petugas')->ignore($petugas->id)],
            'level' => 'required|in:admin,petugas',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $updateData = [
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'level' => $request->level,
            'status' => $request->status,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $updateData['password'] = Hash::make($request->password);
        }

        $petugas->update($updateData);

        return redirect()->route('petugas.manage.index')
            ->with('success', 'Petugas berhasil diperbarui!');
    }

    // Admin only - Delete petugas
    public function destroy(Petugas $petugas)
    {
        $this->authorizeAdmin();

        // Prevent deleting yourself
        if ($petugas->id === Auth::guard('petugas')->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $petugas->delete();

        return redirect()->route('petugas.manage.index')
            ->with('success', 'Petugas berhasil dihapus!');
    }

    // Show own profile
    public function showProfile()
    {
        $petugas = Auth::guard('petugas')->user();
        return view('petugas.profile.show', compact('petugas'));
    }

    // Edit own profile form
    public function editProfile()
    {
        $petugas = Auth::guard('petugas')->user();
        return view('petugas.profile.edit', compact('petugas'));
    }

    // Update own profile
    public function updateProfile(Request $request)
    {
        $petugas = Auth::guard('petugas')->user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('petugas')->ignore($petugas->id)],
        ]);

        $petugas->update([
            'nama' => $request->nama,
            'email' => $request->email,
        ]);

        return redirect()->route('petugas.profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    // Change own password
    public function updatePassword(Request $request)
    {
        $petugas = Auth::guard('petugas')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $petugas->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai.');
        }

        $petugas->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('petugas.profile.show')
            ->with('success', 'Password berhasil diubah!');
    }

    // Helper method to check admin authorization
    private function authorizeAdmin()
    {
        if (Auth::guard('petugas')->user()->level !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }
    }
}
