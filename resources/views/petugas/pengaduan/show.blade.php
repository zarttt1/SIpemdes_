@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            
            {{-- TOMBOL KEMBALI --}}
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('dashboard.petugas') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
                
                {{-- Shortcut ke Halaman Chat --}}
                @if($pengaduan->status != 'selesai')
                    <a href="{{ route('petugas.pengaduan.tanggapan', $pengaduan->id_pengaduan) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-chat-text"></i> Buka Percakapan / Tanggapan
                    </a>
                @endif
            </div>

            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Pengaduan {{ $pengaduan->id_pengaduan }}</h5>
                    <span class="badge bg-light text-primary">
                        {{ \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->translatedFormat('d F Y') }}
                    </span>
                </div>

                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">Nama Pelapor</th>
                            <td>: {{ $pengaduan->masyarakat->nama ?? 'User Terhapus' }}</td>
                        </tr>
                        <tr>
                            <th>NIK</th>
                            <td>: {{ $pengaduan->masyarakat->nik ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Isi Laporan</th>
                            <td>: {{ $pengaduan->isi_laporan }}</td>
                        </tr>
                        <tr>
                            <th>Foto Bukti</th>
                            <td>
                                @if ($pengaduan->foto)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $pengaduan->foto) }}" 
                                             class="img-fluid rounded border shadow-sm"
                                             style="max-height: 400px; width: auto;"
                                             alt="Foto Pengaduan">
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">: Tidak ada foto lampiran</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status Saat Ini</th>
                            <td>
                                : 
                                @if($pengaduan->status == 'menunggu')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($pengaduan->status == 'proses') 
                                    <span class="badge bg-info text-white">Sedang Diproses</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <hr>

                    {{-- FORM UPDATE STATUS --}}
                    {{-- Form ini dikembalikan sesuai permintaan, tapi tanpa input teks panjang --}}
                    <div class="mt-4">
                        <h5 class="fw-bold mb-3 text-secondary"><i class="bi bi-pencil-square"></i> Update Status Cepat</h5>
                        
                        <div class="card bg-light border-0 p-3">
                            <form action="{{ route('petugas.pengaduan.updateStatus', $pengaduan->id_pengaduan) }}" method="POST">
                                @csrf
                                @method('PATCH') 

                                {{-- PENTING: Input Hidden agar Controller tidak error validasi 'required' --}}
                                <input type="hidden" name="isi_tanggapan" value="Status diperbarui melalui halaman detail.">

                                <div class="d-flex gap-2 align-items-end">
                                    <div class="flex-grow-1">
                                        <label for="status" class="form-label fw-bold small text-muted">Pilih Status Baru:</label>
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="menunggu" {{ $pengaduan->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="proses" {{ $pengaduan->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                            <option value="selesai" {{ $pengaduan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Simpan Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection