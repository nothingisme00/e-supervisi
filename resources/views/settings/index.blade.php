@extends('layouts.modern')

@section('page-title', 'Pengaturan')

@section('hide-back-to-top', true)

@section('content')
<div class="max-w-7xl mx-auto pb-24 md:pb-0">
    <x-page-header title="Pengaturan" subtitle="Kelola informasi profil dan keamanan akun Anda" />

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Profile Information -->
    <x-card flush>
        <!-- Header -->
        <div class="px-4 py-3 sm:px-6 sm:py-4 bg-gray-50 dark:bg-gray-800/60 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <x-icon name="users" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                Informasi Profil
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Perbarui informasi profil dan email Anda</p>
        </div>

        <!-- Form -->
        <form action="{{ route('settings.profile.update') }}" method="POST" class="p-4 sm:p-6">
            @csrf

            <!-- NIK (Read Only) -->
            <div class="mb-5">
                <x-form.field label="NIK (Tidak dapat diubah)">
                    <input type="text" value="{{ $user->nik }}" disabled class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg text-sm cursor-not-allowed">
                </x-form.field>
            </div>

            <!-- Role (Read Only) -->
            <div class="mb-5">
                <x-form.field label="Role (Tidak dapat diubah)">
                    <input type="text" value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" disabled class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg text-sm cursor-not-allowed">
                </x-form.field>
            </div>

            <!-- Name -->
            <div class="mb-5">
                <x-form.input
                    name="name"
                    label="Nama Lengkap *"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="{{ $errors->has('name') ? 'border-red-500' : '' }}" />
            </div>

            <!-- Email -->
            <div class="mb-5">
                <x-form.input
                    name="email"
                    type="email"
                    label="Email (Opsional)"
                    value="{{ old('email', $user->email) }}"
                    title="Masukkan alamat email yang valid (contoh: nama@email.com)"
                    class="{{ $errors->has('email') ? 'border-red-500' : '' }}" />
            </div>

            @if($user->tingkat)
            <!-- Tingkat (Read Only) -->
            <div class="mb-5">
                <x-form.field label="Tingkat">
                    <input type="text" value="{{ $user->tingkat }}" disabled class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg text-sm cursor-not-allowed">
                </x-form.field>
            </div>
            @endif

            @if($user->mata_pelajaran)
            <!-- Mata Pelajaran (Read Only) -->
            <div class="mb-5">
                <x-form.field label="Mata Pelajaran">
                    <input type="text" value="{{ $user->mata_pelajaran }}" disabled class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg text-sm cursor-not-allowed">
                </x-form.field>
            </div>
            @endif

            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button type="submit">
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Change Password -->
    <x-card flush>
        <!-- Header -->
        <div class="px-4 py-3 sm:px-6 sm:py-4 bg-gray-50 dark:bg-gray-800/60 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <x-icon name="lock" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                Ubah Password
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pastikan akun Anda menggunakan password yang kuat dan aman</p>
        </div>

        <!-- Form -->
        <form action="{{ route('settings.password.update') }}" method="POST" class="p-4 sm:p-6">
            @csrf

            <!-- Current Password -->
            <div class="mb-5">
                <x-form.field label="Password Lama *" name="current_password">
                    <div class="relative">
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               required
                               class="form-control pr-12 {{ $errors->has('current_password') ? 'border-red-500' : '' }}">
                        <button type="button"
                                onclick="togglePassword('current_password')"
                                aria-label="Tampilkan/sembunyikan password lama"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                            <x-icon name="eye" id="current_password_eye" class="w-5 h-5" />
                            <x-icon name="eye-slash" id="current_password_eye_slash" class="w-5 h-5 hidden" />
                        </button>
                    </div>
                </x-form.field>
            </div>

            <!-- New Password -->
            <div class="mb-5">
                <x-form.field label="Password Baru *" name="password" hint="Minimal 8 karakter">
                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               class="form-control pr-12 {{ $errors->has('password') ? 'border-red-500' : '' }}">
                        <button type="button"
                                onclick="togglePassword('password')"
                                aria-label="Tampilkan/sembunyikan password baru"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                            <x-icon name="eye" id="password_eye" class="w-5 h-5" />
                            <x-icon name="eye-slash" id="password_eye_slash" class="w-5 h-5 hidden" />
                        </button>
                    </div>
                </x-form.field>
            </div>

            <!-- Confirm Password -->
            <div class="mb-5">
                <x-form.field label="Konfirmasi Password Baru *" name="password_confirmation">
                    <div class="relative">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               class="form-control pr-12">
                        <button type="button"
                                onclick="togglePassword('password_confirmation')"
                                aria-label="Tampilkan/sembunyikan konfirmasi password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer">
                            <x-icon name="eye" id="password_confirmation_eye" class="w-5 h-5" />
                            <x-icon name="eye-slash" id="password_confirmation_eye_slash" class="w-5 h-5 hidden" />
                        </button>
                    </div>
                </x-form.field>
            </div>

            <!-- Info Box -->
            <div class="mb-5 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                <div class="flex items-start gap-3">
                    <x-icon name="information-circle" class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 shrink-0" />
                    <div>
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-300">Tips Keamanan Password</p>
                        <ul class="mt-2 text-xs text-amber-700 dark:text-amber-400 space-y-1 list-disc list-inside">
                            <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                            <li>Jangan gunakan password yang sama dengan akun lain</li>
                            <li>Hindari menggunakan informasi pribadi yang mudah ditebak</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button type="submit">
                    Ubah Password
                </x-button>
            </div>
        </form>
    </x-card>

    </div>
    <!-- End Two Column Layout -->

</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eyeIcon = document.getElementById(inputId + '_eye');
    const eyeSlashIcon = document.getElementById(inputId + '_eye_slash');

    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeSlashIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeSlashIcon.classList.add('hidden');
    }
}
</script>
@endsection
