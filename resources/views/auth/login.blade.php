<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login | SIPEMDES</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #FFF9F0;
      background-image: radial-gradient(circle at 20% 30%, #7096D120 0%, transparent 60%),
                        radial-gradient(circle at 80% 70%, #334EAC15 0%, transparent 60%);
      min-height: 100vh;
    }
  </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen px-4">

  <div class="bg-white border border-[#7096D1]/50 shadow-lg shadow-[#7096D120] rounded-2xl p-8 w-full max-w-md">
    <h2 class="text-2xl font-semibold text-center text-[#334EAC] mb-6">Masuk ke SIPEMDES</h2>

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
      <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg">
        {{ $errors->first() }}
      </div>
    @endif

    {{-- Form Login Biasa --}}
    <form method="POST" action="{{ route('login.process') }}" class="space-y-4">
      @csrf

      <div>
        <label for="username" class="block text-sm font-medium text-[#334EAC] mb-1">Username</label>
        <input id="username" type="text" name="username" value="{{ old('username') }}"
               class="w-full border border-[#7096D1]/50 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#334EAC]"
               required autofocus>
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-[#334EAC] mb-1">Password</label>
        <input id="password" type="password" name="password"
               class="w-full border border-[#7096D1]/50 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#334EAC]"
               required>
      </div>

      <button type="submit"
              class="w-full bg-[#334EAC] text-[#FFF9F0] font-semibold py-2 rounded-lg hover:bg-[#7096D1] transition-all">
        Masuk
      </button>
    </form>

    {{-- Divider --}}
    <div class="relative flex py-5 items-center">
        <div class="flex-grow border-t border-[#7096D1]/30"></div>
        <span class="flex-shrink-0 mx-4 text-[#7096D1] text-sm">Atau masuk dengan</span>
        <div class="flex-grow border-t border-[#7096D1]/30"></div>
    </div>

    {{-- Tombol Login Google --}}
    {{-- Pastikan route 'auth.google' sudah dibuat di web.php --}}
    <a href="{{ route('auth.google') }}" 
       class="w-full flex items-center justify-center gap-2 bg-white border border-[#7096D1]/50 text-[#334EAC] font-medium py-2 rounded-lg hover:bg-gray-50 transition-all shadow-sm">
        <svg class="w-5 h-5" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.84z" fill="#FBBC05"/>
            <path d="M12 4.63c1.69 0 3.26.58 4.54 1.8l3.41-3.41C17.9 1.15 15.2 0 12 0 7.7 0 3.99 2.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Google
    </a>

    <p class="text-center text-sm text-[#7096D1] mt-6">
      Belum punya akun?
      <a href="{{ route('register.masyarakat') }}" class="font-semibold text-[#334EAC] hover:underline">
        Daftar Masyarakat
      </a>
    </p>
  </div>

  <footer class="mt-10 text-xs text-[#7096D1] text-center">
    © 2025 Sistem Informasi Pemerintahan Desa
  </footer>

</body>
</html>