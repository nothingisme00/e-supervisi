@extends('layouts.modern')

@section('page-title', 'Edit Pengguna')

@section('content')
<style>
    .dropdown-menu-custom.show {
        display: block;
        opacity: 1;
        transform: scale(1);
    }
    .dropdown-item.active {
        background-color: rgb(238 242 255);
        color: rgb(79 70 229);
    }
    .dark .dropdown-item.active {
        background-color: rgba(49, 46, 129, 0.3);
        color: rgb(129 140 248);
    }
</style>

<div class="max-w-3xl mx-auto pb-24 md:pb-0">
    <!-- Warning Banner untuk Edit Diri Sendiri -->
    @if($isEditingSelf)
    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 dark:border-amber-600 rounded-r-md sm:rounded-r-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600 dark:text-amber-400 mt-0.5 mr-2 sm:mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h3 class="text-xs sm:text-sm font-semibold text-amber-900 dark:text-amber-300 mb-0.5 sm:mb-1">Anda Sedang Mengedit Data Diri Sendiri</h3>
                <p class="text-[10px] sm:text-xs text-amber-800 dark:text-amber-400 leading-relaxed">
                    Berhati-hatilah saat mengubah data diri, terutama <strong>Email</strong> untuk login.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-lg border border-gray-200 dark:border-gray-700 p-3 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-base sm:text-xl font-bold text-gray-900 dark:text-white">Edit Pengguna</h2>
            <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="editUserForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-5 mb-3 sm:mb-5">
                <!-- NIK -->
                <div>
                    <label for="nik" class="block text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm mb-1.5 sm:mb-2">
                        NIK (Maksimal 16 digit) <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nik"
                           name="nik"
                           value="{{ old('nik', $user->nik) }}"
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
                           value="{{ old('name', $user->name) }}"
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
                       value="{{ old('email', $user->email) }}"
                       required
                       placeholder="Masukkan email"
                       class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-[10px] sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-3 sm:mb-5">
                <label class="block text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm mb-1.5 sm:mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <div class="relative custom-dropdown-container" id="role-dropdown-container">
                    <input type="hidden" name="role" id="role" value="{{ old('role', $user->role) }}" required>
                    <button type="button" 
                            class="dropdown-button w-full px-3 sm:px-4 py-2 sm:py-3 text-left border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all flex items-center justify-between @error('role') border-red-500 @enderror">
                        <span class="dropdown-label flex items-center gap-2 {{ old('role', $user->role) ? '' : 'text-gray-400 dark:text-gray-500' }}">
                            @if(old('role', $user->role) == 'admin')
                                Admin
                            @elseif(old('role', $user->role) == 'kepala_sekolah')
                                Kepala Sekolah
                            @elseif(old('role', $user->role) == 'guru')
                                Guru
                            @else
                                -- Pilih Role --
                            @endif
                        </span>
                        <span class="material-symbols-outlined text-gray-400 transition-transform duration-200 dropdown-arrow">expand_more</span>
                    </button>
                    <div class="dropdown-menu-custom absolute top-full mt-1 left-0 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl z-50 hidden opacity-0 transform scale-95 transition-all duration-200 origin-top">
                        <div class="p-1.5 space-y-1">
                            <div class="dropdown-item px-4 py-2.5 rounded-md text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-3" data-value="admin">
                                <span class="material-symbols-outlined text-lg">shield_person</span>
                                Admin
                            </div>
                            <div class="dropdown-item px-4 py-2.5 rounded-md text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-3" data-value="kepala_sekolah">
                                <span class="material-symbols-outlined text-lg">account_balance</span>
                                Kepala Sekolah
                            </div>
                            <div class="dropdown-item px-4 py-2.5 rounded-md text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-3" data-value="guru">
                                <span class="material-symbols-outlined text-lg">person</span>
                                Guru
                            </div>
                        </div>
                    </div>
                </div>
                @error('role')
                    <p class="mt-1 text-[10px] sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tingkat (Conditional - for guru and kepala_sekolah) -->
            <div class="mb-3 sm:mb-5" id="tingkatField" style="display: none;">
                <label class="block text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm mb-1.5 sm:mb-2">
                    Tingkat <span class="text-red-500">*</span>
                </label>
                <div class="relative custom-dropdown-container" id="tingkat-dropdown-container">
                    <input type="hidden" name="tingkat" id="tingkat" value="{{ old('tingkat', $user->tingkat) }}">
                    <button type="button" 
                            class="dropdown-button w-full px-3 sm:px-4 py-2 sm:py-3 text-left border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all flex items-center justify-between @error('tingkat') border-red-500 @enderror">
                        <span class="dropdown-label flex items-center gap-2 {{ old('tingkat', $user->tingkat) ? '' : 'text-gray-400 dark:text-gray-500' }}">
                            @if(old('tingkat', $user->tingkat) == 'SD')
                                SD
                            @elseif(old('tingkat', $user->tingkat) == 'SMP')
                                SMP
                            @else
                                -- Pilih Tingkat --
                            @endif
                        </span>
                        <span class="material-symbols-outlined text-gray-400 transition-transform duration-200 dropdown-arrow">expand_more</span>
                    </button>
                    <div class="dropdown-menu-custom absolute top-full mt-1 left-0 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl z-50 hidden opacity-0 transform scale-95 transition-all duration-200 origin-top">
                        <div class="p-1.5 space-y-1">
                            <div class="dropdown-item px-4 py-2.5 rounded-md text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-3" data-value="SD">
                                <span class="material-symbols-outlined text-lg">school</span>
                                SD
                            </div>
                            <div class="dropdown-item px-4 py-2.5 rounded-md text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-3" data-value="SMP">
                                <span class="material-symbols-outlined text-lg">corporate_fare</span>
                                SMP
                            </div>
                        </div>
                    </div>
                </div>
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
                       value="{{ old('mata_pelajaran', $user->mata_pelajaran) }}"
                       placeholder="Masukkan mata pelajaran"
                       class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('mata_pelajaran') border-red-500 @enderror">
                @error('mata_pelajaran')
                    <p class="mt-1 text-[10px] sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Info -->
            <div class="mb-3 sm:mb-5 p-3 sm:p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md sm:rounded-lg">
                <div class="flex items-start gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-blue-800 dark:text-blue-300">Informasi Password</p>
                        <p class="text-[10px] sm:text-xs text-blue-700 dark:text-blue-400 mt-0.5 sm:mt-1">Password tidak diubah saat update. Gunakan "Reset Password" di halaman daftar pengguna.</p>
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
                    <span id="btnText">Update Pengguna</span>
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
        if (nikInput) {
            nikInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);
                if (this.value.length === 16) {
                    this.classList.add('border-green-500', 'dark:border-green-400');
                } else {
                    this.classList.remove('border-green-500', 'dark:border-green-400');
                }
            });
        }

        // Custom Dropdown Logic
        const dropdownContainers = document.querySelectorAll('.custom-dropdown-container');
        
        dropdownContainers.forEach(container => {
            const btn = container.querySelector('.dropdown-button');
            const menu = container.querySelector('.dropdown-menu-custom');
            const label = container.querySelector('.dropdown-label');
            const arrow = container.querySelector('.dropdown-arrow');
            const input = container.querySelector('input[type="hidden"]');
            const items = container.querySelectorAll('.dropdown-item');

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isShowing = menu.classList.contains('show');
                
                // Close all other dropdowns
                document.querySelectorAll('.dropdown-menu-custom').forEach(m => {
                    if (m !== menu) {
                        m.classList.remove('show');
                        m.closest('.custom-dropdown-container').querySelector('.dropdown-arrow').style.transform = 'rotate(0deg)';
                    }
                });

                menu.classList.toggle('show');
                arrow.style.transform = isShowing ? 'rotate(0deg)' : 'rotate(180deg)';
            });

            items.forEach(item => {
                item.addEventListener('click', () => {
                    const value = item.getAttribute('data-value');
                    const content = item.innerHTML;
                    
                    input.value = value;
                    label.innerHTML = content;
                    label.classList.remove('text-gray-400', 'dark:text-gray-500');
                    
                    // Update active state
                    items.forEach(i => i.classList.remove('active'));
                    item.classList.add('active');
                    
                    menu.classList.remove('show');
                    arrow.style.transform = 'rotate(0deg)';

                    // Trigger change event for role/tingkat logic
                    input.dispatchEvent(new Event('change'));
                });
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu-custom').forEach(m => {
                m.classList.remove('show');
                m.closest('.custom-dropdown-container').querySelector('.dropdown-arrow').style.transform = 'rotate(0deg)';
            });
        });

        // Role change handler - show/hide fields based on role
        const roleInput = document.getElementById('role');
        const tingkatField = document.getElementById('tingkatField');
        const mataPelajaranField = document.getElementById('mataPelajaranField');
        const tingkatInput = document.getElementById('tingkat');
        const mataPelajaranInput = document.getElementById('mata_pelajaran');

        function updateFieldsVisibility() {
            const selectedRole = roleInput.value;

            if (selectedRole === 'guru' || selectedRole === 'kepala_sekolah') {
                tingkatField.style.display = 'block';
                tingkatInput.required = true;
            } else {
                tingkatField.style.display = 'none';
                tingkatInput.required = false;
            }

            if (selectedRole === 'guru') {
                mataPelajaranField.style.display = 'block';
                mataPelajaranInput.required = true;
            } else {
                mataPelajaranField.style.display = 'none';
                mataPelajaranInput.required = false;
            }
        }

        roleInput.addEventListener('change', updateFieldsVisibility);

        // Initialize on page load (for existing user values)
        updateFieldsVisibility();

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
