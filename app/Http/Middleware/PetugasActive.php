<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // 1. Tambahkan Import Facade Auth

class PetugasActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 2. Gunakan Auth::guard() agar lebih eksplisit daripada helper auth()
        $guard = Auth::guard('petugas');

        // Cek apakah user login DAN statusnya bukan aktif
        // Kita gunakan check() dulu untuk memastikan user ada, baru ambil datanya
        if ($guard->check() && $guard->user()->status !== 'aktif') {
            
            // Logout user tersebut
            $guard->logout();
            
            // Invalidate session untuk keamanan (mencegah session hijacking)
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // 3. Pastikan nama route 'login' ini benar. 
            // Jika kamu punya route khusus admin, misal 'login.petugas', ganti di sini.
            return redirect()->route('login') 
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }

        return $next($request);
    }
}