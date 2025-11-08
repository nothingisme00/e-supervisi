@extends('layouts.modern')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <!-- Error Code -->
        <h1 class="text-9xl font-black text-red-500 mb-4">404</h1>

        <!-- Title -->
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-3">
            Halaman Tidak Ditemukan
        </h2>

        <!-- Description -->
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Maaf, halaman yang Anda cari tidak ditemukan.
        </p>

        <!-- Buttons -->
        <div class="flex gap-4 justify-center">
            <button onclick="window.history.back()" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                Kembali
            </button>
            <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'guru' ? route('guru.home') : route('kepala.dashboard'))) : route('login') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
