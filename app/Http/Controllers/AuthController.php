<?php

namespace App\Http\Controllers;

use App\Models\Masyarakat;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Str;

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

        $masyarakat = Masyarakat::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_token' => Str::random(60),
        ]);

        $otpCode = $masyarakat->generateOtp();
        
        try {
            Mail::to($masyarakat->email)->send(new OtpMail($masyarakat, $otpCode));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
        }

        return redirect()->route('verification.otp')
            ->with('success', 'Registrasi berhasil! Kode OTP telah dikirim ke email Anda.')
            ->with('user_id', $masyarakat->id)
            ->with('user_type', 'masyarakat');
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
            $user = Auth::guard('web')->user();
            
            if (!$user->hasVerifiedEmail()) {
                Auth::guard('web')->logout();
                return redirect()->route('verification.notice')
                    ->with('error', 'Silakan verifikasi email Anda terlebih dahulu.')
                    ->with('user_id', $user->id)
                    ->with('user_type', 'masyarakat');
            }
            
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
            $user = Auth::guard('petugas')->user();
            
            if (!$user->hasVerifiedEmail()) {
                Auth::guard('petugas')->logout();
                return redirect()->route('verification.notice')
                    ->with('error', 'Silakan verifikasi email Anda terlebih dahulu.')
                    ->with('user_id', $user->id)
                    ->with('user_type', 'petugas');
            }
            
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
