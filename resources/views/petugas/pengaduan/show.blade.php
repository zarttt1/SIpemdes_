@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detail Pengaduan</h5>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <label class="fw-bold">ID Pengaduan:</label>
                <p>{{ $pengaduan->id_pengaduan }}</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Tanggal Pengaduan:</label>
                <p>{{ $pengaduan->tanggal_pengaduan }}</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Nama Pelapor:</label>
                <p>{{ $pengaduan->masyarakat->nama ?? '-' }}</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Isi Laporan:</label>
                <p>{{ $pengaduan->isi_laporan }}</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Foto:</label><br>
                @if ($pengaduan->foto)
                    <img src="{{ asset('storage/' . $pengaduan->foto) }}" 
                         class="img-fluid rounded border"
                         alt="Foto Pengaduan" width="350">
                @else
                    <p class="text-muted">Tidak ada foto</p>
                @endif
            </div>

            <div class="mb-3">
                <label class="fw-bold">Status:</label>
                <p class="badge bg-info text-dark">{{ $pengaduan->status }}</p>
            </div>

            <hr>

            <h5>Update Status</h5>

            <form action="{{ route('petugas.pengaduan.updateStatus', $pengaduan->id_pengaduan) }}" 
                  method="POST">
                @csrf
                @method('PATCH') {{-- sesuai route --}}

                <div class="mb-3">
                    <select name="status" class="form-control" required>
                        <option value="menunggu" {{ $pengaduan->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="proses" {{ $pengaduan->status == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ $pengaduan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Perbarui Status</button>
                <a href="{{ route('petugas.pengaduan.index') }}" class="btn btn-secondary">Kembali</a>
            </form>

        </div>
    </div>

</div>
@endsection
