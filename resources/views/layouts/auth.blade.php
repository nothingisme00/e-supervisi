{{--
    Layout dokumen HTML lengkap untuk halaman auth standalone (login,
    ganti-password) — split-screen carousel + panel form, di luar shell
    `layouts.modern` (tanpa header/sidebar). Dibangun dari build Vite
    (bukan Tailwind CDN) supaya konsisten dengan seluruh aplikasi.

    Pemakaian di halaman anak (komentar Blade TIDAK bisa bersarang — contoh
    di bawah sengaja tanpa penanda komentar):
        extends('layouts.auth')
        section('page-title', 'Login')
        push('styles')  -> CSS spesifik halaman
        section('content') -> isi halaman
        push('scripts') -> JS spesifik halaman

    CATATAN: footer auth (E-Supervisi · Sistem Supervisi Pembelajaran) adalah
    tanggung jawab HALAMAN (posisinya menyatu di dalam panel form), bukan
    layout ini — lihat login.blade.php / change-password.blade.php.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('page-title')@yield('page-title') · @endif{{ config('app.name', 'E-Supervisi') }}</title>

    @include('partials.theme-init')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased h-screen w-full flex overflow-hidden selection:bg-primary-600 selection:text-white">

@yield('content')

@stack('scripts')
</body>
</html>
