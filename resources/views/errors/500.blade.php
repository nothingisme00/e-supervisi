@extends('layouts.modern')

@section('content')
<div class="min-h-screen w-full flex items-center justify-center p-4">
    <div class="text-center max-w-2xl">
        <!-- Icon -->
        <div class="w-16 h-16 mx-auto mb-6 bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center">
            <x-icon name="exclamation-triangle" class="w-8 h-8 text-primary-600 dark:text-primary-400" />
        </div>

        <!-- Error Code -->
        <h1 class="text-7xl font-extrabold text-primary-600 dark:text-primary-400 mb-4 tabular-nums">500</h1>

        <!-- Title -->
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white mb-3">
            Terjadi Kesalahan Server
        </h2>

        <!-- Description -->
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Maaf, terjadi kesalahan pada server. Tim kami sedang memperbaikinya.
        </p>

        @if(config('app.debug') && isset($exception))
        <!-- Debug Info -->
        <div class="text-left mb-8 p-4 border border-red-300 dark:border-red-700 rounded-lg">
            <p class="text-sm font-mono text-red-600 dark:text-red-400"><strong>Error:</strong> {{ $exception->getMessage() }}</p>
            <p class="text-sm font-mono text-red-600 dark:text-red-400 mt-2"><strong>File:</strong> {{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
        </div>
        @endif

        <!-- Buttons -->
        <div class="flex gap-3 justify-center">
            <x-button variant="secondary" onclick="location.reload()">
                Refresh
            </x-button>
            <x-button href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'guru' ? route('guru.home') : route('kepala.dashboard'))) : route('login') }}">
                <x-icon name="home" class="w-4 h-4" />
                Kembali ke Beranda
            </x-button>
        </div>
    </div>
</div>
@endsection
