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

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelapor</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pengaduan as $item)
                        <tr>
                            <td>{{ $item->id_pengaduan }}</td>

                            <td>{{ $item->masyarakat->nama ?? '-' }}</td>

                            <td>{{ $item->tanggal_pengaduan }}</td>

                            <td>
                                <span class="badge 
                                    @if($item->status == 'menunggu') bg-warning
                                    @elseif($item->status == 'proses') bg-info
                                    @else bg-success
                                    @endif
                                ">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('petugas.pengaduan.show', $item->id_pengaduan) }}"
                                   class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Belum ada pengaduan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection

