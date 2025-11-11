@extends('layouts.app')

@section('title', 'Dashboard Masyarakat - SIPEMDES')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-lg-8">
        <h2 class="fw-bold text-primary">
            Selamat Datang, {{ auth('web')->user()->nama }}
        </h2>
        <p class="text-muted mb-0">
            Pantau dan kelola pengaduan Anda dengan mudah.
        </p>
    </div>
    <div class="col-lg-4 text-end">
        <a href="{{ route('pengaduan.create') }}" class="btn btn-primary px-4 shadow-sm">
            + Buat Pengaduan Baru
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Total Pengaduan</h6>
                <h2 class="fw-bold text-primary mb-0">0</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Diproses</h6>
                <h2 class="fw-bold text-warning mb-0">0</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Selesai</h6>
                <h2 class="fw-bold text-success mb-0">0</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Baru</h6>
                <h2 class="fw-bold text-info mb-0">0</h2>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0">
        <h5 class="fw-semibold text-primary mb-0">Daftar Pengaduan Saya</h5>
    </div>
    <div class="card-body">
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox display-5 d-block mb-3 text-secondary"></i>
            <p class="mb-1">Anda belum membuat pengaduan.</p>
            <p>Klik tombol <strong>"Buat Pengaduan Baru"</strong> di atas untuk memulai.</p>
        </div>
    </div>
</div>
@endsection
