@extends('layouts.modern')

@section('page-title', 'Edit Pengguna')

@section('content')
<style>
    .dropdown-item.active {
        background-color: #0f766e;
        color: white !important;
    }
    .dropdown-item.active span {
        color: white !important;
    }
    .dropdown-menu-custom.show {
        display: block !important;
        opacity: 1 !important;
        transform: scale(1) !important;
    }
</style>

<div class="max-w-3xl mx-auto pb-24 md:pb-0">
    <x-page-header title="Edit Pengguna" subtitle="Perbarui data akun pengguna" :back-url="route('admin.users.index')" />

    <!-- Warning Banner untuk Edit Diri Sendiri -->
    @if($isEditingSelf)
    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 dark:border-amber-600 rounded-r-lg">
        <div class="flex items-start">
            <x-icon name="exclamation-triangle" class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600 dark:text-amber-400 mt-0.5 mr-2 sm:mr-3 shrink-0" />
            <div>
                <h2 class="text-xs sm:text-sm font-semibold text-amber-900 dark:text-amber-300 mb-0.5 sm:mb-1">Anda Sedang Mengedit Data Diri Sendiri</h2>
                <p class="text-xs text-amber-800 dark:text-amber-400 leading-relaxed">
                    Berhati-hatilah saat mengubah data diri, terutama <strong>Email</strong> untuk login.
                </p>
            </div>
        </div>
    </div>
    @endif

    <x-card class="p-4 sm:p-6 lg:p-8">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="editUserForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 mb-4 sm:mb-5">
                <!-- NIK -->
                <x-form.input
                    name="nik"
                    label="NIK (Maksimal 16 digit) *"
                    value="{{ old('nik', $user->nik) }}"
                    maxlength="16"
                    required
                    inputmode="numeric"
                    placeholder="Masukkan NIK (maksimal 16 digit)"
                    class="{{ $errors->has('nik') ? 'border-red-500' : '' }}" />

                <!-- Nama Lengkap -->
                <x-form.input
                    name="name"
                    label="Nama Lengkap *"
                    value="{{ old('name', $user->name) }}"
                    required
                    placeholder="Masukkan nama lengkap"
                    class="{{ $errors->has('name') ? 'border-red-500' : '' }}" />
            </div>

            <!-- Email -->
            <div class="mb-4 sm:mb-5">
                <x-form.input
                    name="email"
                    type="email"
                    label="Email *"
                    value="{{ old('email', $user->email) }}"
                    required
                    placeholder="Masukkan email"
                    class="{{ $errors->has('email') ? 'border-red-500' : '' }}" />
            </div>

            <!-- Role -->
            <div class="mb-4 sm:mb-5">
                <x-form.field label="Role *" name="role">
                    <div class="relative custom-dropdown-container" id="role-dropdown-container">
                        <input type="hidden" name="role" id="role" value="{{ old('role', $user->role) }}" required>
                        <button type="button"
                                class="dropdown-button form-control text-left flex items-center justify-between cursor-pointer {{ $errors->has('role') ? 'border-red-500' : '' }}">
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
                            <x-icon name="chevron-down" class="dropdown-arrow w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" />
                        </button>
                        <div class="dropdown-menu-custom absolute top-full mt-1 left-0 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-50 hidden opacity-0 transform scale-95 transition-all duration-200 origin-top">
                            <div class="p-1.5 space-y-1">
                                <div class="dropdown-item px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 cursor-pointer transition-colors" data-value="admin">Admin</div>
                                <div class="dropdown-item px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 cursor-pointer transition-colors" data-value="kepala_sekolah">Kepala Sekolah</div>
                                <div class="dropdown-item px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 cursor-pointer transition-colors" data-value="guru">Guru</div>
                            </div>
                        </div>
                    </div>
                </x-form.field>
            </div>

            <!-- Tingkat (Conditional - for guru and kepala_sekolah) -->
            <div class="mb-4 sm:mb-5" id="tingkatField" style="display: none;">
                <x-form.field label="Tingkat *" name="tingkat">
                    <div class="relative custom-dropdown-container" id="tingkat-dropdown-container">
                        <input type="hidden" name="tingkat" id="tingkat" value="{{ old('tingkat', $user->tingkat) }}">
                        <button type="button"
                                class="dropdown-button form-control text-left flex items-center justify-between cursor-pointer {{ $errors->has('tingkat') ? 'border-red-500' : '' }}">
                            <span class="dropdown-label flex items-center gap-2 {{ old('tingkat', $user->tingkat) ? '' : 'text-gray-400 dark:text-gray-500' }}">
                                @if(old('tingkat', $user->tingkat) == 'SD')
                                    SD
                                @elseif(old('tingkat', $user->tingkat) == 'SMP')
                                    SMP
                                @else
                                    -- Pilih Tingkat --
                                @endif
                            </span>
                            <x-icon name="chevron-down" class="dropdown-arrow w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" />
                        </button>
                        <div class="dropdown-menu-custom absolute top-full mt-1 left-0 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-50 hidden opacity-0 transform scale-95 transition-all duration-200 origin-top">
                            <div class="p-1.5 space-y-1">
                                <div class="dropdown-item px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 cursor-pointer transition-colors" data-value="SD">SD</div>
                                <div class="dropdown-item px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 cursor-pointer transition-colors" data-value="SMP">SMP</div>
                            </div>
                        </div>
                    </div>
                </x-form.field>
            </div>

            <!-- Mata Pelajaran (Conditional - for guru only) -->
            <div class="mb-4 sm:mb-5" id="mataPelajaranField" style="display: none;">
                <x-form.input
                    name="mata_pelajaran"
                    label="Mata Pelajaran *"
                    value="{{ old('mata_pelajaran', $user->mata_pelajaran) }}"
                    placeholder="Masukkan mata pelajaran"
                    class="{{ $errors->has('mata_pelajaran') ? 'border-red-500' : '' }}" />
            </div>

            <!-- Password Info -->
            <div class="mb-4 sm:mb-5 p-3 sm:p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex items-start gap-2 sm:gap-3">
                    <x-icon name="information-circle" class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 shrink-0" />
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-blue-800 dark:text-blue-300">Informasi Password</p>
                        <p class="text-xs text-blue-700 dark:text-blue-400 mt-0.5 sm:mt-1">Password tidak diubah saat update. Gunakan "Reset Password" di halaman daftar pengguna.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-2 sm:gap-3 pt-4 sm:pt-5 border-t border-gray-200 dark:border-gray-700">
                <x-button href="{{ route('admin.users.index') }}" variant="secondary">
                    Batal
                </x-button>
                <x-button type="submit" id="submitBtn">
                    <span id="btnText">Update Pengguna</span>
                    <span id="btnLoader" class="hidden">
                        <div class="spinner inline-block"></div>
                        <span class="ml-2">Menyimpan...</span>
                    </span>
                </x-button>
            </div>
        </form>
    </x-card>
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
                const isShowing = !menu.classList.contains('hidden');

                // Close all other dropdowns first
                document.querySelectorAll('.custom-dropdown-container').forEach(otherContainer => {
                    if (otherContainer !== container) {
                        otherContainer.querySelector('.dropdown-menu-custom').classList.add('hidden');
                        otherContainer.querySelector('.dropdown-arrow').style.transform = 'rotate(0deg)';
                    }
                });

                if (isShowing) {
                    menu.classList.add('hidden');
                    arrow.style.transform = 'rotate(0deg)';
                } else {
                    menu.classList.remove('hidden');
                    arrow.style.transform = 'rotate(180deg)';
                }
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

                    menu.classList.add('hidden');
                    arrow.style.transform = 'rotate(0deg)';

                    // Trigger change event for role/tingkat logic
                    input.dispatchEvent(new Event('change'));
                });
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            document.querySelectorAll('.custom-dropdown-container').forEach(container => {
                const menu = container.querySelector('.dropdown-menu-custom');
                const arrow = container.querySelector('.dropdown-arrow');
                if (!container.contains(e.target)) {
                    menu.classList.add('hidden');
                    arrow.style.transform = 'rotate(0deg)';
                }
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
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
        });
    });
</script>
@endsection
