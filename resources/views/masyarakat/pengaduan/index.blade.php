@extends('layouts.app')

@section('title', 'Daftar Pengaduan')

@section('content')

<style>
    body {
        background: #eef2f7 !important;
        font-family: "Poppins", sans-serif;
    }

    .page-title {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        padding: 25px 30px;
        border-radius: 14px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        color: white;
    }

    .card-custom {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        box-shadow: 0px 6px 18px rgba(0,0,0,0.065);
    }

    .table thead th {
        text-transform: uppercase;
        letter-spacing: .03em;
        font-size: .75rem;
        font-weight: 600;
        background: #f1f5f9 !important;
        color: #475569 !important;
    }

    .table-hover tbody tr:hover {
        background: #f8fafc;
    }

    .badge-modern {
        padding: 8px 14px !important;
        border-radius: 8px;
        font-weight: 600;
        font-size: .75rem;
    }

    .btn-action {
        border-radius: 10px !important;
        font-weight: 500 !important;
    }

    .empty-state {
        padding: 60px 0;
        color: #94a3b8;
    }
</style>

<div class="container mt-5">

    {{-- HEADER BAGUS --}}
    <div class="page-title mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Daftar Pengaduan Saya</h2>
            <p class="mb-0 opacity-75">Semua pengaduan yang pernah kamu kirimkan</p>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- CARD UTAMA --}}
    <div class="card card-custom">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Isi Laporan</th>
                            <th width="15%">Foto</th>
                            <th width="15%">Status</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pengaduan as $i => $item)
                            <tr>
                                <td class="text-center fw-bold text-secondary">
                                    {{ $i + 1 }}
                                </td>

                                {{-- LAPORAN --}}
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 310px;" title="{{ $item->isi_laporan }}">
                                        {{ Str::limit($item->isi_laporan, 60) }}
                                    </span>
                                </td>

                                {{-- FOTO --}}
                                <td class="text-center">
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" 
                                             class="rounded shadow-sm border"
                                             width="60" height="60"
                                             style="object-fit: cover;">
                                    @else
                                        <span class="badge bg-light text-secondary border">Tidak ada</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="text-center">
                                    @if ($item->status == 'menunggu')
                                        <span class="badge bg-warning text-dark badge-modern">Menunggu</span>
                                    @elseif ($item->status == 'proses')
                                        <span class="badge bg-info text-white badge-modern">Proses</span>
                                    @else
                                        <span class="badge bg-success text-white badge-modern">Selesai</span>
                                    @endif
                                </td>

                                {{-- TANGGAL --}}
                                <td class="text-center text-muted small">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    <div class="d-flex flex-column gap-2 px-2">

                                        {{-- DETAIL --}}
                                        <a href="{{ route('pengaduan.show', $item->id_pengaduan) }}"
                                           class="btn btn-sm btn-outline-primary btn-action">
                                            Detail
                                        </a>

                                        {{-- TANGGAPAN --}}
                                        <a href="{{ route('pengaduan.tanggapan', $item->id_pengaduan) }}"
                                           class="btn btn-sm btn-primary btn-action position-relative text-white">
                                            Tanggapan
                                            
                                            @if ($item->tanggapan->count() > 0)
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                                            @endif
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center empty-state">
                                    <div class="fs-1">📭</div>
                                    <p class="mt-2 mb-0">Belum ada pengaduan yang dibuat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if(method_exists($pengaduan, 'links'))
            <div class="card-footer bg-white py-3 border-0">
                <div class="d-flex justify-content-center">
                    {{ $pengaduan->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- KEMBALI --}}
    <div class="text-end mt-4">
        <a href="{{ route('dashboard.masyarakat') }}" class="btn btn-secondary px-4 py-2 shadow-sm">
            Kembali ke Dashboard
        </a>
    </div>

</div>
@endsection
