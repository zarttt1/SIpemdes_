@extends('layouts.app')

@section('title', 'Detail Pengaduan - SIPEMDES')

@section('content')

<style>
    body {
        background: #f1f5f9 !important;
        font-family: "Poppins", sans-serif;
    }

    .detail-wrapper {
        padding-top: 30px;
        padding-bottom: 40px;
    }

    .section-label {
        font-size: .75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .info-box {
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 15px;
    }

    .status-badge {
        padding: 8px 14px;
        font-size: .78rem;
        border-radius: 999px;
        font-weight: 600;
    }

    .img-preview {
        max-height: 420px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 5px 16px rgba(0,0,0,0.08);
    }

    .header-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #1e40af;
    }

    .meta-info {
        color: #64748b;
        font-size: .85rem;
    }
</style>


<div class="container detail-wrapper">

    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- TOMBOL KEMBALI --}}
            <div class="mb-3">
                <a href="{{ route('pengaduan.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>

            {{-- CARD WRAPPER --}}
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- HEADER CARD --}}
                <div class="p-4 bg-white border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="header-title">
                            Detail Pengaduan #{{ $pengaduan->id_pengaduan }}
                        </div>
                        <div class="meta-info mt-1">
                            Dilaporkan pada:
                            <strong>
                                {{ \Carbon\Carbon::parse($pengaduan->created_at)->translatedFormat('d F Y, H:i') }}
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    {{-- STATUS --}}
                    <div class="mb-4">
                        <label class="section-label mb-1">Status Pengaduan</label>

                        @if($pengaduan->status == 'menunggu')
                            <span class="status-badge bg-warning text-dark">Menunggu Verifikasi</span>
                        @elseif($pengaduan->status == 'proses')
                            <span class="status-badge bg-info text-white">Sedang Diproses</span>
                        @else
                            <span class="status-badge bg-success text-white">Selesai</span>
                        @endif
                    </div>

                    {{-- ISI LAPORAN --}}
                    <div class="mb-4">
                        <label class="section-label mb-2">Isi Laporan</label>
                        <div class="info-box">
                            <p class="mb-0" style="white-space: pre-line;">
                                {{ $pengaduan->isi_laporan }}
                            </p>
                        </div>
                    </div>

                    {{-- FOTO BUKTI --}}
                    <div class="mb-4">
                        <label class="section-label mb-2">Foto Bukti</label>

                        @if(!empty($pengaduan->foto))
                            <img src="{{ asset('storage/' . $pengaduan->foto) }}"
                                 alt="Foto pengaduan"
                                 class="img-fluid img-preview">
                        @else
                            <div class="alert alert-secondary d-inline-flex align-items-center gap-2 px-3 py-2">
                                <i class="bi bi-image"></i>
                                Tidak ada foto yang dilampirkan.
                            </div>
                        @endif
                    </div>

                    {{-- AKSI (EDIT / HAPUS) --}}
                    @if($pengaduan->status == 'menunggu')
                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('pengaduan.edit', $pengaduan->id_pengaduan) }}"
                               class="btn btn-warning text-dark shadow-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <form action="{{ route('pengaduan.destroy', $pengaduan->id_pengaduan) }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini? Data tidak bisa dikembalikan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger shadow-sm">
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
