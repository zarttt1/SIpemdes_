@extends('layouts.app')

@section('title', 'Dashboard Petugas - SIPEMDES')

@section('content')

<style>
/* ----------------------------------------------------------
   GLOBAL
---------------------------------------------------------- */
.dashboard-container {
    background: linear-gradient(135deg, #eef3f9 0%, #f9fbff 50%, #eef2ff 100%);
    padding: 2.8rem 1rem 4rem;
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
}

/* ----------------------------------------------------------
   HEADER
---------------------------------------------------------- */
.dashboard-header {
    margin-bottom: 2.8rem;
    padding-left: 1.1rem;
    border-left: 5px solid #2563eb;
    background: #ffffffaa;
    backdrop-filter: blur(6px);
    padding: 1rem 1.2rem;
    border-radius: 12px;
    box-shadow: 0px 6px 18px rgba(0,0,0,0.06);
}

.dashboard-header h2 {
    font-size: 2.35rem;
    font-weight: 700;
    color: #0f172a;
}

.dashboard-header p {
    color: #64748b;
    margin-top: .25rem;
    font-size: .96rem;
}

/* ----------------------------------------------------------
   STATISTIC CARDS
---------------------------------------------------------- */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.7rem;
    margin-bottom: 2.8rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.92);
    border-radius: 20px;
    padding: 2rem 1.5rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 12px 28px rgba(0,0,0,0.07);
    position: relative;
    overflow: hidden;
    transition: 0.3s ease;
    backdrop-filter: blur(6px);
}

.stat-card::after {
    content: '';
    position: absolute;
    top: -30%;
    right: -30%;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(37, 99, 235, 0.08);
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.1);
}

.stat-icon {
    font-size: 2.6rem;
    margin-bottom: .7rem;
}

.stat-label {
    font-size: .92rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: .4rem;
}

.stat-value {
    font-size: 2.4rem;
    font-weight: 800;
}

/* ----------------------------------------------------------
   TABLE WRAPPER
---------------------------------------------------------- */
.table-wrapper {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    overflow: hidden;
}

.table-wrapper .header {
    padding: 1.6rem 1.8rem;
    border-bottom: 1px solid #e2e8f0;
    background: linear-gradient(to right, #f1f5f9, #eaefff);
}

.table-wrapper h5 {
    margin: 0;
    font-size: 1.45rem;
    font-weight: 700;
    color: #1e293b;
}

/* ----------------------------------------------------------
   TABLE
---------------------------------------------------------- */
.table thead th {
    background: #f1f5f9;
    padding: 1.1rem;
    text-transform: uppercase;
    font-size: .74rem;
    font-weight: 700;
    letter-spacing: .04em;
    color: #475569;
    border-bottom: 1px solid #e2e8f0;
}

.table tbody tr {
    transition: 0.2s ease;
}

.table tbody tr:hover {
    background: #f8fafc;
}

.table tbody td {
    padding: 1rem;
    font-size: .93rem;
    border-bottom: 1px solid #e2e8f0;
}

/* STATUS BADGES */
.badge-status {
    padding: .48rem .9rem;
    border-radius: 16px;
    font-size: .76rem;
    font-weight: 700;
}

.bg-wait { background: #fff7d6; color: #9a6800; }
.bg-proc { background: #dbeafe; color: #1e3a8a; }
.bg-done { background: #d1fae5; color: #065f46; }

/* ----------------------------------------------------------
   ACTION BUTTONS
---------------------------------------------------------- */
.action-buttons {
    display: flex;
    gap: .6rem;
    justify-content: center;
}

.btn-action {
    padding: 7px 16px;
    border-radius: 10px;
    font-size: .84rem;
    display: flex;
    align-items: center;
    gap: 7px;
    border: none;
    color: white;
    transition: .25s;
    font-weight: 500;
}

.btn-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.btn-detail { background: #475569; }
.btn-detail:hover { background: #334155; }

.btn-response { background: #2563eb; }
.btn-response:hover { background: #1d4ed8; }

.btn-static {
    background: #16a34a;
    cursor: default;
}

/* EMPTY STATE */
.empty-state {
    padding: 4rem 1rem;
    text-align: center;
    color: #94a3b8;
}

.empty-state i {
    opacity: .75;
}

@media (max-width: 768px) {
    .btn-action span { display: none; }
    .dashboard-header h2 { font-size: 1.7rem; }
}
</style>


<div class="dashboard-container">

    {{-- HEADER --}}
    <div class="dashboard-header">
        <h2>Dashboard Petugas</h2>
        <p>Selamat bekerja, <strong>{{ optional(auth('petugas')->user())->nama ?? 'Petugas' }}</strong></p>
    </div>

    {{-- STATISTICS --}}
    <div class="stats-grid">

        <div class="stat-card">
            <i class="bi bi-clipboard-data text-primary stat-icon"></i>
            <div class="stat-label">Total Pengaduan</div>
            <div class="stat-value text-primary">{{ $totalPengaduan ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <i class="bi bi-exclamation-circle text-warning stat-icon"></i>
            <div class="stat-label">Menunggu</div>
            <div class="stat-value text-warning">{{ $pengaduanBaru ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <i class="bi bi-hourglass-split text-info stat-icon"></i>
            <div class="stat-label">Proses</div>
            <div class="stat-value text-info">{{ $diproses ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <i class="bi bi-check-circle-fill text-success stat-icon"></i>
            <div class="stat-label">Selesai</div>
            <div class="stat-value text-success">{{ $selesai ?? 0 }}</div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="table-wrapper">

        <div class="header">
            <h5>Daftar Pengaduan Terbaru</h5>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">

                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Pelapor</th>
                        <th>Isi Laporan</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pengaduan ?? [] as $item)
                    <tr>

                        <td class="text-center fw-bold text-secondary">
                            #{{ $item->id_pengaduan }}
                        </td>

                        <td>
                            <div class="fw-semibold">{{ $item->masyarakat->nama ?? 'User Terhapus' }}</div>
                            <div class="small text-muted">{{ $item->masyarakat->nik ?? '-' }}</div>
                        </td>

                        <td>
                            <span class="d-inline-block text-truncate" style="max-width: 260px;">
                                {{ Str::limit($item->isi_laporan, 60) }}
                            </span>
                        </td>

                        <td class="text-center text-muted">
                            {{ \Carbon\Carbon::parse($item->tanggal_pengaduan)->format('d/m/Y') }}
                        </td>

                        <td class="text-center">
                            @if($item->status == 'menunggu')
                                <span class="badge-status bg-wait">Menunggu</span>
                            @elseif($item->status == 'proses')
                                <span class="badge-status bg-proc">Proses</span>
                            @else
                                <span class="badge-status bg-done">Selesai</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="action-buttons">

                                <a href="{{ route('petugas.pengaduan.show', $item->id_pengaduan) }}"
                                   class="btn-action btn-detail">
                                    <i class="bi bi-eye"></i> <span>Detail</span>
                                </a>

                                @if($item->status != 'selesai')
                                    <a href="{{ route('petugas.pengaduan.tanggapan', $item->id_pengaduan) }}"
                                       class="btn-action btn-response">
                                        <i class="bi bi-chat-text"></i> <span>Tanggapi</span>
                                    </a>
                                @else
                                    <span class="btn-action btn-static">
                                        <i class="bi bi-check-circle"></i> <span>Selesai</span>
                                    </span>
                                @endif

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="bi bi-inbox fs-1"></i>
                            <div class="mt-2">Tidak ada pengaduan saat ini.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if(method_exists($pengaduan, 'links'))
            <div class="p-3 border-top">
                {{ $pengaduan->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
