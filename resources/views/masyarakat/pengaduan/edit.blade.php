@extends('layouts.app')

@section('title', 'Edit Pengaduan - SIPEMDES')

@section('content')
<style>
.edit-container {
    background: #f8fafc;
    padding: 2rem 1rem 3rem;
    font-family: 'Poppins', sans-serif;
}

.form-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.06);
    padding: 2rem;
    max-width: 720px;
    margin: 0 auto;
    border: 1px solid #e2e8f0;
}

.form-card h3 {
    font-weight: 700;
    color: #1e40af;
    margin-bottom: 1.5rem;
    text-align: center;
}

.form-label {
    font-weight: 500;
    color: #334155;
    margin-bottom: 0.5rem;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
}

.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}

textarea.form-control {
    resize: none;
    height: 130px;
}

.btn-submit {
    background: linear-gradient(90deg, #2563eb, #1d4ed8);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: 0.25s ease;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37,99,235,0.25);
}

.btn-back {
    background: #e2e8f0;
    border: none;
    color: #334155;
    border-radius: 10px;
    padding: 0.75rem 1.25rem;
    font-weight: 500;
    transition: 0.25s ease;
}

.btn-back:hover {
    background: #cbd5e1;
}

.image-preview {
    margin-top: 1rem;
    border-radius: 10px;
    overflow: hidden;
    width: 150px;
    height: auto;
}
</style>

<div class="edit-container">
    <div class="form-card">
        <h3>Edit Pengaduan</h3>

        <form action="{{ route('pengaduan.update', $pengaduan->id_pengaduan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Isi Laporan --}}
            <div class="mb-3">
                <label for="isi_laporan" class="form-label">Isi Laporan</label>
                <textarea name="isi_laporan" id="isi_laporan" class="form-control" required>{{ old('isi_laporan', $pengaduan->isi_laporan) }}</textarea>
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label for="foto" class="form-label">Foto (Opsional)</label>
                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                
                @if($pengaduan->foto)
                    <div class="image-preview mt-2">
                        <p class="text-sm text-gray-500 mb-1">Foto saat ini:</p>
                        <img src="{{ asset('storage/' . $pengaduan->foto) }}" alt="Foto lama" width="150">
                    </div>
                @endif
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('dashboard.masyarakat') }}" class="btn btn-back">
                     Kembali
                </a>
                <button type="submit" class="btn btn-submit">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
