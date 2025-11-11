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
      <div class="mb-4 text-red-600 text-sm">
        {{ $errors->first() }}
      </div>
    @endif

    {{-- Pastikan route dan method sesuai --}}
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

    <p class="text-center text-sm text-[#7096D1] mt-4">
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
