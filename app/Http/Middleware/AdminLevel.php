<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminLevel
{
    /**
     * Handle an incoming request - memastikan hanya admin level yang dapat mengakses
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $petugas = auth('petugas')->user();

        if (!$petugas || $petugas->level !== 'admin' || $petugas->status !== 'aktif') {
            return redirect()->route('dashboard.petugas')
                ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}
