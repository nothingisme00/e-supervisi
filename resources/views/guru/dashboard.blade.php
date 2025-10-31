@extends('layouts.app')

@section('title', 'Dashboard Guru - E-Supervisi')

@section('content')
<div class="max-w-5xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Selamat Datang, {{ Auth::user()->name }}</h2>

    <p class="text-gray-600 mb-6">
        Ini adalah halaman <strong>Dashboard Guru</strong>.  
        Anda dapat mengunggah dokumen <strong>Lembar Evaluasi Diri</strong> dan mengisi refleksi kegiatan pembelajaran.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('guru.supervisi.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-center p-4 rounded-lg shadow transition">
            📄 Unggah Lembar Evaluasi Diri
        </a>

        <a href="#"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold text-center p-4 rounded-lg shadow transition">
            📊 Lihat Progres Supervisi
        </a>
    </div>

    <div class="mt-10 text-center text-sm text-gray-500">
        <p>Terakhir login: {{ Auth::user()->updated_at->diffForHumans() }}</p>
    </div>
</div>
@endsection
