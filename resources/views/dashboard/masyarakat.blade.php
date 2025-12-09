@extends('layouts.app')

@section('title', 'Dashboard Masyarakat - SIPEMDES')

@section('content')

<style>
    body {
        background: #f1f5f9 !important;
        font-family: "Poppins", sans-serif;
        overflow-y: scroll;
    }

    .page-wrapper {
        padding-top: 30px;
        padding-bottom: 40px;
    }

    .header-card {
        background: linear-gradient(135deg, #1e40af, #3b82f6);
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        color: white;
    }

    .stat-card {
        border-radius: 14px;
        padding: 22px;
        border: 1px solid #e2e8f0;
        background: white;
        text-align: center;
        transition: .2s;
        box-shadow: 0 3px 12px rgba(0,0,0,0.05);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }

    .table thead th {
        background: #f8fafc !important;
        text-transform: uppercase;
        font-size: .75rem;
        color: #475569;
        letter-spacing: .03em;
        font-weight: 600;
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table td {
        padding: 14px;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .table-hover tbody tr:hover {
        background: #f1f5f9;
    }

    .no-data {
        color: #94a3b8;
        padding: 50px 0;
    }
</style>

<div class="container page-wrapper">

    {{-- HEADER --}}
    <div class="header-card mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h2 class="mb-1">Dashboard Pengaduan</h2>
            <p class="mb-0 opacity-75">
                Selamat datang, <strong>{{ auth('web')->user()->nama }}</strong>
            </p>
        </div>

        <a href="{{ route('pengaduan.create') }}"
            class="btn btn-light fw-semibold px-4 py-2 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Buat Pengaduan
        </a>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- HITUNG STATISTIK OTOMATIS --}}
    @php
        $total = $pengaduan->count();
        $baru = $pengaduan->where('status', 'menunggu')->count();
        $diproses = $pengaduan->where('status', 'proses')->count();
        $selesai = $pengaduan->where('status', 'selesai')->count();
    @endphp

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <h6>Total Pengaduan</h6>
                <h3 class="text-primary">{{ $total }}</h3>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <h6>Baru</h6>
                <h3 class="text-info">{{ $baru }}</h3>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <h6>Diproses</h6>
                <h3 class="text-warning">{{ $diproses }}</h3>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <h6>Selesai</h6>
                <h3 class="text-success">{{ $selesai }}</h3>
            </div>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Daftar Pengaduan Saya</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="30%">Isi Laporan</th>
                            <th class="text-center" width="15%">Foto</th>
                            <th class="text-center" width="15%">Status</th>
                            <th class="text-center" width="15%">Tanggal</th>
                            <th class="text-center" width="20%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse ($pengaduan as $i => $item)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $i + 1 }}</td>

                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 260px;">
                                    {{ Str::limit($item->isi_laporan, 70) }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}"
                                         width="55" height="55"
                                         class="rounded shadow-sm border"
                                         style="object-fit: cover;">
                                @else
                                    <span class="badge bg-light text-secondary border">No Img</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($item->status == 'menunggu')
                                    <span class="badge bg-warning text-dark rounded-pill">Menunggu</span>
                                @elseif ($item->status == 'proses')
                                    <span class="badge bg-info text-white rounded-pill">Proses</span>
                                @else
                                    <span class="badge bg-success rounded-pill">Selesai</span>
                                @endif
                            </td>

                            <td class="text-center text-muted small">
                                {{ $item->created_at->format('d/m/Y') }}
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('pengaduan.show', $item->id_pengaduan) }}"
                                       class="btn btn-sm btn-outline-primary">Detail</a>

                                    <a href="{{ route('pengaduan.tanggapan', $item->id_pengaduan) }}"
                                       class="btn btn-sm {{ $item->tanggapan->count() ? 'btn-success text-white' : 'btn-outline-secondary' }}">
                                        Chat
                                    </a>

                                    @if ($item->status == 'menunggu')
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                Opsi
                                            </button>

                                            <ul class="dropdown-menu shadow-sm">
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('pengaduan.edit', $item->id_pengaduan) }}">
                                                        Edit Laporan
                                                    </a>
                                                </li>
                                                <li>
                                                    <form method="POST"
                                                          action="{{ route('pengaduan.destroy', $item->id_pengaduan) }}">
                                                        @csrf @method('DELETE')
                                                        <button class="dropdown-item text-danger"
                                                                onclick="return confirm('Hapus pengaduan ini?')">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center no-data">
                                <div style="font-size: 40px;">📭</div>
                                <p class="mt-2 mb-0">Belum ada pengaduan yang dibuat.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        @if (method_exists($pengaduan, 'links'))
            <div class="card-footer bg-white">
                {{ $pengaduan->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
