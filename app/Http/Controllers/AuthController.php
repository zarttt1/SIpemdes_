<?php

namespace App\Http\Controllers;

use App\Models\Masyarakat;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Masyarakat Registration
    public function showMasyarakatRegister()
    {
        return view('auth.register-masyarakat');
    }

    public function registerMasyarakat(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:masyarakat,nik|digits:16',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'username' => 'required|unique:masyarakat,username|string|max:50',
            'email' => 'required|unique:masyarakat,email|email',
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

        return redirect()->route('login.masyarakat')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Masyarakat Login
    public function showMasyarakatLogin()
    {
        return view('auth.login-masyarakat');
    }

    public function loginMasyarakat(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            return redirect()->intended('/masyarakat/dashboard');
        }

        return back()->with('error', 'Username atau password salah.');
    }

    // Petugas Login
    public function showPetugasLogin()
    {
        return view('auth.login-petugas');
    }

    public function loginPetugas(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('petugas')->attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            return redirect()->intended('/petugas/dashboard');
        }

        return back()->with('error', 'Username atau password salah.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
