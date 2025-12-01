<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\TanggapanController;
use App\Http\Controllers\MasyarakatController;
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
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    // Login
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'processLogin'])->name('login.process');

    // Register masyarakat
    Route::get('register-masyarakat', [AuthController::class, 'showMasyarakatRegister'])->name('register.masyarakat');
    Route::post('register-masyarakat', [AuthController::class, 'registerMasyarakat'])->name('register.masyarakat.store');

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD & FITUR MASYARAKAT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->prefix('masyarakat')->group(function () {

    Route::get('/dashboard', [MasyarakatController::class, 'dashboard'])->name('dashboard.masyarakat');

    // CRUD pengaduan masyarakat
    Route::resource('pengaduan', PengaduanController::class);
});

/*
|--------------------------------------------------------------------------
| DASHBOARD & FITUR PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:petugas'])->prefix('petugas')->group(function () {

    // Dashboard petugas (list pengaduan)
    Route::get('/dashboard', [PengaduanController::class, 'indexPetugas'])
        ->name('dashboard.petugas');

    // List pengaduan
    Route::get('/pengaduan', [PengaduanController::class, 'indexPetugas'])
        ->name('petugas.pengaduan.index');

    // DETAIL pengaduan petugas (WAJIB di atas updateStatus biar tidak bentrok)
    Route::get('/pengaduan/show/{pengaduan}', [PengaduanController::class, 'showPetugas'])
        ->name('petugas.pengaduan.show');

    // Update status pengaduan
    Route::patch('/pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])
        ->name('petugas.pengaduan.updateStatus');

    // Tanggapan petugas
    Route::post('/tanggapan/{pengaduan}', [TanggapanController::class, 'store'])
        ->name('petugas.tanggapan.store');
});
