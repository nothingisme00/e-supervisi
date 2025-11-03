<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Supervisi') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

@auth
    <!-- HEADER -->
    <header class="fixed top-0 left-0 right-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-3 z-50 flex items-center justify-between transition-colors duration-300">
        <div class="flex items-center gap-3">
            <button id="mobile-menu-button" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h3 class="text-lg font-bold">E-Supervisi</h3>
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed top-0 left-0 h-screen w-60 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform -translate-x-full transition-transform duration-300 ease-in-out z-40">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">E-Supervisi</h3>
        </div>

        <div class="p-5 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-600 dark:text-gray-300">
                @if(Auth::user()->isAdmin())
                    Administrator
                @elseif(Auth::user()->isGuru())
                    Guru {{ Auth::user()->mata_pelajaran }}
                @elseif(Auth::user()->isKepalaSekolah())
                    Kepala Sekolah
                @endif
            </div>
        </div>

        <!-- NAVIGATION -->
        <nav class="py-3 flex-1 overflow-y-auto">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="block px-5 py-3 text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="block px-5 py-3 text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    Kelola Pengguna
                </a>
            @elseif(Auth::user()->isGuru())
                <a href="{{ route('guru.home') }}" class="block px-5 py-3 text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('guru.home') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    Beranda
                </a>
                <a href="{{ route('guru.supervisi.create') }}" class="block px-5 py-3 text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('guru.supervisi.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    Supervisi
                </a>
            @elseif(Auth::user()->isKepalaSekolah())
                <a href="{{ route('kepala.dashboard') }}" class="block px-5 py-3 text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('kepala.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    Dashboard
                </a>
                <a href="{{ route('kepala.evaluasi.index') }}" class="block px-5 py-3 text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('kepala.evaluasi.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    Evaluasi Supervisi
                </a>
            @endif
        </nav>

        <!-- SIDEBAR FOOTER -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4">
            <button id="theme-toggle" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors w-full justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span id="theme-text">Light</span>
            </button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- OVERLAY -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 backdrop-blur-sm z-30 hidden transition-all duration-300"></div>

    <!-- MAIN CONTENT -->
    <main class="min-h-screen pt-[64px] transition-colors duration-300">
        <div class="p-4 lg:p-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-200 border-l-4 border-green-500 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200 border-l-4 border-red-500 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- JS FUNCTIONALITY -->
    <script>
        const menuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const html = document.documentElement;
        const themeText = document.getElementById('theme-text');
        const themeToggleSidebar = document.getElementById('theme-toggle');

        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            html.classList.add('dark');
            themeText.textContent = 'Dark';
        } else {
            html.classList.remove('dark');
            themeText.textContent = 'Light';
        }

        function toggleMenu() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleTheme() {
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeText.textContent = isDark ? 'Dark' : 'Light';
        }

        menuButton?.addEventListener('click', toggleMenu);
        overlay?.addEventListener('click', toggleMenu);
        themeToggleSidebar?.addEventListener('click', toggleTheme);
    </script>

@else
    <!-- GUEST (LOGIN) -->
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 py-12 transition-colors duration-300">
        @yield('content')
    </div>
@endauth
</body>
</html>
