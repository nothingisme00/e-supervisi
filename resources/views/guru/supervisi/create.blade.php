@extends('layouts.modern')

@section('page-title', 'Mulai Supervisi Pembelajaran Baru')

@section('content')
<!-- Main Container -->
<div class="max-w-3xl mx-auto px-0 sm:px-4 pb-24 md:pb-0">
    <!-- Confirmation Card -->
    <x-card flush>

        <!-- Header -->
        <div class="bg-primary-600 dark:bg-primary-700 px-4 py-4 sm:px-6 sm:py-6 text-center">
            <div class="w-10 h-10 sm:w-16 sm:h-16 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <x-icon name="check-circle" class="w-5 h-5 sm:w-8 sm:h-8 text-white" />
            </div>
            <h2 class="text-base sm:text-2xl font-bold text-white mb-1 sm:mb-2">Mulai Supervisi Baru?</h2>
            <p class="text-primary-100 dark:text-primary-200 text-xs sm:text-sm">
                Tanggal: <strong>{{ now()->translatedFormat('d F Y') }}</strong>
            </p>
        </div>

        <!-- Body Content -->
        <div class="p-3 sm:p-6">
            <!-- Yang Perlu Disiapkan -->
            <div class="mb-3 sm:mb-5">
                <h3 class="text-xs sm:text-base font-bold text-gray-900 dark:text-white mb-2 sm:mb-3">Yang Perlu Disiapkan:</h3>
                <div class="space-y-1.5 sm:space-y-2">
                    <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-gray-50 dark:bg-gray-900/30 rounded-lg">
                        <x-icon name="document" class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400 shrink-0" />
                        <p class="text-xs sm:text-sm text-gray-900 dark:text-white"><strong>7 Dokumen Evaluasi Diri</strong> (RPP, Silabus, dll)</p>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-gray-50 dark:bg-gray-900/30 rounded-lg">
                        <x-icon name="video-camera" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 shrink-0" />
                        <p class="text-xs sm:text-sm text-gray-900 dark:text-white"><strong>Video Pembelajaran</strong> & Refleksi</p>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-gray-50 dark:bg-gray-900/30 rounded-lg">
                        <x-icon name="calendar" class="w-4 h-4 sm:w-5 sm:h-5 text-primary-600 dark:text-primary-400 shrink-0" />
                        <p class="text-xs sm:text-sm text-gray-900 dark:text-white"><strong>Informasi Pembelajaran</strong> (tanggal, kelas)</p>
                    </div>
                </div>
            </div>

            <!-- Alur Proses -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 sm:p-4 mb-3 sm:mb-5 border border-blue-200 dark:border-blue-800">
                <div class="flex gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                    <x-icon name="information-circle" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 shrink-0" />
                    <p class="text-xs sm:text-sm font-semibold text-blue-900 dark:text-blue-200">Alur Proses:</p>
                </div>
                <ol class="list-decimal list-inside space-y-0.5 sm:space-y-1 text-xs sm:text-sm text-blue-800 dark:text-blue-300 ml-5 sm:ml-7">
                    <li>Upload dokumen evaluasi</li>
                    <li>Isi informasi & upload video</li>
                    <li>Submit untuk direview</li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <form action="{{ route('guru.supervisi.store') }}" method="POST">
                @csrf
                <div class="flex gap-2 sm:gap-3">
                    <x-button href="{{ route('guru.home') }}" variant="secondary" class="flex-1 justify-center">
                        Batal
                    </x-button>
                    <x-button type="submit" class="flex-1 justify-center">
                        Ya, Mulai
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </x-button>
                </div>
            </form>
        </div>

    </x-card>
</div>

@endsection
