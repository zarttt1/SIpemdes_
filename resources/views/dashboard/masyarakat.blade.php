@extends('layouts.app')

@section('title', 'Dashboard Masyarakat - SIPEMDES')

@section('content')
<div class="container mt-5">
    {{-- Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-primary mb-1">Dashboard Pengaduan</h2>
            <p class="text-muted mb-0">Selamat datang, {{ auth('web')->user()->nama }}</p>
        </div>
        <a href="{{ route('pengaduan.create') }}" class="btn btn-primary px-4 py-2 shadow-sm">
            <i class="bi bi-plus-lg"></i> + Buat Pengaduan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Statistik Ringkas --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <h6 class="text-muted small text-uppercase fw-bold">Total</h6>
                <h3 class="fw-bold text-primary mb-0">{{ $total ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <h6 class="text-muted small text-uppercase fw-bold">Baru</h6>
                <h3 class="fw-bold text-info mb-0">{{ $baru ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <h6 class="text-muted small text-uppercase fw-bold">Proses</h6>
                <h3 class="fw-bold text-warning mb-0">{{ $diproses ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <h6 class="text-muted small text-uppercase fw-bold">Selesai</h6>
                <h3 class="fw-bold text-success mb-0">{{ $selesai ?? 0 }}</h3>
            </div>
        </div>
    </div>

    {{-- Tabel Pengaduan --}}
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Daftar Pengaduan Saya</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="text-center py-3" width="5%">No</th>
                            <th class="py-3" width="30%">Isi Laporan</th>
                            <th class="py-3 text-center" width="15%">Foto</th>
                            <th class="py-3 text-center" width="15%">Status</th>
                            <th class="py-3 text-center" width="15%">Tanggal</th>
                            <th class="py-3 text-center" width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengaduan as $i => $item)
                            <tr>
                                <td class="text-center fw-bold text-muted">{{ $i + 1 }}</td>
                                
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 250px;" title="{{ $item->isi_laporan }}">
                                        {{ Str::limit($item->isi_laporan, 60) }}
                                    </span>
                                </td>
                                
                                <td class="text-center">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" 
                                             alt="foto" 
                                             class="rounded border shadow-sm" 
                                             width="50" height="50" 
                                             style="object-fit: cover;">
                                    @else
                                        <span class="badge bg-light text-secondary border fw-normal">No Img</span>
                                    @endif
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

                                <td class="text-center text-muted small">
                                    {{ $item->created_at->format('d/m/Y') }}
                                </td>
                                
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        {{-- 1. Tombol Detail --}}
                                        <a href="{{ route('pengaduan.show', $item->id_pengaduan) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Lihat Detail">
                                            Detail
                                        </a>

                                        {{-- 2. Tombol Tanggapan (BUTTON KHUSUS) --}}
                                        {{-- Menggunakan teks 'Chat' atau 'Respon' agar tidak kosong --}}
                                        <a href="{{ route('pengaduan.tanggapan', $item->id_pengaduan) }}" 
                                           class="btn btn-sm position-relative {{ $item->tanggapan->count() > 0 ? 'btn-success text-white' : 'btn-outline-secondary' }}" 
                                           title="Lihat Tanggapan">
                                            Chat
                                            @if($item->tanggapan->count() > 0)
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            @endif
                                        </a>

                                        {{-- 3. Tombol Edit & Hapus (Dropdown agar rapi) --}}
                                        @if($item->status == 'menunggu')
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Opsi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('pengaduan.edit', $item->id_pengaduan) }}">
                                                            Edit Laporan
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('pengaduan.destroy', $item->id_pengaduan) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Hapus pengaduan ini?')">
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-2">📭</div>
                                    <p class="mb-0">Belum ada pengaduan yang dibuat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if(method_exists($pengaduan, 'links'))
            <div class="card-footer bg-white border-0 py-3">
                {{ $pengaduan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection