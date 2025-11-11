<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPEMDES')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0B5ED7;
            --light-blue: #062c66ff;
            --dark-blue: #054FBE;
            --accent-blue: #40B4F5;
            --light-gray: #F8F9FA;
            --white: #FFFFFF;
            --text-dark: #212529;
            --text-light: #6C757D;
        }

        * {
            --bs-primary: var(--primary-blue);
            --bs-body-color: var(--text-dark);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--light-gray) 0%, #E7F1FF 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(90deg, var(--primary-blue) 0%, var(--light-blue) 100%);
            box-shadow: 0 4px 12px rgba(11, 94, 215, 0.15);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--white) !important;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: var(--primary-blue);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(11, 94, 215, 0.3);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(11, 94, 215, 0.15);
            transform: translateY(-4px);
        }

        .alert {
            border: none;
            border-radius: 8px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #E0E0E0;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(11, 94, 215, 0.25);
        }

        .badge-status {
            border-radius: 20px;
            padding: 6px 12px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge-baru {
            background: #E7F1FF;
            color: var(--primary-blue);
        }

        .badge-diproses {
            background: #FFF3CD;
            color: #856404;
        }

        .badge-selesai {
            background: #D4EDDA;
            color: #155724;
        }

        footer {
            background: var(--primary-blue);
            color: var(--white);
            padding: 2rem 0;
            margin-top: auto;
        }

        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
        }
    </style>
</head>
<body class="page-container">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Sistem Informasi Pengaduan Masyarakat Desa
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto">
                    @auth('web')
                        <span class="text-white me-3">{{ auth('web')->user()->nama }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm">Logout</button>
                        </form>
                    @endauth
                    @auth('petugas')
                        <span class="text-white me-3">{{ auth('petugas')->user()->nama }} ({{ auth('petugas')->user()->level }})</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="content">
        <div class="container my-4">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>SIPEMDES</h5>
                    <p class="text-light">Sistem Informasi Pengaduan Masyarakat Desa</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">&copy; 2025 Kelompok 5. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
