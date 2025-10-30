<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | E-Supervisi Web</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-blue-50">

  <div class="w-full max-w-md bg-white shadow-xl rounded-3xl p-8 border border-gray-100">
    
    {{-- Logo dan Judul --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-2xl mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 mb-2">E-Supervisi</h1>
      <p class="text-gray-500 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    {{-- Pesan Error --}}
    @if ($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
        @if ($errors->has('nik'))
          {{ $errors->first('nik') }}
        @elseif ($errors->has('role'))
          {{ $errors->first('role') }}
        @else
          Terjadi kesalahan saat login.
        @endif
      </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
      @csrf

      {{-- Input NIK --}}
      <div>
        <label for="nik" class="block text-sm font-semibold text-gray-700 mb-2">NIK</label>
        <div class="relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </span>
          <input id="nik" name="nik" type="text" value="{{ old('nik') }}" required autofocus
            class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-400 transition-all"
            placeholder="Masukkan NIK Anda">
        </div>
      </div>


{{-- Input Password --}}
<div>
  <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
  <div class="relative">
    {{-- Icon Lock --}}
    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none z-10">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
      </svg>
    </div>
    
    {{-- Input Password - UKURAN FONT TETAP --}}
    <input id="password" name="password" type="password" required
      class="block w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-400 transition-colors"
      placeholder="Masukkan password"
      style="height: 52px !important; padding-left: 48px !important; padding-right: 48px !important; padding-top: 0 !important; padding-bottom: 0 !important; border-radius: 0.75rem !important; line-height: normal !important; font-size: 16px !important; letter-spacing: normal !important;">

    {{-- Tombol Toggle - POSISI TETAP --}}
    <button type="button" id="togglePassword"
      class="absolute inset-y-0 right-0 flex items-center justify-center w-12 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors z-10">
      {{-- Icon Hide (garis diagonal) --}}
      <svg id="iconHide" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.985 9.985 0 012.36-3.733M6.18 6.18A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.98 9.98 0 01-4.626 6.065M3 3l18 18" />
      </svg>
      {{-- Icon Show (mata terbuka) --}}
      <svg id="iconShow" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
      </svg>
    </button>
  </div>
</div>

      {{-- Role --}}
      <div>
        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Masuk sebagai</label>
        <div class="relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </span>
          <select id="role" name="role" required
            class="w-full pl-12 pr-10 py-3.5 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 appearance-none cursor-pointer transition-all">
            <option value="admin">Admin</option>
            <option value="guru" selected>Guru</option>
            <option value="kepala_sekolah">Kepala Sekolah</option>
          </select>
          <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </span>
        </div>
      </div>

      {{-- Tombol Login --}}
      <button type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl text-base shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30 transition-all duration-200 ease-in-out mt-6">
        Masuk
      </button>
    </form>

    {{-- Footer --}}
    <p class="text-center text-gray-500 text-sm mt-6">
      Belum punya akun?
      <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Daftar</a>
    </p>

    {{-- Copyright Footer --}}
    <p class="text-center text-gray-400 text-xs mt-8 pt-6 border-t border-gray-100">
      © 2025 E-Supervisi. Sistem Supervisi Pendidikan.
    </p>
  </div>

  {{-- Script toggle password --}}
  <script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const iconShow = document.getElementById('iconShow');
    const iconHide = document.getElementById('iconHide');

    toggleBtn.addEventListener('click', () => {
      const isHidden = passwordInput.getAttribute('type') === 'password';
      passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
      iconShow.classList.toggle('hidden');
      iconHide.classList.toggle('hidden');
    });
  </script>

</body>
</html>