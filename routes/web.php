<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\TanggapanController;
use App\Http\Controllers\MasyarakatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
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

    // 1. Login Konvensional (Username & Password)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'processLogin'])->name('login.process');

    // 2. Login Google (OAUTH)
    Route::get('google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // 3. Register masyarakat
    Route::get('register-masyarakat', [AuthController::class, 'showMasyarakatRegister'])->name('register.masyarakat');
    Route::post('register-masyarakat', [AuthController::class, 'registerMasyarakat'])->name('register.masyarakat.store');

    // 4. Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD & FITUR MASYARAKAT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->prefix('masyarakat')->group(function () {
    Route::get('/dashboard', [MasyarakatController::class, 'dashboard'])->name('dashboard.masyarakat');

    // CRUD Pengaduan (Resource)
    Route::resource('pengaduan', PengaduanController::class);

    // FITUR BARU: Lihat Riwayat Tanggapan (Chat Style)
    Route::get('/pengaduan/{id}/tanggapan', [PengaduanController::class, 'showTanggapan'])
        ->name('pengaduan.tanggapan');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD & FITUR PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:petugas', 'petugas.active'])->prefix('petugas')->group(function () {

    // Dashboard petugas
    Route::get('/dashboard', [PengaduanController::class, 'indexPetugas'])->name('dashboard.petugas');

    // List pengaduan
    Route::get('/pengaduan', [PengaduanController::class, 'indexPetugas'])->name('petugas.pengaduan.index');

    // Detail Pengaduan (Read Only)
    Route::get('/pengaduan/{id}', [PengaduanController::class, 'showPetugas'])->name('petugas.pengaduan.show');

    // === FITUR BARU: Page Khusus Beri Tanggapan ===
    Route::get('/pengaduan/{id}/tanggapan', [PengaduanController::class, 'createTanggapan'])
        ->name('petugas.pengaduan.tanggapan');

    // Aksi Update Status & Kirim Tanggapan
    Route::patch('/pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])->name('petugas.pengaduan.updateStatus');
    
    // (Opsional) Route khusus store tanggapan jika dipisah
    Route::post('/tanggapan/{pengaduan}', [TanggapanController::class, 'store'])->name('petugas.tanggapan.store');

    // Profile & Laporan
    Route::get('/profile', [PetugasController::class, 'profile'])->name('petugas.profile');
    Route::get('/profile/edit', [PetugasController::class, 'editProfile'])->name('petugas.profile.edit');
    Route::put('/profile', [PetugasController::class, 'updateProfile'])->name('petugas.profile.update');
    Route::get('/laporan', [PetugasController::class, 'laporan'])->name('petugas.laporan');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD & FITUR ADMIN / OPERATOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:petugas', 'petugas.active', 'admin.level'])->prefix('admin')->group(function () {

    // Dashboard admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // ========== PENGADUAN (ADMIN) ==========
    Route::get('/pengaduan', [PengaduanController::class, 'indexAdmin'])->name('admin.pengaduan.index');
    Route::get('/pengaduan/{id}', [PengaduanController::class, 'showAdmin'])->name('admin.pengaduan.show');
    Route::get('/pengaduan/{id}/edit', [PengaduanController::class, 'editAdmin'])->name('admin.pengaduan.edit');
    Route::put('/pengaduan/{id}', [PengaduanController::class, 'updateAdmin'])->name('admin.pengaduan.update');
    Route::delete('/pengaduan/{id}', [PengaduanController::class, 'destroyAdmin'])->name('admin.pengaduan.destroy');

    // ========== MASYARAKAT ==========
    Route::get('/masyarakat', [AdminController::class, 'indexMasyarakat'])->name('admin.masyarakat.index');
    Route::get('/masyarakat/create', [AdminController::class, 'createMasyarakat'])->name('admin.masyarakat.create');
    Route::post('/masyarakat', [AdminController::class, 'storeMasyarakat'])->name('admin.masyarakat.store');
    Route::get('/masyarakat/{id}', [AdminController::class, 'showMasyarakat'])->name('admin.masyarakat.show');
    Route::get('/masyarakat/{id}/edit', [AdminController::class, 'editMasyarakat'])->name('admin.masyarakat.edit');
    Route::put('/masyarakat/{id}', [AdminController::class, 'updateMasyarakat'])->name('admin.masyarakat.update');
    Route::delete('/masyarakat/{id}', [AdminController::class, 'destroyMasyarakat'])->name('admin.masyarakat.destroy');

    // ========== PETUGAS ==========
    Route::get('/petugas', [AdminController::class, 'indexPetugas'])->name('admin.petugas.index');
    Route::get('/petugas/create', [AdminController::class, 'createPetugas'])->name('admin.petugas.create');
    Route::post('/petugas', [AdminController::class, 'storePetugas'])->name('admin.petugas.store');
    Route::get('/petugas/{id}', [AdminController::class, 'showPetugas'])->name('admin.petugas.show');
    Route::get('/petugas/{id}/edit', [AdminController::class, 'editPetugas'])->name('admin.petugas.edit');
    Route::put('/petugas/{id}', [AdminController::class, 'updatePetugas'])->name('admin.petugas.update');
    Route::delete('/petugas/{id}', [AdminController::class, 'destroyPetugas'])->name('admin.petugas.destroy');

    // ========== TANGGAPAN (HAPUS/EDIT OLEH ADMIN) ==========
    Route::get('/tanggapan', [AdminController::class, 'indexTanggapan'])->name('admin.tanggapan.index');
    Route::get('/tanggapan/{id}', [AdminController::class, 'showTanggapan'])->name('admin.tanggapan.show');
    
    Route::get('/tanggapan/{id}/edit', [TanggapanController::class, 'edit'])->name('admin.tanggapan.edit');
    Route::put('/tanggapan/{id}', [TanggapanController::class, 'update'])->name('admin.tanggapan.update');
    Route::delete('/tanggapan/{id}', [AdminController::class, 'destroyTanggapan'])->name('admin.tanggapan.destroy');
});