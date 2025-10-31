@extends('layouts.modern')

@section('content')
<div class="w-full max-w-md mx-auto px-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-8 lg:p-10">
        <!-- Header dengan Dark Mode Toggle -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">E-Supervisi</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Sistem Supervisi Pembelajaran</p>
            </div>
            <button id="theme-toggle-login" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors ml-4" title="Toggle dark mode">
                <svg id="theme-toggle-dark-icon-login" class="w-5 h-5 text-gray-700 dark:text-gray-300 hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="theme-toggle-light-icon-login" class="w-5 h-5 text-gray-700 dark:text-gray-300 hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg mb-5 border-l-4 border-red-500 dark:border-red-400 text-sm">
                <strong>Login Gagal!</strong><br>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="mb-5">
                <label for="nik" class="block text-gray-700 dark:text-gray-300 font-medium text-sm mb-2">
                    NIK (18 digit)
                </label>
                <input type="text"
                       id="nik"
                       name="nik"
                       value="{{ old('nik') }}"
                       maxlength="18"
                       required
                       placeholder="Masukkan 18 digit NIK"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>

            <div class="mb-5">
                <label for="password" class="block text-gray-700 dark:text-gray-300 font-medium text-sm mb-2">
                    Password
                </label>
                <div class="relative">
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           placeholder="Masukkan password"
                           class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    <button type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="eyeSlashIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <label for="role" class="block text-gray-700 dark:text-gray-300 font-medium text-sm mb-2">
                    Login Sebagai
                </label>
                <div class="relative">
                    <select id="role"
                            name="role"
                            required
                            class="w-full px-4 py-3 pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all appearance-none cursor-pointer">
                        <option value="">Pilih role Anda</option>
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
                </label>
            </div>

            <button type="submit"
                    id="submitBtn"
                    class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <span id="btnText">Masuk</span>
                <span id="btnLoader" class="hidden">
                    <div class="spinner inline-block"></div>
                    <span class="ml-2">Memproses...</span>
                </span>
            </button>
        </form>

        <div class="text-center mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400">© 2025 E-Supervisi</p>
        </div>
    </div>
</div>

<script>
    // Check if user is already authenticated when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // If user is authenticated, redirect to home
        @auth
            window.location.href = '{{ url('/') }}';
        @endauth

        // Dark Mode Toggle untuk Login Page
        const themeToggleLogin = document.getElementById('theme-toggle-login');
        const themeToggleDarkIconLogin = document.getElementById('theme-toggle-dark-icon-login');
        const themeToggleLightIconLogin = document.getElementById('theme-toggle-light-icon-login');

        // Show correct icon on load
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIconLogin.classList.remove('hidden');
        } else {
            themeToggleDarkIconLogin.classList.remove('hidden');
        }

        themeToggleLogin.addEventListener('click', function() {
            // Toggle icons
            themeToggleDarkIconLogin.classList.toggle('hidden');
            themeToggleLightIconLogin.classList.toggle('hidden');

            // Toggle dark mode
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        });

        // Show/Hide Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        togglePassword.addEventListener('click', function() {
            // Toggle password visibility
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle icons
            eyeIcon.classList.toggle('hidden');
            eyeSlashIcon.classList.toggle('hidden');
        });

        // NIK input validation
        const nikInput = document.getElementById('nik');
        nikInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 18);

            // Visual feedback for valid length
            if (this.value.length === 18) {
                this.classList.add('border-green-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-green-500');
                this.classList.add('border-gray-300');
            }
        });

        // Form submit with loading animation
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoader = document.getElementById('btnLoader');

        form.addEventListener('submit', function(e) {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
        });

        // Dropdown hover effect (border color only)
        const roleSelect = document.getElementById('role');
        roleSelect.addEventListener('mouseenter', function() {
            if (document.activeElement !== this) {
                this.style.borderColor = '#818cf8';
            }
        });
        roleSelect.addEventListener('mouseleave', function() {
            if (document.activeElement !== this) {
                this.style.borderColor = '';
            }
        });
    });

    // Prevent caching
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
</script>
@endsection
