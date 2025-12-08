@extends('layouts.app')

@section('title', 'Detail Pengaduan - SIPEMDES')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            {{-- TOMBOL KEMBALI --}}
            <div class="mb-3">
                <a href="{{ route('pengaduan.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>

            <div class="card shadow border-0">
                
                {{-- HEADER CARD --}}
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        {{ $pengaduan->id_pengaduan }} - Detail Pengaduan
                    </h5>
                    <span class="text-muted small">
                        Dilaporkan pada: {{ \Carbon\Carbon::parse($pengaduan->created_at)->translatedFormat('d F Y, H:i') }}
                    </span>
                </div>

                <div class="card-body p-4">
                    
                    {{-- STATUS --}}
                    <div class="mb-4">
                        <label class="fw-bold text-uppercase text-secondary small mb-1">Status Pengaduan</label>
                        <div>
                            @if($pengaduan->status == 'menunggu')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Verifikasi</span>
                            @elseif($pengaduan->status == 'proses')
                                <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Sedang Diproses</span>
                            @else
                                <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                            @endif
                        </div>
                    </div>

                    {{-- ISI LAPORAN --}}
                    <div class="mb-4">
                        <label class="fw-bold text-uppercase text-secondary small mb-1">Isi Laporan</label>
                        <div class="p-3 bg-light rounded border">
                            <p class="mb-0" style="white-space: pre-line;">{{ $pengaduan->isi_laporan }}</p>
                        </div>
                    </div>

                    {{-- FOTO BUKTI --}}
                    <div class="mb-4">
                        <label class="fw-bold text-uppercase text-secondary small mb-1">Foto Bukti</label>
                        <div class="mt-2">
                            @if(!empty($pengaduan->foto))
                                <img src="{{ asset('storage/' . $pengaduan->foto) }}" 
                                     alt="Foto pengaduan" 
                                     class="img-fluid rounded border shadow-sm" 
                                     style="max-height: 400px; width: auto;">
                            @else
                                <div class="alert alert-secondary d-inline-block px-3 py-2">
                                    <i class="bi bi-image-alt"></i> Tidak ada foto yang dilampirkan.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- TOMBOL AKSI (EDIT/HAPUS) --}}
                    {{-- Hanya muncul jika status masih 'menunggu' --}}
                    @if($pengaduan->status == 'menunggu')
                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('pengaduan.edit', $pengaduan->id_pengaduan) }}" class="btn btn-warning text-dark">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <form action="{{ route('pengaduan.destroy', $pengaduan->id_pengaduan) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini? Data tidak bisa dikembalikan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection