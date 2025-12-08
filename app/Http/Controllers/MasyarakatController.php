<?php

namespace App\Http\Controllers;

use App\Models\Masyarakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MasyarakatController extends Controller
{
    // Show profile
    public function show()
    {
        $masyarakat = Auth::guard('web')->user();
        return view('masyarakat.profile.show', compact('masyarakat'));
    }

    // Edit profile form
    public function edit()
    {
        $masyarakat = Auth::guard('web')->user();
        return view('masyarakat.profile.edit', compact('masyarakat'));
    }

    // Update profile
    public function update(Request $request)
    {
        $masyarakat = Auth::guard('web')->user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'email' => ['required', 'email', Rule::unique('masyarakat')->ignore($masyarakat->id_masyarakat, 'id_masyarakat')],
        ]);

        $masyarakat->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
        ]);

        return redirect()->route('masyarakat.profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    // Change password form
    public function editPassword()
    {
        return view('masyarakat.profile.change-password');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $masyarakat = Auth::guard('web')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $masyarakat->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai.');
        }

        $masyarakat->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('masyarakat.profile.show')
            ->with('success', 'Password berhasil diubah!');
    }
}
