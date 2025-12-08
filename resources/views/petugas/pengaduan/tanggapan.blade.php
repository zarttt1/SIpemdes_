@extends('layouts.app')

@section('title', 'Beri Tanggapan - SIPEMDES')

@section('content')
<style>
    .response-container {
        max-width: 800px;
        margin: 0 auto;
        padding-bottom: 4rem;
    }
    
    /* Timeline Styles */
    .timeline-wrapper {
        position: relative;
        padding-left: 20px;
        border-left: 2px solid #e5e7eb;
        margin-left: 10px;
        margin-top: 1rem;
        margin-bottom: 2rem;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
        padding-left: 20px;
    }

    .timeline-dot {
        position: absolute;
        left: -29px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #3b82f6;
        border: 4px solid #fff;
        box-shadow: 0 0 0 2px #3b82f6;
    }

    /* Dot Merah untuk User */
    .timeline-dot.user-dot {
        background: #ef4444;
        box-shadow: 0 0 0 2px #ef4444;
    }

    .message-box {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        position: relative;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .message-box.user-message {
        background: #fff5f5; /* Merah muda sangat pudar */
        border-color: #feb2b2;
    }

    /* Sticky Action Panel */
    .action-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
        padding: 1.5rem;
        position: sticky;
        bottom: 20px;
    }
</style>

<div class="container mt-4 response-container">
    
    {{-- Header Simple --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('dashboard.petugas') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <span class="badge bg-light text-secondary border">Ticket #{{ $pengaduan->id_pengaduan }}</span>
    </div>

    {{-- RIWAYAT PERCAKAPAN --}}
    <h5 class="fw-bold text-secondary mb-3 ms-2">Riwayat Percakapan</h5>
    
    <div class="timeline-wrapper">
        
        {{-- 1. Laporan Awal User (Dimasukkan ke Timeline agar konteks tidak hilang) --}}
        <div class="timeline-item">
            <div class="timeline-dot user-dot"></div>
            <div class="message-box user-message shadow-sm">
                <div class="d-flex justify-content-between mb-2">
                    <strong class="text-danger">{{ $pengaduan->masyarakat->nama ?? 'Pelapor' }}</strong>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->format('d M Y, H:i') }}</small>
                </div>
                <p class="mb-0 text-dark fw-medium">"{{ $pengaduan->isi_laporan }}"</p>
                
                @if($pengaduan->foto)
                    <div class="mt-2 pt-2 border-top border-danger border-opacity-25">
                        <a href="{{ asset('storage/' . $pengaduan->foto) }}" target="_blank" class="text-decoration-none small text-danger">
                            <i class="bi bi-paperclip"></i> Lihat Foto Bukti
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. Balasan Petugas --}}
        @foreach($pengaduan->tanggapan as $item)
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="message-box shadow-sm">
                    <div class="d-flex justify-content-between mb-2">
                        <strong class="text-primary">{{ $item->petugas->nama ?? 'Petugas' }}</strong>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal_tanggapan)->diffForHumans() }}</small>
                    </div>
                    <p class="mb-0 text-secondary">{{ $item->isi_tanggapan }}</p>
                </div>
            </div>
        @endforeach

    </div>

    {{-- FORM INPUT TANGGAPAN --}}
    @if($pengaduan->status != 'selesai')
        <div class="action-panel mt-4">
            <form action="{{ route('petugas.pengaduan.updateStatus', $pengaduan->id_pengaduan) }}" method="POST">
                @csrf
                @method('PATCH')

                {{-- Hidden Input untuk Status --}}
                {{-- Jika status masih 'menunggu', otomatis ubah jadi 'proses' saat membalas --}}
                <input type="hidden" name="status" value="{{ $pengaduan->status == 'menunggu' ? 'proses' : $pengaduan->status }}">

                <div class="mb-2">
                    <label class="form-label fw-bold small text-muted text-uppercase">Balasan Anda</label>
                    <textarea name="isi_tanggapan" class="form-control" rows="3" 
                        placeholder="Ketik tanggapan atau tindak lanjut disini..." required></textarea>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted fst-italic">
                        *Membalas akan otomatis mengubah status menjadi "Proses"
                    </small>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-send-fill me-1"></i> Kirim
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="alert alert-success text-center mt-4">
            <i class="bi bi-check-circle-fill me-1"></i> Pengaduan ini telah diselesaikan.
        </div>
    @endif

</div>
@endsection