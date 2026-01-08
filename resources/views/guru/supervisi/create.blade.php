@extends('layouts.modern')

@section('page-title', 'Mulai Supervisi Pembelajaran Baru')

@section('content')
<!-- Main Container -->
<div class="max-w-3xl mx-auto px-0 sm:px-4 pb-24 md:pb-0">
    <!-- Confirmation Card -->
    <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700 px-4 py-4 sm:px-6 sm:py-6 text-center">
            <div class="w-10 h-10 sm:w-16 sm:h-16 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <svg class="w-5 h-5 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-base sm:text-2xl font-bold text-white mb-1 sm:mb-2">Mulai Supervisi Baru?</h2>
            <p class="text-indigo-100 dark:text-indigo-200 text-xs sm:text-sm">
                Tanggal: <strong>{{ date('d F Y') }}</strong>
            </p>
        </div>

        <!-- Body Content -->
        <div class="p-3 sm:p-6">
            <!-- Yang Perlu Disiapkan -->
            <div class="mb-3 sm:mb-5">
                <h3 class="text-xs sm:text-base font-bold text-gray-900 dark:text-white mb-2 sm:mb-3">Yang Perlu Disiapkan:</h3>
                <div class="space-y-1.5 sm:space-y-2">
                    <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-gray-50 dark:bg-gray-900/30 rounded-md sm:rounded-lg">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs sm:text-sm text-gray-900 dark:text-white"><strong>7 Dokumen Evaluasi Diri</strong> (RPP, Silabus, dll)</p>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-gray-50 dark:bg-gray-900/30 rounded-md sm:rounded-lg">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs sm:text-sm text-gray-900 dark:text-white"><strong>Video Pembelajaran</strong> & Refleksi</p>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-gray-50 dark:bg-gray-900/30 rounded-md sm:rounded-lg">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs sm:text-sm text-gray-900 dark:text-white"><strong>Informasi Pembelajaran</strong> (tanggal, kelas)</p>
                    </div>
                </div>
            </div>

            <!-- Alur Proses -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-md sm:rounded-lg p-3 sm:p-4 mb-3 sm:mb-5 border border-blue-200 dark:border-blue-800">
                <div class="flex gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs sm:text-sm font-semibold text-blue-900 dark:text-blue-200">Alur Proses:</p>
                </div>
                <ol class="list-decimal list-inside space-y-0.5 sm:space-y-1 text-[11px] sm:text-sm text-blue-800 dark:text-blue-300 ml-5 sm:ml-7">
                    <li>Upload dokumen evaluasi</li>
                    <li>Isi informasi & upload video</li>
                    <li>Submit untuk direview</li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <form action="{{ route('guru.supervisi.store') }}" method="POST">
                @csrf
                <div class="flex gap-2 sm:gap-3">
                    <a href="{{ route('guru.home') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-5 sm:py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-semibold rounded-md sm:rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-5 sm:py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-xs sm:text-sm font-bold rounded-md sm:rounded-lg transition-all shadow-md hover:shadow-lg">
                        Ya, Mulai
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection

