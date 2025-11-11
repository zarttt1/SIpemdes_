<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPEMDES')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #0B5ED7;
            --light-blue: #0D6EFD;
            --dark-blue: #054FBE;
            --light-gray: #F8F9FA;
            --white: #FFFFFF;
            --text-dark: #212529;
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
        }

        .btn-primary {
            background: var(--primary-blue);
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: var(--dark-blue);
            transform: translateY(-2px);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(11, 94, 215, 0.15);
            transform: translateY(-4px);
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/">SIPEMDES</a>
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
            </div>
        </div>
    </div>
</nav>

<div class="container my-4">
