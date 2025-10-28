@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah - E-Supervisi')

@section('content')
<div class="max-w-5xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
        Selamat datang, {{ $user->name }}
    </h2>

    <p class="text-gray-600 dark:text-gray-300 mb-8">
        Anda masuk sebagai <strong>Kepala Sekolah</strong>.
        Dari halaman ini, Anda dapat melihat laporan supervisi guru, status evaluasi, dan progres pembelajaran.
    </p>

    <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-xl text-center border border-blue-100 dark:border-blue-700">
            <h3 class="text-lg font-semibold text-blue-700 dark:text-blue-400">Total Guru</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-2">24</p>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-xl text-center border border-green-100 dark:border-green-700">
            <h3 class="text-lg font-semibold text-green-700 dark:text-green-400">Sudah Disupervisi</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-2">18</p>
        </div>

        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-xl text-center border border-yellow-100 dark:border-yellow-700">
            <h3 class="text-lg font-semibold text-yellow-700 dark:text-yellow-400">Belum Disupervisi</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-2">6</p>
        </div>
    </div>

    <div class="mt-10 flex flex-wrap gap-4 justify-center">
        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition-all duration-150 ease-in-out">
            📊 Lihat Laporan Supervisi
        </a>
        <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition-all duration-150 ease-in-out">
            📁 Data Guru
        </a>
    </div>
</div>
@endsection
