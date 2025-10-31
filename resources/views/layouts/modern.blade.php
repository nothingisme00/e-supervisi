<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Supervisi') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    @auth
    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-0 left-0 right-0 bg-white border-b border-gray-200 px-4 py-3 z-50 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900">E-Supervisi</h3>
        <button id="mobile-menu-button" class="p-2 rounded-lg hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 bottom-0 w-60 bg-white border-r border-gray-200 overflow-y-auto transition-transform duration-300 -translate-x-full lg:translate-x-0 z-40">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">E-Supervisi</h3>
        </div>

        <div class="p-5 bg-gray-50 border-b border-gray-200">
            <div class="text-sm font-semibold text-gray-900 mb-1">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-600">
                @if(Auth::user()->isAdmin())
                    Administrator
                @elseif(Auth::user()->isGuru())
                    Guru {{ Auth::user()->mata_pelajaran }}
                @elseif(Auth::user()->isKepalaSekolah())
                    Kepala Sekolah
                @endif
            </div>
        </div>

        <nav class="py-3">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="block px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="block px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    Kelola Pengguna
                </a>
            @elseif(Auth::user()->isGuru())
                <a href="{{ route('guru.home') }}" class="block px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('guru.home') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('guru.supervisi.create') }}" class="block px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('guru.supervisi.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    Supervisi
                </a>
            @elseif(Auth::user()->isKepalaSekolah())
                <a href="{{ route('kepala.dashboard') }}" class="block px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('kepala.dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('kepala.evaluasi.index') }}" class="block px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('kepala.evaluasi.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    Evaluasi Supervisi
                </a>
            @endif
        </nav>
    </div>

    <!-- Overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

    <!-- Main Content -->
    <div class="lg:ml-60 min-h-screen">
        <!-- Topbar -->
        <div class="sticky top-0 z-20 bg-white border-b border-gray-200 px-4 lg:px-8 py-4 flex items-center justify-between mt-14 lg:mt-0">
            <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-colors">
                    Keluar
                </button>
            </form>
        </div>

        <!-- Content Area -->
        <div class="p-4 lg:p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 border-l-4 border-green-500 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-800 border-l-4 border-red-500 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleMobileMenu() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', toggleMobileMenu);
        }

        if (overlay) {
            overlay.addEventListener('click', toggleMobileMenu);
        }
    </script>
    @else
    <!-- Guest Layout -->
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-purple-500 to-purple-600">
        @yield('content')
    </div>
    @endauth
</body>
</html>
