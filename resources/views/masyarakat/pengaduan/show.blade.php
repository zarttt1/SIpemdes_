@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')
<div class="container mt-5">
    <h2>Detail Pengaduan</h2>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Isi Laporan:</h5>
            <p class="card-text">{{ $pengaduan->isi_laporan ?? 'Isi laporan belum diisi' }}</p>

            <h5 class="card-title mt-3">Foto:</h5>
            @if(!empty($pengaduan->foto))
                <img src="{{ asset('storage/' . $pengaduan->foto) }}" alt="Foto pengaduan" class="img-fluid rounded shadow-sm" width="300">
            @else
                <p class="text-muted">Tidak ada foto</p>
            @endif

            <h5 class="card-title mt-3">Status:</h5>
            <p>
                <span class="badge 
                    @if($pengaduan->status == 'diproses') bg-warning 
                    @elseif($pengaduan->status == 'selesai') bg-success 
                    @else bg-secondary @endif">
                    {{ ucfirst($pengaduan->status ?? 'baru') }}
                </span>
            </p>

            <h5 class="card-title mt-3">Tanggal Pengaduan:</h5>
            <p>{{ $pengaduan->created_at->format('d M Y') }}</p>

            {{-- Tombol Aksi --}}
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('pengaduan.edit', ['pengaduan' => $pengaduan->id_pengaduan]) }}" class="btn btn-warning me-2">
                         Edit
                    </a>

                    <form action="{{ route('pengaduan.destroy', ['pengaduan' => $pengaduan->id_pengaduan]) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                             Hapus
                        </button>
                    </form>
                </div>

                <a href="{{ route('pengaduan.index') }}" class="btn btn-primary">
                     Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
