<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\TanggapanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::get('register-masyarakat', [AuthController::class, 'showMasyarakatRegister'])->name('register.masyarakat');
    Route::post('register-masyarakat', [AuthController::class, 'registerMasyarakat'])->name('register.masyarakat.store');
    
    Route::get('login-masyarakat', [AuthController::class, 'showMasyarakatLogin'])->name('login.masyarakat');
    Route::post('login-masyarakat', [AuthController::class, 'loginMasyarakat']);
    
    Route::get('login-petugas', [AuthController::class, 'showPetugasLogin'])->name('login.petugas');
    Route::post('login-petugas', [AuthController::class, 'loginPetugas']);
    
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Masyarakat Routes
Route::middleware(['auth:web'])->prefix('masyarakat')->group(function () {
    Route::get('dashboard', function () {
        return view('masyarakat.dashboard');
    })->name('masyarakat.dashboard');
    
    Route::resource('pengaduan', PengaduanController::class);
});

// Petugas Routes
Route::middleware(['auth:petugas'])->prefix('petugas')->group(function () {
    Route::get('dashboard', function () {
        return view('petugas.dashboard');
    })->name('petugas.dashboard');
    
    Route::get('pengaduan', [PengaduanController::class, 'indexPetugas'])->name('petugas.pengaduan.index');
    Route::patch('pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])->name('pengaduan.updateStatus');
    Route::post('tanggapan/{pengaduan}', [TanggapanController::class, 'store'])->name('tanggapan.store');
});

// Public Route for viewing complaints
Route::get('pengaduan/{pengaduan}', [PengaduanController::class, 'show'])->name('pengaduan.show');

Route::get('/login-masyarakat', function () {
    return redirect()->route('login.masyarakat');
});
