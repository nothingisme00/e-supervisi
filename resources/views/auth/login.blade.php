<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | E-Supervisi Web</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* Custom animation */
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }
    .float-animation {
      animation: float 3s ease-in-out infinite;
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-indigo-50">
  
  {{-- Background Pattern --}}
  <div class="absolute inset-0 z-0 opacity-5">
    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
  </div>

  <div class="w-full max-w-md relative z-10">
    {{-- Card Container --}}
    <div class="bg-white/95 backdrop-blur-lg shadow-2xl rounded-3xl p-8 border border-gray-100">
      
      {{-- Logo dan Judul --}}
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl mb-4 float-animation shadow-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
          </svg>
        </div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-2">E-Supervisi</h1>
        <p class="text-gray-500 text-sm">Sistem Evaluasi Kinerja Guru Digital</p>
      </div>

      {{-- Alert Messages --}}
      @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm">
                @foreach ($errors->all() as $error)
                  {{ $error }}
                @endforeach
              </p>
            </div>
          </div>
        </div>
      @endif

      @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm">{{ session('success') }}</p>
            </div>
          </div>
        </div>
      @endif

      {{-- Login Form --}}
      <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- NIK Input --}}
        <div class="space-y-2">
          <label for="nik" class="block text-sm font-semibold text-gray-700">NIK</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
              </svg>
            </span>
            <input 
              id="nik" 
              name="nik" 
              type="text" 
              value="{{ old('nik') }}"
              required 
              autocomplete="username"
              class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('nik') border-red-500 @enderror"
              placeholder="Masukkan NIK Anda"
            >
          </div>
          @error('nik')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- Password Input --}}
        <div class="space-y-2">
          <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </span>
            <input 
              id="password" 
              name="password" 
              type="password" 
              required
              autocomplete="current-password"
              class="w-full pl-12 pr-12 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror"
              placeholder="Masukkan Password"
            >
            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600">
              <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
          @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
          </label>
          
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 transition">
              Lupa password?
            </a>
          @endif
        </div>

        {{-- Submit Button --}}
        <button 
          type="submit" 
          class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:scale-[1.02]"
        >
          Masuk
        </button>
      </form>

      {{-- Footer --}}
      <div class="mt-8 text-center">
        <p class="text-xs text-gray-500">
          © {{ date('Y') }} E-Supervisi. All rights reserved.
        </p>
      </div>
    </div>

    {{-- Help Text --}}
    <div class="mt-6 text-center">
      <p class="text-sm text-gray-600">
        Belum punya akun? 
        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Hubungi Admin</a>
      </p>
    </div>
  </div>

  {{-- Script for password toggle --}}
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const eyeIcon = document.getElementById('eye-icon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
      } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
      }
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
      const alerts = document.querySelectorAll('[role="alert"]');
      alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      });
    }, 5000);
  </script>
</body>
</html>