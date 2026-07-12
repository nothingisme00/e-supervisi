@extends('layouts.modern')

@section('content')
<div class="min-h-screen w-full flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <!-- Icon -->
        <div class="w-16 h-16 mx-auto mb-6 bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center">
            <x-icon name="clock" class="w-8 h-8 text-primary-600 dark:text-primary-400" />
        </div>

        <!-- Error Code -->
        <h1 class="text-7xl font-extrabold text-primary-600 dark:text-primary-400 mb-4 tabular-nums">419</h1>

        <!-- Title -->
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white mb-3">
            Sesi Anda Telah Berakhir
        </h2>

        <!-- Description -->
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Halaman ini telah terbuka terlalu lama. Silakan refresh halaman.
        </p>

        <!-- Buttons -->
        <div class="flex gap-3 justify-center">
            <x-button variant="secondary" onclick="window.history.back()">
                Kembali
            </x-button>
            <x-button onclick="location.reload()">
                Refresh Halaman
            </x-button>
        </div>
    </div>
</div>
@endsection
