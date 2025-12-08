@extends('layouts.app')

@section('title', 'Dashboard Petugas - SIPEMDES')

@section('content')
<style>
    .dashboard-petugas {
        background: #f8fafc;
        padding: 2rem 1rem 3rem;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        color: #1e293b;
    }

    /* Header */
    .dashboard-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .dashboard-header h2 {
        color: #1e40af;
        font-weight: 700;
        font-size: 1.8rem;
    }

    .dashboard-header p {
        color: #64748b;
        margin-top: 0.25rem;
        font-size: 0.95rem;
    }

    /* Statistik Card */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.2rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.25s ease;
        text-align: center;
        border: 1px solid #e2e8f0;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
    }

    .stat-card i {
        font-size: 2rem;
        margin-bottom: 0.6rem;
        display: inline-block;
    }

    .stat-card h6 {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 0.4rem;
        font-weight: 500;
    }

    .stat-card h3 {
        font-weight: 700;
        font-size: 1.7rem;
        margin: 0;
    }

    .text-primary { color: #2563eb !important; }
    .text-warning { color: #f59e0b !important; }
    .text-success { color: #16a34a !important; }
    .text-info { color: #0ea5e9 !important; }

    /* Tabel & Card */
    .list-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .list-card .card-header {
        background: #fff;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-card .card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
        font-size: 1.1rem;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .empty-state {
        text-align: center;
        color: #94a3b8;
        padding: 4rem 1rem;
    }

    /* --- PERBAIKAN TOMBOL AKSI --- */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 8px; /* Jarak antar tombol */
    }

    /* Style Tombol dengan Teks */
    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: white;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        color: white;
    }

    /* Warna Tombol */
    .btn-detail {
        background-color: #64748b; /* Abu-abu Slate */
    }
    .btn-detail:hover { background-color: #475569; }

    .btn-response {
        background-color: #2563eb; /* Biru */
    }
    .btn-response:hover { background-color: #1d4ed8; }

    .btn-success-static {
        background-color: #10b981; /* Hijau */
        cursor: default;
    }
    
    @media (max-width: 768px) {
        .dashboard-petugas { padding: 1rem; }
        .dashboard-header h2 { font-size: 1.4rem; }
        .btn-action span { display: none; } /* Sembunyikan teks di HP kecil */
    }
</style>

<div class="dashboard-petugas">

    {{-- Header --}}
    <div class="dashboard-header">
        <div>
            <h2>Dashboard Petugas</h2>
            <p class="mb-0">
                Selamat bekerja, <strong>{{ optional(auth('petugas')->user())->nama ?? 'Petugas' }}</strong>
            </p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="stats-grid">
        <div class="stat-card">
            <i class="bi bi-clipboard-data text-primary"></i>
            <h6>Total Pengaduan</h6>
            <h3 class="text-primary">{{ $totalPengaduan ?? 0 }}</h3>
        </div>
        <div class="stat-card">
            <i class="bi bi-exclamation-circle text-warning"></i>
            <h6>Menunggu</h6>
            <h3 class="text-warning">{{ $pengaduanBaru ?? 0 }}</h3>
        </div>
        <div class="stat-card">
            <i class="bi bi-hourglass-split text-info"></i>
            <h6>Proses</h6>
            <h3 class="text-info">{{ $diproses ?? 0 }}</h3>
        </div>
        <div class="stat-card">
            <i class="bi bi-check-circle-fill text-success"></i>
            <h6>Selesai</h6>
            <h3 class="text-success">{{ $selesai ?? 0 }}</h3>
        </div>
    </div>

    {{-- Tabel Pengaduan --}}
    <div class="card list-card">
        <div class="card-header">
            <h5>Daftar Pengaduan Terbaru</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">ID</th>
                            <th width="20%">Pelapor</th>
                            <th width="35%">Isi Laporan</th>
                            <th class="text-center" width="15%">Tanggal</th>
                            <th class="text-center" width="10%">Status</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pengaduan ?? [] as $item)
                        <tr>
                            <td class="text-center fw-bold text-secondary">{{ $item->id_pengaduan }}</td>

                            <td>
                                <div class="fw-bold">{{ $item->masyarakat->nama ?? 'User Terhapus' }}</div>
                                <div class="small text-muted">{{ $item->masyarakat->nik ?? '-' }}</div>
                            </td>

                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 250px;" title="{{ $item->isi_laporan }}">
                                    {{ Str::limit($item->isi_laporan, 50) }}
                                </span>
                            </td>

                            <td class="text-center text-muted">
                                {{ \Carbon\Carbon::parse($item->tanggal_pengaduan)->format('d/m/Y') }}
                            </td>

                            <td class="text-center">
                                @if($item->status == 'menunggu')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu</span>
                                @elseif($item->status == 'proses') 
                                    <span class="badge bg-info text-white rounded-pill px-3">Proses</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3">Selesai</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="action-buttons">
                                    {{-- 1. Tombol Detail --}}
                                    <a href="{{ route('petugas.pengaduan.show', $item->id_pengaduan) }}" 
                                       class="btn-action btn-detail" 
                                       title="Lihat Detail">
                                        <i class="bi bi-eye"></i> <span>Detail</span>
                                    </a>

                                    {{-- 2. Tombol Tanggapan --}}
                                    @if($item->status != 'selesai')
                                        <a href="{{ route('petugas.pengaduan.tanggapan', $item->id_pengaduan) }}" 
                                           class="btn-action btn-response" 
                                           title="Beri Tanggapan">
                                            <i class="bi bi-chat-text"></i> <span>Tanggapi</span>
                                        </a>
                                    @else
                                        <span class="btn-action btn-success-static">
                                            <i class="bi bi-check-circle"></i> <span>Selesai</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="bi bi-inbox fs-1 mb-3 d-block text-gray-300"></i>
                                Tidak ada pengaduan yang perlu diproses saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if(method_exists($pengaduan, 'links'))
                <div class="p-3 border-top">
                    {{ $pengaduan->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection