<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\TanggapanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Redirect awal
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATION (LOGIN, REGISTER, LOGOUT)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    // Login umum (bisa masyarakat/petugas)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'processLogin'])->name('login.process');

    // Register khusus masyarakat
    Route::get('register-masyarakat', [AuthController::class, 'showMasyarakatRegister'])->name('register.masyarakat');
    Route::post('register-masyarakat', [AuthController::class, 'registerMasyarakat'])->name('register.masyarakat.store');

    // Logout (umum untuk semua guard)
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD & FITUR MASYARAKAT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->prefix('masyarakat')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.masyarakat');
    })->name('dashboard.masyarakat');

    Route::resource('pengaduan', PengaduanController::class);
});

/*
|--------------------------------------------------------------------------
| DASHBOARD & FITUR PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:petugas'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.petugas');
    })->name('dashboard.petugas');

    Route::get('/pengaduan', [PengaduanController::class, 'indexPetugas'])->name('petugas.pengaduan.index');
    Route::patch('/pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])->name('petugas.pengaduan.updateStatus');
    Route::post('/tanggapan/{pengaduan}', [TanggapanController::class, 'store'])->name('petugas.tanggapan.store');
});
