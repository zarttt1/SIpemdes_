@extends('layouts.app')

@section('title', 'Buat Pengaduan')

@section('content')
<style>
    /* ====== Styling Modern Form ====== */
    .pengaduan-container {
        max-width: 700px;
        margin: 3rem auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        padding: 2rem 2.5rem;
        font-family: 'Poppins', sans-serif;
    }

    .pengaduan-container h2 {
        color: #2563eb;
        font-weight: 600;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    label.form-label {
        font-weight: 500;
        color: #374151;
    }

    .form-control {
        border-radius: 12px;
        padding: 0.75rem;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
    }

    button.btn-success {
        background-color: #2563eb;
        border: none;
        border-radius: 10px;
        font-weight: 500;
        padding: 0.6rem 1.5rem;
    }

    button.btn-success:hover {
        background-color: #1d4ed8;
    }

    .btn-secondary {
        border-radius: 10px;
        font-weight: 500;
        padding: 0.6rem 1.5rem;
    }

    .text-danger.small {
        font-size: 0.85rem;
    }
</style>

<div class="pengaduan-container">
    <h2>Buat Pengaduan Baru</h2>

    <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="isi_laporan" class="form-label">Isi Laporan</label>
            <textarea name="isi_laporan" id="isi_laporan" class="form-control" rows="5" placeholder="Tuliskan keluhan atau laporan Anda di sini..." required>{{ old('isi_laporan') }}</textarea>
            @error('isi_laporan')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Upload Foto (Opsional)</label>
            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
            @error('foto')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('pengaduan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-success">Kirim Pengaduan</button>
        </div>
    </form>
</div>
@endsection
