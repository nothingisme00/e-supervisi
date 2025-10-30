<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-300">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">
    <button id="theme-toggle"
                class="fixed top-4 right-4 z-50 bg-gray-200 dark:bg-gray-700 p-2 rounded-full shadow-md hover:scale-105 transition"
                title="Ganti Tema">
            {{-- Icon Matahari (Light Mode) --}}
            <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 text-gray-800 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 3v1m0 16v1m8.66-10H21M3 12H2m15.36 6.36l.7.7M6.34 6.34l-.7-.7m0 12.72l.7-.7m12.02-12.02l.7.7M12 5a7 7 0 000 14 7 7 0 000-14z" />
            </svg>

            {{-- Icon Bulan (Dark Mode) --}}
            <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 text-yellow-400 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
            </svg>
        </button>
    <div class="min-h-screen">
        
        {{-- 🔹 Navbar --}}
        @include('layouts.navigation')

        {{-- 🌙 Tombol Dark Mode Toggle --}}
        

        {{-- 🔹 Page Heading --}}
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-300">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- 🔹 Page Content --}}
        <main class="transition-colors duration-300">
            @yield('content')
        </main>
    </div>

    {{-- 🌙 Dark Mode Toggle Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById('theme-toggle');
            const root = document.documentElement;

            // Apply saved theme
            if (localStorage.getItem('theme') === 'dark') {
                root.classList.add('dark');
            } else if (localStorage.getItem('theme') === 'light') {
                root.classList.remove('dark');
            } else {
                // Default: follow system preference
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    root.classList.add('dark');
                }
            }

            // Handle toggle click
            toggle.addEventListener('click', () => {
                root.classList.toggle('dark');
                const newTheme = root.classList.contains('dark') ? 'dark' : 'light';
                localStorage.setItem('theme', newTheme);
            });
        });
    </script>
</body>
</html>
