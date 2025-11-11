<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard - SIPEMDES</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tambahan styling biar rapi --}}
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8fafc;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #0056b3;
        }
        p {
            font-size: 16px;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
        }
        button {
            background-color: #0056b3;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
        button:hover {
            background-color: #003f87;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard SIPEMDES</h2>
        <p>
            Halo, <strong>{{ auth()->user()->nama ?? auth()->user()->name }}</strong><br>
            <small>Anda login sebagai: <strong>{{ auth()->user()->level ?? 'masyarakat' }}</strong></small>
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>
