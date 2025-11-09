@extends('layouts.modern')

@section('page-title', 'Edit Admin')

@section('content')
<x-breadcrumb :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Kelola Admin', 'url' => route('admin.users.index')],
    ['label' => 'Edit Admin']
]" />

<div class="max-w-3xl mx-auto">
    <!-- Warning Banner untuk Edit Diri Sendiri -->
    @if($isEditingSelf)
    <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 dark:border-amber-600 rounded-r-lg">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 mt-0.5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h3 class="text-sm font-semibold text-amber-900 dark:text-amber-300 mb-1">Anda Sedang Mengedit Data Diri Sendiri</h3>
                <p class="text-xs text-amber-800 dark:text-amber-400 leading-relaxed">
                    Anda hanya dapat mengubah <strong>NIK</strong>, <strong>Nama</strong>, dan <strong>Email</strong> Anda.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Edit Admin</h2>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="editUserForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <!-- NIK -->
                <div>
                    <label for="nik" class="block text-gray-700 dark:text-gray-300 font-medium text-sm mb-2">
                        NIK (Maksimal 16 digit) <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nik"
                           name="nik"
                           value="{{ old('nik', $user->nik) }}"
                           maxlength="16"
                           required
                           placeholder="Masukkan NIK (maksimal 16 digit)"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('nik') border-red-500 @enderror">
                    @error('nik')
                        <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-gray-700 dark:text-gray-300 font-medium text-sm mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           required
                           placeholder="Masukkan nama lengkap"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="mb-5">
                <label for="email" class="block text-gray-700 dark:text-gray-300 font-medium text-sm mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       required
                       placeholder="Masukkan email admin"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Hidden (Always admin) -->
            <input type="hidden" name="role" value="admin">

            <!-- Password Info -->
            <div class="mb-5 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Informasi Password</p>
                        <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">Password tidak akan diubah saat update. Gunakan tombol "Reset Password" di halaman daftar admin untuk mereset password.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit"
                        id="submitBtn"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    <span id="btnText">Update Admin</span>
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

        // Form submit with loading animation
        const form = document.getElementById('editUserForm');
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
