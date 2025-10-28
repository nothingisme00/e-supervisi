@extends('layouts.admin')

@section('title', 'Dashboard Admin - E-Supervisi')
@section('page-title', 'Selamat Datang, ' . Auth::user()->name)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white shadow rounded-xl p-6 hover:shadow-lg transition">
        <h3 class="text-sm text-gray-500 mb-1">Jumlah Guru</h3>
        <p class="text-3xl font-bold text-blue-600">{{ $totalGuru }}</p>
    </div>

    <div class="bg-white shadow rounded-xl p-6 hover:shadow-lg transition">
        <h3 class="text-sm text-gray-500 mb-1">Laporan Masuk</h3>
        <p class="text-3xl font-bold text-green-600">{{ $laporanMasuk }}</p>
    </div>

    <div class="bg-white shadow rounded-xl p-6 hover:shadow-lg transition">
        <h3 class="text-sm text-gray-500 mb-1">Evaluasi Tersimpan</h3>
        <p class="text-3xl font-bold text-purple-600">{{ $evaluasiDisetujui }}</p>
    </div>

</div>

<div class="mt-8 bg-white shadow rounded-xl p-6">
    <h2 class="text-lg font-semibold mb-4 text-gray-700">Statistik Evaluasi</h2>
    <p class="text-gray-700 text-sm">Rata-rata Nilai: 
        <span class="font-bold text-blue-600">
            {{ number_format($rataNilai, 2) ?? '0.00' }}
        </span>
    </p>
</div>
@endsection
