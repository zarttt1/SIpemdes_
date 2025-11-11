@extends('layouts.app')

@section('title', 'Dashboard Petugas - SIPEMDES')

@section('content')
<style>
/* ===================== */
/*  Dashboard Petugas UI */
/* ===================== */

.dashboard-petugas {
    background: #f8fafc;
    padding: 2rem 1rem 3rem;
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
}

/* Header */
.dashboard-petugas h2 {
    color: #1e40af;
    font-weight: 700;
    font-size: 1.8rem;
}

.dashboard-petugas p {
    color: #6b7280;
    font-size: 0.95rem;
}

/* Statistik Card */
.dashboard-petugas .stat-card {
    background: linear-gradient(145deg, #ffffff, #f1f5f9);
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    padding: 1.5rem 1rem;
    text-align: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    transition: all 0.25s ease-in-out;
}

.dashboard-petugas .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
}

.dashboard-petugas .stat-card i {
    font-size: 2rem;
    margin-bottom: 0.6rem;
}

.dashboard-petugas .stat-card h6 {
    color: #6b7280;
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
}

.dashboard-petugas .stat-card h3 {
    font-weight: 700;
    font-size: 1.7rem;
}

/* Warna ikonik tiap card */
.text-primary { color: #1e40af !important; }
.text-info { color: #0ea5e9 !important; }
.text-warning { color: #f59e0b !important; }
.text-success { color: #10b981 !important; }

/* Header tabel */
.dashboard-petugas .dashboard-header {
    background: linear-gradient(90deg, #1e40af, #1d4ed8);
    color: #ffffff;
    border: none;
    border-radius: 10px 10px 0 0;
    padding: 0.9rem 1.2rem;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.dashboard-petugas .dashboard-header h5 {
    font-weight: 600;
    font-size: 1.05rem;
}

.dashboard-petugas .btn-outline-light {
    border-color: rgba(255, 255, 255, 0.7);
    color: white;
    transition: 0.2s ease;
    font-size: 0.85rem;
    border-radius: 6px;
    padding: 0.4rem 0.8rem;
}

.dashboard-petugas .btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

/* Tabel */
.dashboard-petugas table thead {
    background-color: #f1f5f9;
    color: #334155;
    font-weight: 600;
    font-size: 0.9rem;
}

.dashboard-petugas table th,
.dashboard-petugas table td {
    padding: 0.9rem 1rem;
    vertical-align: middle;
}

.dashboard-petugas table tbody tr {
    background-color: #ffffff;
    transition: 0.25s ease;
    border-bottom: 1px solid #f1f5f9;
}

.dashboard-petugas table tbody tr:hover {
    background-color: #f8fafc;
}

.dashboard-petugas table td.text-muted {
    color: #9ca3af !important;
    font-style: italic;
}

/* Empty state */
.dashboard-petugas .empty-state {
    color: #94a3b8;
    font-style: italic;
    padding: 3rem 0;
}

/* Responsif */
@media (max-width: 768px) {
    .dashboard-petugas {
        padding: 1rem;
    }
    .dashboard-petugas h2 {
        font-size: 1.4rem;
    }
}
</style>

<div class="dashboard-petugas">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-1">Dashboard Petugas</h2>
            <p class="mb-0">
                Selamat datang, <strong>{{ auth('petugas')->user()->nama }}</strong>
            </p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <i class="bi bi-clipboard-data text-primary"></i>
                <h6>Total Pengaduan</h6>
                <h3 class="text-primary">0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <i class="bi bi-plus-circle text-info"></i>
                <h6>Pengaduan Baru</h6>
                <h3 class="text-info">0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <i class="bi bi-hourglass-split text-warning"></i>
                <h6>Sedang Diproses</h6>
                <h3 class="text-warning">0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <i class="bi bi-check-circle text-success"></i>
                <h6>Sudah Selesai</h6>
                <h3 class="text-success">0</h3>
            </div>
        </div>
    </div>

    {{-- Tabel Pengaduan --}}
    <div class="card border-0 shadow-sm">
        <div class="dashboard-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pengaduan Terbaru</h5>
            <a href="#" class="btn btn-sm btn-outline-light">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
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
                            <td colspan="5" class="text-center empty-state">
                                Belum ada pengaduan yang masuk.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
