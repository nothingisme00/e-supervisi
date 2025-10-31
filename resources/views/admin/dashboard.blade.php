@extends('layouts.modern')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 mb-2">Total Pengguna</div>
        <div class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 mb-2">Total Guru</div>
        <div class="text-3xl font-bold text-gray-900">{{ $totalGuru }}</div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 mb-2">Total Supervisi</div>
        <div class="text-3xl font-bold text-gray-900">{{ $totalSupervisi }}</div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 mb-2">Perlu Direview</div>
        <div class="text-3xl font-bold text-gray-900">{{ $supervisiSubmitted }}</div>
    </div>
</div>

<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <h3 class="text-base font-semibold text-gray-900 mb-4">Menu Utama</h3>
    <a href="{{ route('admin.users.index') }}" class="inline-block px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
        Kelola Pengguna
    </a>
</div>

<div class="bg-white rounded-lg border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-3">Informasi</h3>
    <p class="text-gray-700 text-sm leading-relaxed">
        Selamat datang di Dashboard Administrator E-Supervisi. Dari sini Anda dapat mengelola semua pengguna sistem dan memantau aktivitas supervisi pembelajaran.
    </p>
</div>
@endsection
