@extends('layouts.modern')

@section('content')
<div class="min-h-screen w-full flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <!-- Icon -->
        <div class="w-16 h-16 mx-auto mb-6 bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center">
            <x-icon name="search" class="w-8 h-8 text-primary-600 dark:text-primary-400" />
        </div>

        <!-- Error Code -->
        <h1 class="text-7xl font-extrabold text-primary-600 dark:text-primary-400 mb-4 tabular-nums">404</h1>

        <!-- Title -->
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white mb-3">
            Halaman Tidak Ditemukan
        </h2>

        <!-- Description -->
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Maaf, halaman yang Anda cari tidak ditemukan.
        </p>

        <!-- Buttons -->
        <div class="flex gap-3 justify-center">
            <x-button variant="secondary" onclick="window.history.back()">
                Kembali
            </x-button>
            <x-button href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'guru' ? route('guru.home') : route('kepala.dashboard'))) : route('login') }}">
                <x-icon name="home" class="w-4 h-4" />
                Kembali ke Beranda
            </x-button>
        </div>
    </div>
</div>
@endsection
