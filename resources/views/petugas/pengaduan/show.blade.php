@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-9">

            {{-- TITEL & TOMBOL AKSI --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('dashboard.petugas') }}" 
                   class="btn btn-light border shadow-sm btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>

                @if($pengaduan->status != 'selesai')
                    <a href="{{ route('petugas.pengaduan.tanggapan', $pengaduan->id_pengaduan) }}" 
                       class="btn btn-primary btn-sm shadow-sm">
                        <i class="bi bi-chat-dots"></i> Buka Percakapan
                    </a>
                @endif
            </div>

            {{-- CARD DETAIL --}}
            <div class="card shadow-sm border-0 mb-4 rounded-3">

                {{-- HEADER CARD (VERSI TERBAIK, DIGABUNG) --}}
                <div class="d-flex justify-content-between align-items-center p-3 rounded-top"
                     style="background: linear-gradient(90deg, #0099ff, #007ee6);">

                    <h5 class="text-white mb-0 fw-semibold">
                        Detail Pengaduan #{{ $pengaduan->id_pengaduan }}
                    </h5>

                    <span class="badge bg-light text-primary px-3 py-2 rounded-pill shadow-sm">
                        {{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->translatedFormat('d F Y') }}
                    </span>
                </div>

                {{-- BODY --}}
                <div class="card-body p-4">

                    <div class="row">

                        {{-- INFO PELAPOR --}}
                        <div class="col-md-7">
                            <p class="mb-1"><strong>Nama Pelapor :</strong> {{ $pengaduan->masyarakat->nama ?? 'User Terhapus' }}</p>
                            <p class="mb-1"><strong>NIK :</strong> {{ $pengaduan->masyarakat->nik ?? '-' }}</p>
                            <p class="mb-1"><strong>Isi Laporan :</strong> {{ $pengaduan->isi_laporan }}</p>

                            <p class="mt-3 mb-1 fw-semibold">Status Saat Ini</p>

                            @if($pengaduan->status == 'menunggu')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu</span>
                            @elseif($pengaduan->status == 'proses')
                                <span class="badge bg-info text-white px-3 py-2 rounded-pill">Sedang Diproses</span>
                            @else
                                <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                            @endif
                        </div>

                        {{-- FOTO BUKTI --}}
                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                            <p class="fw-semibold mb-2">Foto Bukti :</p>

                            @if ($pengaduan->foto)
                                <img src="{{ asset('storage/' . $pengaduan->foto) }}"
                                     class="img-fluid rounded shadow-sm border"
                                     style="max-height: 200px; width: auto;"
                                     alt="Foto Pengaduan">
                            @else
                                <p class="text-muted">Tidak ada foto lampiran</p>
                            @endif
                        </div>

                    </div>
                </div>

            </div>

            {{-- UPDATE STATUS --}}
            <div class="card bg-white shadow-sm border-0 p-4">

                <h5 class="fw-semibold mb-3 text-secondary">
                    Update Status Cepat
                </h5>

                <form action="{{ route('petugas.pengaduan.updateStatus', $pengaduan->id_pengaduan) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="isi_tanggapan" value="Status diperbarui melalui halaman detail.">

                    <label class="form-label fw-semibold text-muted">Pilih Status Baru</label>

                    <select name="status" class="form-select shadow-sm mb-3" required>
                        <option value="menunggu" {{ $pengaduan->status == 'menunggu' ? 'selected' : '' }}>
                            Menunggu
                        </option>
                        <option value="proses" {{ $pengaduan->status == 'proses' ? 'selected' : '' }}>
                            Proses
                        </option>
                        <option value="selesai" {{ $pengaduan->status == 'selesai' ? 'selected' : '' }}>
                            Selesai
                        </option>
                    </select>

                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        Simpan Status
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>
@endsection
