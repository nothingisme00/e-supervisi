@extends('layouts.modern')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <!-- Error Code -->
        <h1 class="text-9xl font-black text-yellow-500 mb-4">503</h1>

        <!-- Title -->
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-3">
            Sedang Dalam Pemeliharaan
        </h2>

        <!-- Description -->
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Kami sedang melakukan pemeliharaan sistem. Mohon kembali lagi dalam beberapa saat.
        </p>

        <!-- Button -->
        <button onclick="location.reload()" class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
            Coba Lagi
        </button>
    </div>
</div>
@endsection
