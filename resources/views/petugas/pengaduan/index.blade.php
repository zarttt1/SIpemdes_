@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Pengaduan Masyarakat</h5>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nama Pelapor</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengaduan as $item)
                            <tr>
                                <td class="text-center">{{ $item->id_pengaduan }}</td>
                                <td>{{ $item->masyarakat->nama ?? 'User Terhapus' }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pengaduan)->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    @if($item->status == 'menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($item->status == 'diproses')
                                        <span class="badge bg-info text-dark">diproses</span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- PERHATIKAN ROUTE INI --}}
                                    <a href="{{ route('petugas.pengaduan.show', $item->id_pengaduan) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Belum ada pengaduan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination jika ada --}}
            <div class="mt-3">
                {{ $pengaduan->links() }} 
            </div>
        </div>
    </div>
</div>
@endsection