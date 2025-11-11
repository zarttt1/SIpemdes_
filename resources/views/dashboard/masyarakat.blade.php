@extends('layouts.app')

@section('title', 'Dashboard Masyarakat - SIPEMDES')

@section('content')
<style>
.dashboard-masyarakat {
    background: #f8fafc;
    padding: 2rem 1rem 3rem;
    font-family: 'Poppins', sans-serif;
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
    font-size: 1.75rem;
}

.dashboard-header p {
    color: #64748b;
    margin-top: 0.25rem;
    font-size: 0.95rem;
}

.dashboard-header .btn-primary {
    background: linear-gradient(90deg, #2563eb, #1d4ed8);
    border: none;
    border-radius: 8px;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    transition: all 0.25s ease;
}

.dashboard-header .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37,99,235,0.25);
}

/* Statistik */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.2rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.25s ease;
    text-align: center;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
}

.stat-card h6 {
    color: #6b7280;
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
}

.stat-card h2 {
    font-weight: 700;
    font-size: 1.8rem;
}

.text-primary { color: #2563eb !important; }
.text-warning { color: #f59e0b !important; }
.text-success { color: #16a34a !important; }
.text-info { color: #0ea5e9 !important; }

/* Daftar Pengaduan */
.list-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
}

.list-card .card-header {
    background: linear-gradient(90deg, #1e3a8a, #2563eb);
    color: #fff;
    padding: 1rem 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: none;
}

.list-card .card-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.05rem;
}

.list-card .card-body {
    background: #ffffff;
    padding: 2rem 1.5rem;
}

.empty-state {
    text-align: center;
    color: #6b7280;
    padding: 2rem 1rem;
}

.empty-state i {
    font-size: 3rem;
    color: #9ca3af;
    margin-bottom: 1rem;
}

/* Tabel */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}

.table th {
    background-color: #f1f5f9;
    font-weight: 600;
    color: #334155;
}

.table td:last-child {
    text-align: center;
}

/* Tombol Aksi */
.action-buttons {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    flex-wrap: nowrap;
}

.btn-sm {
    padding: 0.35rem 0.7rem;
    border-radius: 6px;
    font-size: 0.85rem;
    white-space: nowrap;
}

.btn-edit {
    background: #3b82f6;
    color: white;
    border: none;
}

.btn-edit:hover {
    background: #2563eb;
}

.btn-delete {
    background: #ef4444;
    color: white;
    border: none;
}

.btn-delete:hover {
    background: #dc2626;
}

/* Responsif */
@media (max-width: 640px) {
    .action-buttons {
        flex-wrap: wrap;
    }
}
</style>

<div class="dashboard-masyarakat">
    {{-- Header --}}
    <div class="dashboard-header">
        <div>
            <h2>Selamat Datang, {{ auth('web')->user()->nama }}</h2>
            <p>Pantau dan kelola pengaduan Anda dengan mudah.</p>
        </div>
        <a href="{{ route('pengaduan.create') }}" class="btn btn-primary shadow-sm">
            + Buat Pengaduan Baru
        </a>
    </div>

    {{-- Statistik --}}
    <div class="stats-grid">
        <div class="stat-card"><h6>Total Pengaduan</h6><h2 class="text-primary">{{ $total }}</h2></div>
        <div class="stat-card"><h6>Diproses</h6><h2 class="text-warning">{{ $diproses }}</h2></div>
        <div class="stat-card"><h6>Selesai</h6><h2 class="text-success">{{ $selesai }}</h2></div>
        <div class="stat-card"><h6>Baru</h6><h2 class="text-info">{{ $baru }}</h2></div>
    </div>

    {{-- Daftar Pengaduan --}}
    <div class="card list-card">
        <div class="card-header"><h5>Daftar Pengaduan Saya</h5></div>
        <div class="card-body">
            @if($pengaduan->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <p class="mb-1">Anda belum membuat pengaduan.</p>
                    <p>Klik tombol <strong>"Buat Pengaduan Baru"</strong> di atas untuk memulai.</p>
                </div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Isi Laporan</th>
                            <th>Foto</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengaduan as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->isi_laporan }}</td>
                            <td>
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="foto" width="70" class="rounded shadow-sm">
                                @else
                                    <small>- Tidak ada -</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    @if($item->status == 'diproses') bg-warning 
                                    @elseif($item->status == 'selesai') bg-success 
                                    @else bg-secondary @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('pengaduan.edit', $item->id_pengaduan) }}" class="btn btn-sm btn-edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('pengaduan.destroy', $item->id_pengaduan) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-delete"
                                            onclick="return confirm('Yakin ingin menghapus pengaduan ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
