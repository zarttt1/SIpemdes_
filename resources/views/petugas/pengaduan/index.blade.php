@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="card border-0 shadow-lg">
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-list-task me-2"></i> Daftar Pengaduan Masyarakat
            </h5>
        </div>

        <div class="card-body">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
                <div class="alert alert-success shadow-sm">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr class="text-center">
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
                                <td class="text-center fw-semibold">{{ $item->id_pengaduan }}</td>

                                <td>
                                    {{ $item->masyarakat->nama ?? 'User Terhapus' }}
                                </td>

                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pengaduan)->format('d-m-Y') }}
                                </td>

                                <td class="text-center">
                                    @if($item->status == 'menunggu')
                                        <span class="badge rounded-pill bg-warning text-dark px-3 py-2">
                                            Menunggu
                                        </span>
                                    @elseif($item->status == 'diproses')
                                        <span class="badge rounded-pill bg-info text-dark px-3 py-2">
                                            Diproses
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-success px-3 py-2">
                                            Selesai
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('petugas.pengaduan.show', $item->id_pengaduan) }}"
                                       class="btn btn-primary btn-sm px-3 shadow-sm">
                                        <i class="bi bi-eye-fill me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox text-secondary fs-1 d-block mb-2"></i>
                                    Belum ada pengaduan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $pengaduan->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
