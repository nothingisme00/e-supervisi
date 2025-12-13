@extends('layouts.modern')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <!-- Icon -->
        <svg class="w-24 h-24 mx-auto text-gray-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path>
        </svg>

        <!-- Title -->
        <h1 class="text-5xl font-black text-gray-800 dark:text-white mb-3">
            Koneksi Terputus
        </h1>

        <!-- Description -->
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Koneksi internet Anda terputus. Periksa koneksi jaringan Anda.
        </p>

        <!-- Buttons -->
        <div class="flex gap-4 justify-center">
            <button onclick="window.history.back()" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                Kembali
            </button>
            <button onclick="location.reload()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Coba Lagi
            </button>
        </div>
    </div>
</div>
@endsection
