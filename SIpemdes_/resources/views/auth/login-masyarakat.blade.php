@extends('layouts.app')

@section('title', 'Login Masyarakat - SIPEMDES')

@section('content')
<div class="row justify-content-center" style="min-height: calc(100vh - 200px); display: flex; align-items: center;">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-5">
                <h2 class="mb-4" style="color: var(--primary-blue); text-align: center; font-weight: 700;">
                    Login Masyarakat
                </h2>

                <form action="{{ route('login.masyarakat') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               id="username" name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100" style="padding: 10px; font-weight: 600;">
                        Login
                    </button>
                </form>

                <div class="mt-3 text-center">
                    <p class="text-muted mb-0">
                        Belum memiliki akun? 
                        <a href="{{ route('register.masyarakat') }}" style="color: var(--primary-blue); font-weight: 600;">Daftar di sini</a>
                    </p>
                </div>

                <hr>

                <div class="text-center">
                    <p class="text-muted">Anda Petugas?</p>
                    <a href="{{ route('login.petugas') }}" class="btn btn-outline-primary w-100">
                        Login sebagai Petugas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
