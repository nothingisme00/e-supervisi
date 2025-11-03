<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Supervisi') }}</title>

    <!-- Apply theme before body renders to prevent flash -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

@auth
    <!-- Wrapper untuk push effect -->
    <div id="app-wrapper" class="transition-all duration-300 ease-in-out">
        
        <!-- HEADER -->
        <header id="header" class="fixed top-0 left-0 right-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 z-50 transition-all duration-300 shadow-sm">
            <div class="flex items-center justify-between">
                <!-- Left: Hamburger Menu + Logo & Page Info -->
                <div class="flex items-center gap-4">
                    <!-- Hamburger Menu Button -->
                    <button id="hamburger-menu" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                    <!-- Logo & Brand - Clickable -->
                    <a href="@if(Auth::user()->isAdmin()){{ route('admin.dashboard') }}@elseif(Auth::user()->isGuru()){{ route('guru.home') }}@elseif(Auth::user()->isKepalaSekolah()){{ route('kepala.dashboard') }}@else{{ url('/') }}@endif" class="flex items-center gap-3 hover:opacity-80 transition-opacity cursor-pointer group">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">E-Supervisi</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                @if(request()->routeIs('admin.dashboard'))
                                    Dashboard Admin
                                @elseif(request()->routeIs('admin.users.*'))
                                    Kelola Pengguna
                                @elseif(request()->routeIs('guru.home'))
                                    Beranda Guru
                                @elseif(request()->routeIs('guru.supervisi.*'))
                                    Supervisi Pembelajaran
                                @elseif(request()->routeIs('kepala.dashboard'))
                                    Dashboard Kepala Sekolah
                                @elseif(request()->routeIs('kepala.evaluasi.*'))
                                    Evaluasi Supervisi
                                @else
                                    Sistem Pembelajaran
                                @endif
                            </p>
                        </div>
                    </a>
                </div>
                
                <!-- Right: User Info + Dark Mode + Logout -->
                <div class="flex items-center gap-3">
                    <!-- User Info -->
                    <div class="hidden md:flex items-center gap-3 pr-3 border-r border-gray-300 dark:border-gray-600">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                @if(Auth::user()->isAdmin())
                                    Administrator
                                @elseif(Auth::user()->isGuru())
                                    Guru
                                @elseif(Auth::user()->isKepalaSekolah())
                                    Kepala Sekolah
                                @endif
                            </p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle-header" class="p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors" title="Toggle dark mode">
                        <svg id="theme-icon-sun-header" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg id="theme-icon-moon-header" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- SIDEBAR -->
        <aside id="sidebar" class="fixed top-0 left-0 h-screen w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform -translate-x-full transition-all duration-300 ease-in-out z-50 shadow-xl overflow-y-auto">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">E-Supervisi</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Sistem Pembelajaran</p>
                    </div>
                </div>
            </div>        <!-- User Profile -->
        <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-300 truncate">
                        @if(Auth::user()->isAdmin())
                            Administrator
                        @elseif(Auth::user()->isGuru())
                            Guru {{ Auth::user()->mata_pelajaran }}
                        @elseif(Auth::user()->isKepalaSekolah())
                            Kepala Sekolah
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- NAVIGATION -->
        <nav class="py-3 flex-1 overflow-y-auto">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 mx-2 mb-1 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 mb-1 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.users.*') ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Kelola Pengguna</span>
                </a>
            @elseif(Auth::user()->isGuru())
                <a href="{{ route('guru.home') }}" class="flex items-center gap-3 px-4 py-3 mx-2 mb-1 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('guru.home') ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('guru.supervisi.create') }}" class="flex items-center gap-3 px-4 py-3 mx-2 mb-1 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('guru.supervisi.*') ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Supervisi</span>
                </a>
            @elseif(Auth::user()->isKepalaSekolah())
                <a href="{{ route('kepala.dashboard') }}" class="flex items-center gap-3 px-4 py-3 mx-2 mb-1 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('kepala.dashboard') ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('kepala.evaluasi.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 mb-1 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('kepala.evaluasi.*') ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>Evaluasi Supervisi</span>
                </a>
            @endif
        </nav>

        <!-- SIDEBAR FOOTER -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4">
            <div class="text-center text-xs text-gray-500 dark:text-gray-400">
                © 2025 E-Supervisi
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main id="main-content" class="min-h-screen pt-20 transition-all duration-300">
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
    </div> <!-- Close app-wrapper -->

    <!-- JS FUNCTIONALITY -->
    <script>
        const hamburgerButton = document.getElementById('hamburger-menu');
        const sidebar = document.getElementById('sidebar');
        const appWrapper = document.getElementById('app-wrapper');
        const header = document.getElementById('header');
        const mainContent = document.getElementById('main-content');
        const html = document.documentElement;
        const themeToggleHeader = document.getElementById('theme-toggle-header');
        const themeIconSunHeader = document.getElementById('theme-icon-sun-header');
        const themeIconMoonHeader = document.getElementById('theme-icon-moon-header');

        let sidebarOpen = false;

        // Sync icon states with current theme (already applied in <head>)
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            themeIconMoonHeader.classList.remove('hidden');
            themeIconSunHeader.classList.add('hidden');
        } else {
            themeIconSunHeader.classList.remove('hidden');
            themeIconMoonHeader.classList.add('hidden');
        }

        function toggleSidebar() {
            sidebarOpen = !sidebarOpen;
            
            if (sidebarOpen) {
                // Open sidebar
                sidebar.classList.remove('-translate-x-full');
                
                // Push effect for all devices
                appWrapper.style.marginLeft = '256px';
                header.style.left = '256px';
                mainContent.style.marginLeft = '0';
            } else {
                // Close sidebar
                sidebar.classList.add('-translate-x-full');
                
                // Reset push effect
                appWrapper.style.marginLeft = '0';
                header.style.left = '0';
                mainContent.style.marginLeft = '0';
            }
        }

        function toggleTheme() {
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            
            // Toggle header icons
            if (isDark) {
                themeIconMoonHeader.classList.remove('hidden');
                themeIconSunHeader.classList.add('hidden');
            } else {
                themeIconSunHeader.classList.remove('hidden');
                themeIconMoonHeader.classList.add('hidden');
            }
        }

        // Reset sidebar on window resize
        window.addEventListener('resize', function() {
            if (sidebarOpen) {
                appWrapper.style.marginLeft = '256px';
                header.style.left = '256px';
                mainContent.style.marginLeft = '0';
            }
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (sidebarOpen && 
                !sidebar.contains(event.target) && 
                !hamburgerButton.contains(event.target)) {
                toggleSidebar();
            }
        });

        // Event listeners
        hamburgerButton?.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
        themeToggleHeader?.addEventListener('click', toggleTheme);
    </script>

@else
    <!-- GUEST (LOGIN) -->
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 py-12 transition-colors duration-300">
        @yield('content')
    </div>
@endauth
</body>
</html>
