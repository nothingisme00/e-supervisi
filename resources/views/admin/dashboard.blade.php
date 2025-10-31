@extends('layouts.modern')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Total Pengguna</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Total Guru</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalGuru }}</div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Total Supervisi</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalSupervisi }}</div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Perlu Direview</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supervisiSubmitted }}</div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Menu Utama</h3>
    <a href="{{ route('admin.users.index') }}" class="inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
        Kelola Pengguna
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Informasi</h3>
    <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
        Selamat datang di Dashboard Administrator E-Supervisi. Dari sini Anda dapat mengelola semua pengguna sistem dan memantau aktivitas supervisi pembelajaran.
    </p>
</div>
@endsection
