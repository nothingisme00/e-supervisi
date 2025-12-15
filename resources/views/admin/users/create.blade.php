@extends('layouts.modern')

@section('page-title', 'Tambah Pengguna Baru')

@section('content')
<x-breadcrumb :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Kelola Pengguna', 'url' => route('admin.users.index')],
    ['label' => 'Tambah Pengguna']
]" />

<div class="max-w-3xl mx-auto pb-24 md:pb-0">
    <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-lg border border-gray-200 dark:border-gray-700 p-3 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-base sm:text-xl font-bold text-gray-900 dark:text-white">Tambah Pengguna Baru</h2>
            <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-5 mb-3 sm:mb-5">
                <!-- NIK -->
                <div>
                    <label for="nik" class="block text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm mb-1.5 sm:mb-2">
                        NIK (Maksimal 16 digit) <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nik"
                           name="nik"
                           value="{{ old('nik') }}"
                           maxlength="16"
                           required
                           placeholder="Masukkan NIK (maksimal 16 digit)"
                           class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('nik') border-red-500 @enderror">
                    @error('nik')
                        <p class="mt-1 text-[10px] sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm mb-1.5 sm:mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Masukkan nama lengkap"
                           class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-[10px] sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="mb-3 sm:mb-5">
                <label for="email" class="block text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm mb-1.5 sm:mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       placeholder="Masukkan email"
                       class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-[10px] sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-3 sm:mb-5">
                <label for="role" class="block text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm mb-1.5 sm:mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <select id="role"
                        name="role"
                        required
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('role') border-red-500 @enderror">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                    <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
                @error('role')
                    <p class="mt-1 text-[10px] sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tingkat (Conditional - for guru and kepala_sekolah) -->
            <div class="mb-3 sm:mb-5" id="tingkatField" style="display: none;">
                <label for="tingkat" class="block text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm mb-1.5 sm:mb-2">
                    Tingkat <span class="text-red-500">*</span>
                </label>
                <select id="tingkat"
                        name="tingkat"
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('tingkat') border-red-500 @enderror">
                    <option value="">-- Pilih Tingkat --</option>
                    <option value="SD" {{ old('tingkat') == 'SD' ? 'selected' : '' }}>SD</option>
                    <option value="SMP" {{ old('tingkat') == 'SMP' ? 'selected' : '' }}>SMP</option>
                </select>
                @error('tingkat')
                    <p class="mt-1 text-[10px] sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mata Pelajaran (Conditional - for guru only) -->
            <div class="mb-3 sm:mb-5" id="mataPelajaranField" style="display: none;">
                <label for="mata_pelajaran" class="block text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm mb-1.5 sm:mb-2">
                    Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="mata_pelajaran"
                       name="mata_pelajaran"
                       value="{{ old('mata_pelajaran') }}"
                       placeholder="Masukkan mata pelajaran"
                       class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('mata_pelajaran') border-red-500 @enderror">
                @error('mata_pelajaran')
                    <p class="mt-1 text-[10px] sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Info Box -->
            <div class="mb-3 sm:mb-5 p-3 sm:p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md sm:rounded-lg">
                <div class="flex items-start gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-blue-800 dark:text-blue-300">Password Default</p>
                        <p class="text-[10px] sm:text-xs text-blue-700 dark:text-blue-400 mt-0.5 sm:mt-1">Password default: <strong>pass123456</strong></p>
                        <p class="text-[10px] sm:text-xs text-blue-700 dark:text-blue-400 mt-0.5 sm:mt-1">Pengguna diminta mengganti password saat login pertama.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-2 sm:gap-3 pt-3 sm:pt-5 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.users.index') }}" class="px-3 sm:px-5 py-2 sm:py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit"
                        id="submitBtn"
                        class="px-3 sm:px-5 py-2 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs sm:text-sm font-semibold rounded-md sm:rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    <span id="btnText">Simpan Pengguna</span>
                    <span id="btnLoader" class="hidden">
                        <div class="spinner inline-block"></div>
                        <span class="ml-2">Menyimpan...</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // NIK input validation
        const nikInput = document.getElementById('nik');
        nikInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);

            // Visual feedback for valid length (16 digits)
            if (this.value.length === 16) {
                this.classList.add('border-green-500', 'dark:border-green-400');
                this.classList.remove('border-gray-300', 'dark:border-gray-600');
            } else if (this.value.length > 0 && this.value.length < 16) {
                this.classList.remove('border-green-500', 'dark:border-green-400');
                this.classList.add('border-gray-300', 'dark:border-gray-600');
            } else {
                this.classList.remove('border-green-500', 'dark:border-green-400');
                this.classList.add('border-gray-300', 'dark:border-gray-600');
            }
        });

        // Role change handler - show/hide fields based on role
        const roleSelect = document.getElementById('role');
        const tingkatField = document.getElementById('tingkatField');
        const mataPelajaranField = document.getElementById('mataPelajaranField');
        const tingkatSelect = document.getElementById('tingkat');
        const mataPelajaranInput = document.getElementById('mata_pelajaran');

        function updateFieldsVisibility() {
            const selectedRole = roleSelect.value;

            if (selectedRole === 'guru' || selectedRole === 'kepala_sekolah') {
                tingkatField.style.display = 'block';
                tingkatSelect.required = true;
            } else {
                tingkatField.style.display = 'none';
                tingkatSelect.required = false;
                tingkatSelect.value = '';
            }

            if (selectedRole === 'guru') {
                mataPelajaranField.style.display = 'block';
                mataPelajaranInput.required = true;
            } else {
                mataPelajaranField.style.display = 'none';
                mataPelajaranInput.required = false;
                mataPelajaranInput.value = '';
            }
        }

        roleSelect.addEventListener('change', updateFieldsVisibility);

        // Initialize on page load (for old() values)
        updateFieldsVisibility();

        // Form submit with loading animation
        const form = document.getElementById('createUserForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoader = document.getElementById('btnLoader');

        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-300', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-300', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700', 'text-white');
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
        });
    });
</script>
@endsection
