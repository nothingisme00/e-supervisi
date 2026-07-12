@extends('layouts.modern')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <!-- Icon -->
        <div class="w-16 h-16 mx-auto mb-6 bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center">
            <x-icon name="link" class="w-8 h-8 text-primary-600 dark:text-primary-400" />
        </div>

        <!-- Title -->
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white mb-3">
            Koneksi Terputus
        </h1>

        <!-- Description -->
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Koneksi internet Anda terputus. Periksa koneksi jaringan Anda.
        </p>

        <!-- Buttons -->
        <div class="flex gap-3 justify-center">
            <x-button variant="secondary" onclick="window.history.back()">
                Kembali
            </x-button>
            <x-button onclick="location.reload()">
                Coba Lagi
            </x-button>
        </div>
    </div>
</div>
@endsection
