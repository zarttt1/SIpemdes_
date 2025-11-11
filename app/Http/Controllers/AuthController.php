<?php

namespace App\Http\Controllers;

use App\Models\Masyarakat;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * ======================
     *  LOGIN UMUM
     * ======================
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 🔹 1. Login masyarakat
        $masyarakat = Masyarakat::where('username', $request->username)->first();
        if ($masyarakat && Hash::check($request->password, $masyarakat->password)) {
            Auth::guard('web')->login($masyarakat);
            $request->session()->regenerate();
            return redirect()->route('dashboard.masyarakat');
        }

        // 🔹 2. Login petugas
        $petugas = Petugas::where('username', $request->username)->first();
        if ($petugas && Hash::check($request->password, $petugas->password)) {
            Auth::guard('petugas')->login($petugas);
            $request->session()->regenerate();
            return redirect()->route('dashboard.petugas');
        }

        // 🔹 3. Gagal login
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput();
    }

    /**
     * ======================
     *  REGISTER MASYARAKAT
     * ======================
     */
    public function showMasyarakatRegister()
    {
        return view('auth.register-masyarakat');
    }

    public function registerMasyarakat(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|unique:masyarakat,nik',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'username' => 'required|string|max:50|unique:masyarakat,username',
            'email' => 'required|email|unique:masyarakat,email',
            'password' => 'required|min:8|confirmed',
        ]);

        Masyarakat::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    /**
     * ======================
     *  LOGOUT
     * ======================
     */
    public function logout(Request $request)
    {
        if (Auth::guard('petugas')->check()) {
            Auth::guard('petugas')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
