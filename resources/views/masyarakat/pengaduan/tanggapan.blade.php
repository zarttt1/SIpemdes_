@extends('layouts.app')

@section('title', 'Riwayat Tanggapan - SIPEMDES')

@section('content')
<style>
    .timeline-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .report-summary {
        background: #fff;
        border-left: 5px solid #2563eb;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .timeline-item {
        position: relative;
        padding-left: 3rem;
        margin-bottom: 2rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 14px;
        top: 0;
        bottom: -2rem;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 32px;
        height: 32px;
        background: #fff;
        border: 2px solid #2563eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        z-index: 1;
    }
    .timeline-content {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        position: relative;
    }
    .timeline-content::after {
        content: '';
        position: absolute;
        left: -8px;
        top: 10px;
        width: 15px;
        height: 15px;
        background: #f8fafc;
        border-left: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        transform: rotate(45deg);
    }
</style>

<div class="timeline-container">
    
    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('dashboard.masyarakat') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <h3 class="fw-bold mb-4 text-center text-primary">Riwayat Tindak Lanjut</h3>

    {{-- Ringkasan Laporan User --}}
    <div class="report-summary p-4 mb-5">
        <h6 class="text-uppercase text-secondary small fw-bold mb-2">Laporan Anda:</h6>
        <p class="mb-2 fs-5">"{{ $pengaduan->isi_laporan }}"</p>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">
                <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->translatedFormat('d F Y, H:i') }}
            </small>
            
            {{-- Badge Status --}}
            @if($pengaduan->status == 'menunggu')
                <span class="badge bg-warning text-dark">Menunggu</span>
            @elseif($pengaduan->status == 'proses')
                <span class="badge bg-info text-dark">Sedang Diproses</span>
            @else
                <span class="badge bg-success">Selesai</span>
            @endif
        </div>
    </div>

    {{-- Timeline Tanggapan --}}
    <div class="timeline-wrapper">
        @if($pengaduan->tanggapan->isEmpty())
            <div class="text-center py-5 text-muted">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="80" class="mb-3 opacity-50" alt="Empty">
                <p>Belum ada tanggapan dari petugas.</p>
                <small>Mohon menunggu, laporan Anda sedang dalam antrean verifikasi.</small>
            </div>
        @else
            @foreach($pengaduan->tanggapan as $item)
                <div class="timeline-item">
                    <div class="timeline-icon shadow-sm">
                        <i class="bi bi-chat-quote-fill"></i>
                    </div>
                    <div class="timeline-content shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                            <h6 class="fw-bold text-primary mb-0">
                                {{ $item->petugas->nama ?? 'Petugas Desa' }}
                            </h6>
                            <small class="text-muted" style="font-size: 0.8rem;">
                                {{ \Carbon\Carbon::parse($item->tanggal_tanggapan)->diffForHumans() }}
                            </small>
                        </div>
                        <p class="mb-0 text-dark" style="line-height: 1.6;">
                            {{ $item->isi_tanggapan }}
                        </p>
                        <div class="text-end mt-2">
                            <small class="text-muted" style="font-size: 0.75rem;">
                                {{ \Carbon\Carbon::parse($item->tanggal_tanggapan)->format('d M Y, H:i') }} WIB
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection