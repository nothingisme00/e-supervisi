@extends('layouts.modern')

@section('page-title', 'Buat Supervisi Baru')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700 text-white rounded-lg p-6 mb-6">
    <h2 class="text-2xl font-bold mb-2">Buat Supervisi Baru</h2>
    <p class="text-sm text-indigo-100 dark:text-indigo-200">Isi informasi dasar untuk memulai supervisi pembelajaran Anda</p>
</div>

<!-- Form Card -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
    <form action="{{ route('guru.supervisi.store') }}" method="POST">
        @csrf

        <!-- Tanggal Supervisi -->
        <div class="mb-6">
            <label for="tanggal_supervisi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tanggal Supervisi <span class="text-gray-400 text-xs">(Opsional)</span>
            </label>
            <input
                type="date"
                name="tanggal_supervisi"
                id="tanggal_supervisi"
                value="{{ old('tanggal_supervisi', date('Y-m-d')) }}"
                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors"
            >
            @error('tanggal_supervisi')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan untuk menggunakan tanggal hari ini</p>
        </div>

        <!-- Catatan -->
        <div class="mb-6">
            <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Catatan <span class="text-gray-400 text-xs">(Opsional)</span>
            </label>
            <textarea
                name="catatan"
                id="catatan"
                rows="4"
                maxlength="500"
                placeholder="Tambahkan catatan atau deskripsi singkat tentang supervisi ini..."
                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors resize-none"
            >{{ old('catatan') }}</textarea>
            <div class="flex justify-between items-center mt-1">
                <p class="text-xs text-gray-500 dark:text-gray-400">Maksimal 500 karakter</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    <span id="charCount">0</span>/500
                </p>
            </div>
            @error('catatan')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-900 dark:text-blue-200">
                    <p class="font-semibold mb-1">Langkah Selanjutnya:</p>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>Step 1: Upload 7 dokumen evaluasi diri (wajib - harus lengkap untuk lanjut ke Step 2)</li>
                        <li>Step 2: Isi informasi proses pembelajaran (wajib)</li>
                        <li>Submit untuk direview oleh Kepala Sekolah</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between">
            <a href="{{ route('guru.home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Batal
            </a>

            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-semibold rounded-lg transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                Mulai Supervisi
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </button>
        </div>
    </form>
</div>

<script>
    // Character counter for catatan
    const catatanTextarea = document.getElementById('catatan');
    const charCount = document.getElementById('charCount');

    if (catatanTextarea && charCount) {
        // Initial count
        charCount.textContent = catatanTextarea.value.length;

        // Update on input
        catatanTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
</script>
@endsection
