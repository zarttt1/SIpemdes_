@extends('layouts.app')

@section('title', 'Dashboard Petugas - SIPEMDES')

@section('content')
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-lg-8">
            <h2 class="fw-bold text-primary mb-1">
                Dashboard Petugas
            </h2>
            <p class="text-muted mb-0">
                Selamat datang, <strong>{{ auth('petugas')->user()->nama }}</strong>
            </p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-clipboard-data" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total Pengaduan</h6>
                    <h3 class="fw-bold text-primary mb-0">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-plus-circle" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Pengaduan Baru</h6>
                    <h3 class="fw-bold text-info mb-0">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-hourglass-split" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Sedang Diproses</h6>
                    <h3 class="fw-bold text-warning mb-0">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Sudah Selesai</h6>
                    <h3 class="fw-bold text-success mb-0">0</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Pengaduan --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-semibold">Pengaduan Terbaru</h5>
            <a href="#" class="btn btn-sm btn-outline-primary">
                Lihat Semua
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama Masyarakat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                Belum ada pengaduan yang masuk.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
