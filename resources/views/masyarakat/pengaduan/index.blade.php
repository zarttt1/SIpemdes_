@extends('layouts.app')

@section('title', 'Daftar Pengaduan')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Daftar Pengaduan Saya</h2>
        {{-- Tombol Buat Pengaduan Dihapus Sesuai Permintaan --}}
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="bg-primary text-white text-center">
                        <tr>
                            <th class="py-3" width="5%">No</th>
                            <th class="py-3" width="35%">Isi Laporan</th>
                            <th class="py-3" width="15%">Foto</th>
                            <th class="py-3" width="15%">Status</th>
                            <th class="py-3" width="15%">Tanggal</th>
                            <th class="py-3" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengaduan as $i => $item)
                            <tr>
                                <td class="text-center fw-bold text-secondary">{{ $i + 1 }}</td>
                                
                                {{-- Isi Laporan --}}
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 300px;" title="{{ $item->isi_laporan }}">
                                        {{ Str::limit($item->isi_laporan, 60) }}
                                    </span>
                                </td>
                                
                                {{-- Foto --}}
                                <td class="text-center">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" 
                                             alt="foto" 
                                             class="img-thumbnail rounded shadow-sm" 
                                             width="60" height="60" 
                                             style="object-fit: cover;">
                                    @else
                                        <span class="badge bg-light text-secondary border fw-normal">Tidak ada</span>
                                    @endif
                                </td>
                                
                                {{-- Status --}}
                                <td class="text-center">
                                    @if($item->status == 'menunggu')
                                        <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu</span>
                                    @elseif($item->status == 'proses')
                                        <span class="badge bg-info text-white rounded-pill px-3">Proses</span>
                                    @else
                                        <span class="badge bg-success rounded-pill px-3">Selesai</span>
                                    @endif
                                </td>

                                {{-- Tanggal --}}
                                <td class="text-center small text-muted">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                                
                                {{-- Aksi (Tombol Diperbaiki) --}}
                                <td class="text-center">
                                    <div class="d-flex flex-column gap-2 px-2">
                                        {{-- 1. Tombol Detail --}}
                                        <a href="{{ route('pengaduan.show', $item->id_pengaduan) }}" 
                                           class="btn btn-sm btn-outline-primary w-100">
                                            Detail
                                        </a>

                                        {{-- 2. Tombol Lihat Tanggapan --}}
                                        <a href="{{ route('pengaduan.tanggapan', $item->id_pengaduan) }}" 
                                           class="btn btn-sm btn-primary w-100 position-relative text-white">
                                            Tanggapan
                                            {{-- Indikator Merah --}}
                                            @if($item->tanggapan->count() > 0)
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                    <span class="visually-hidden">Pesan baru</span>
                                                </span>
                                            @endif
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-2 fs-1">📭</div>
                                    <p class="mb-0">Belum ada pengaduan yang dibuat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        @if(method_exists($pengaduan, 'links'))
            <div class="card-footer bg-white border-0 py-3">
                {{ $pengaduan->links() }}
            </div>
        @endif
    </div>

    {{-- Tombol Kembali Dashboard --}}
    <div class="text-end mt-4">
        <a href="{{ route('dashboard.masyarakat') }}" class="btn btn-secondary shadow-sm">
             Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection