@extends('layouts.app')

@section('title', 'Buat Pengaduan')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #f1f5f9);
        font-family: "Poppins", sans-serif;
    }

    .glass-wrapper {
        display: flex;
        justify-content: center;
        padding: 3rem 1rem;
    }

    .glass-card {
        width: 100%;
        max-width: 720px;
        padding: 2.5rem;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        box-shadow: 0 12px 34px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .glass-title {
        font-weight: 700;
        font-size: 1.9rem;
        color: #1d4ed8;
        text-align: center;
        margin-bottom: 2rem;
        letter-spacing: 0.3px;
    }

    /* Label */
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
        display: block;
    }

    /* Input & Textarea */
    .form-control {
        background: #f9fafb;
        border-radius: 14px;
        padding: 0.85rem;
        border: 1px solid #e5e7eb;
        transition: all .25s ease;
    }

    .form-control:hover {
        border-color: #93c5fd;
        background: #ffffff;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,.25);
    }

    textarea {
        resize: none;
    }

    /* File Input */
    #foto {
        display: none;
    }

    .file-label {
        background: #e0f2fe;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        display: inline-block;
        cursor: pointer;
        font-weight: 500;
        color: #0369a1;
        border: 1px solid #bae6fd;
        transition: background .25s ease, color .25s ease;
    }

    .file-label:hover {
        background: #38bdf8;
        color: white;
    }

    .file-name-display {
        font-size: 0.85rem;
        margin-top: 6px;
        font-style: italic;
        color: #6b7280;
    }

    /* Buttons */
    .btn-submit {
        background: #3b82f6;
        color: white;
        border-radius: 12px;
        padding: 0.7rem 2rem;
        font-weight: 600;
        transition: all .25s ease;
        border: none;
    }

    .btn-submit:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }

    .btn-cancel {
        background: #9ca3af;
        color: white;
        padding: 0.7rem 1.8rem;
        border-radius: 12px;
        font-weight: 500;
        transition: all .25s ease;
    }

    .btn-cancel:hover {
        background: #6b7280;
        transform: translateY(-2px);
    }
</style>

<div class="glass-wrapper">
    <div class="glass-card">

        <h2 class="glass-title">Buat Pengaduan Baru</h2>

        <form action="{{ route('pengaduan.store') }}" 
              method="POST" 
              enctype="multipart/form-data">

            @csrf

            <!-- Isi laporan -->
            <div class="mb-3">
                <label class="form-label">Isi Laporan</label>
                <textarea name="isi_laporan"
                          id="isi_laporan"
                          class="form-control"
                          rows="5"
                          placeholder="Tuliskan laporan Anda dengan jelas..."
                          required>{{ old('isi_laporan') }}</textarea>

                @error('isi_laporan')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Upload foto -->
            <div class="mb-3">
                <label class="form-label">Upload Foto (Opsional)</label>

                <label for="foto" class="file-label">Pilih Foto</label>
                <input type="file"
                       name="foto"
                       id="foto"
                       accept="image/*"
                       onchange="showFileName()">

                <div id="file-name" class="file-name-display">
                    Belum ada file dipilih
                </div>

                @error('foto')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('pengaduan.index') }}" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-submit">Kirim Pengaduan</button>
            </div>

        </form>

    </div>
</div>

<script>
    function showFileName() {
        const fileInput = document.getElementById('foto');
        const display = document.getElementById('file-name');

        if (fileInput.files.length > 0) {
            display.textContent = "Dipilih: " + fileInput.files[0].name;
            display.style.color = "#0369a1";
            display.style.fontWeight = "600";
        } else {
            display.textContent = "Belum ada file dipilih";
            display.style.color = "#6b7280";
            display.style.fontWeight = "normal";
        }
    }
</script>

@endsection
