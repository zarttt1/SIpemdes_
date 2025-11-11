@extends('layouts.app')

@section('title', 'Daftar Pengaduan')

@section('content')
<div class="container mt-5">
    <h2>Daftar Pengaduan</h2>

    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <a href="{{ route('pengaduan.create') }}" class="btn btn-primary mt-3">+ Buat Pengaduan</a>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Isi Laporan</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengaduan as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->isi_laporan }}</td>
                    <td>
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="foto" width="70">
                        @else
                            <small>- Tidak ada -</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge 
                            @if($item->status == 'diproses') bg-warning
                            @elseif($item->status == 'selesai') bg-success
                            @else bg-secondary @endif">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>{{ $item->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('pengaduan.show', ['pengaduan' => $item->id_pengaduan]) }}" 
                           class="btn btn-sm btn-info">Lihat</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada pengaduan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tombol kembali --}}
    <div class="text-end mt-4">
        <a href="{{ route('dashboard.masyarakat') }}" class="btn btn-primary">
             Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
