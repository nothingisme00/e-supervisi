@extends('layouts.modern')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-2xl">
        <!-- Error Code -->
        <h1 class="text-9xl font-black text-orange-500 mb-4">500</h1>

        <!-- Title -->
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-3">
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
        <div class="flex gap-4 justify-center">
            <button onclick="location.reload()" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                Refresh
            </button>
            <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'guru' ? route('guru.home') : route('kepala.dashboard'))) : route('login') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
