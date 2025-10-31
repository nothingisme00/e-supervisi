@extends('layouts.modern')

@section('content')
<div class="w-full max-w-md mx-auto px-4">
    <div class="bg-white rounded-xl shadow-2xl p-8 lg:p-10">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">E-Supervisi</h2>
            <p class="text-sm text-gray-600">Sistem Supervisi Pembelajaran</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-800 px-4 py-3 rounded-lg mb-5 border-l-4 border-red-500 text-sm">
                <strong>Login Gagal!</strong><br>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label for="nik" class="block text-gray-700 font-medium text-sm mb-2">
                    NIK (18 digit)
                </label>
                <input type="text"
                       id="nik"
                       name="nik"
                       value="{{ old('nik') }}"
                       maxlength="18"
                       required
                       placeholder="Masukkan 18 digit NIK"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div class="mb-5">
                <label for="password" class="block text-gray-700 font-medium text-sm mb-2">
                    Password
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       placeholder="Masukkan password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div class="mb-6">
                <label for="role" class="block text-gray-700 font-medium text-sm mb-2">
                    Login Sebagai
                </label>
                <select id="role"
                        name="role"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Pilih role</option>
                    <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>

            <button type="submit"
                    class="w-full px-4 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Masuk
            </button>
        </form>

        <div class="text-center mt-6 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-500">© 2025 E-Supervisi</p>
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
    });

    document.getElementById('nik').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 18);
    });

    // Prevent caching
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
</script>
@endsection
