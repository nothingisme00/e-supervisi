@extends('layouts.modern')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <!-- Error Code -->
        <h1 class="text-9xl font-black text-purple-500 mb-4">419</h1>

        <!-- Title -->
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-3">
            Sesi Anda Telah Berakhir
        </h2>

        <!-- Description -->
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Halaman ini telah terbuka terlalu lama. Silakan refresh halaman.
        </p>

        <!-- Buttons -->
        <div class="flex gap-4 justify-center">
            <button onclick="window.history.back()" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                Kembali
            </button>
            <button onclick="location.reload()" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Refresh Halaman
            </button>
        </div>
    </div>
</div>
@endsection
