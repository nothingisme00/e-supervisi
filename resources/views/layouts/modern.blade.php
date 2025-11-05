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
            <!-- SIDEBAR BACKDROP (Mobile Only) -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 hidden md:hidden"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed top-0 left-0 h-screen w-64 bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 border-r border-gray-200 dark:border-gray-700/50 transition-transform duration-300 ease-in-out z-50 -translate-x-full shadow-xl">
        <div class="h-full flex flex-col">
            <!-- User Profile -->
        <div class="p-5 border-b border-gray-200 dark:border-gray-700/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="relative">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
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
        <nav class="py-4 px-3 flex-1 overflow-y-auto">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="group relative flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg shadow-indigo-500/50 scale-[1.02]' : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 hover:shadow-md hover:scale-[1.02]' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <span class="flex-1">Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}" class="group relative flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg shadow-indigo-500/50 scale-[1.02]' : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 hover:shadow-md hover:scale-[1.02]' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-all {{ request()->routeIs('admin.users.*') ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="flex-1">Kelola Pengguna</span>
                    @if(request()->routeIs('admin.users.*'))
                        <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>
            @elseif(Auth::user()->isGuru())
                <a href="{{ route('guru.home') }}" class="group relative flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('guru.home') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg shadow-indigo-500/50 scale-[1.02]' : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 hover:shadow-md hover:scale-[1.02]' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-all {{ request()->routeIs('guru.home') ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <span class="flex-1">Beranda</span>
                    @if(request()->routeIs('guru.home'))
                        <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>
                <a href="{{ route('guru.supervisi.create') }}" class="group relative flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('guru.supervisi.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg shadow-indigo-500/50 scale-[1.02]' : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 hover:shadow-md hover:scale-[1.02]' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-all {{ request()->routeIs('guru.supervisi.*') ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="flex-1">Supervisi</span>
                    @if(request()->routeIs('guru.supervisi.*'))
                        <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>
            @elseif(Auth::user()->isKepalaSekolah())
                <a href="{{ route('kepala.dashboard') }}" class="group relative flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('kepala.dashboard') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg shadow-indigo-500/50 scale-[1.02]' : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 hover:shadow-md hover:scale-[1.02]' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-all {{ request()->routeIs('kepala.dashboard') ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <span class="flex-1">Dashboard</span>
                    @if(request()->routeIs('kepala.dashboard'))
                        <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>
                <a href="{{ route('kepala.evaluasi.index') }}" class="group relative flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('kepala.evaluasi.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg shadow-indigo-500/50 scale-[1.02]' : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 hover:shadow-md hover:scale-[1.02]' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-all {{ request()->routeIs('kepala.evaluasi.*') ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="flex-1">Evaluasi Supervisi</span>
                    @if(request()->routeIs('kepala.evaluasi.*'))
                        <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>
            @endif
        </nav>

        <!-- SIDEBAR FOOTER -->
        <div class="border-t border-gray-200 dark:border-gray-700/50 p-3 bg-white/50 dark:bg-gray-800/50">
            <!-- Settings Link -->
            <a href="{{ route('settings.index') }}" class="group relative flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg shadow-indigo-500/50' : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 hover:shadow-md' }}">
                <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-all {{ request()->routeIs('settings.*') ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="flex-1">Pengaturan</span>
                @if(request()->routeIs('settings.*'))
                    <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                @endif
            </a>
            
            <div class="text-center text-xs text-gray-400 dark:text-gray-500 mt-3 font-medium">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    <span>E-Supervisi 2025</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main id="main-content" class="min-h-screen pt-20 transition-all duration-300 flex flex-col">
        <div class="p-4 lg:p-8 bg-gray-50 dark:bg-gray-900 flex-1">
            @yield('content')
        </div>
        
        <!-- FOOTER -->
        <footer class="py-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Left: Brand -->
                    <a href="@if(Auth::user()->isAdmin()){{ route('admin.dashboard') }}@elseif(Auth::user()->isGuru()){{ route('guru.dashboard') }}@elseif(Auth::user()->isKepalaSekolah()){{ route('kepala.dashboard') }}@endif" 
                       class="flex items-center gap-3 hover:opacity-80 transition-opacity group">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">E-Supervisi</span>
                            <span class="text-gray-400 dark:text-gray-500">•</span>
                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                @if(Auth::user()->isAdmin())
                                    Admin Panel
                                @elseif(Auth::user()->isGuru())
                                    Portal Guru
                                @elseif(Auth::user()->isKepalaSekolah())
                                    Portal Kepala Sekolah
                                @endif
                            </span>
                        </div>
                    </a>
                    
                    <!-- Right: Copyright -->
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <span>© 2025 Sistem Supervisi Pembelajaran</span>
                    </div>
                </div>
            </div>
        </footer>
    </main>
    </div> <!-- Close app-wrapper -->

    <!-- TOAST CONTAINER -->
    <div id="toast-container" class="fixed top-24 left-1/2 -translate-x-1/2 z-[60] flex flex-col gap-3 pointer-events-none w-full max-w-2xl px-4"></div>

    <!-- MODAL CONFIRMATION -->
    <div id="modal-backdrop" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[70] flex items-center justify-center p-4 animate-fade-in">
        <div id="modal-content" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
            <div class="p-6">
                <!-- Icon -->
                <div class="flex justify-center mb-4">
                    <div id="modal-icon" class="w-16 h-16 rounded-full flex items-center justify-center bg-red-100 dark:bg-red-900/30">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
                <!-- Title -->
                <h3 id="modal-title" class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">
                    Konfirmasi Hapus
                </h3>
                <!-- Message -->
                <p id="modal-message" class="text-gray-600 dark:text-gray-400 text-center mb-6">
                    Apakah Anda yakin ingin menghapus item ini?
                </p>
                <!-- Buttons -->
                <div class="flex gap-3">
                    <button onclick="closeModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button id="modal-confirm-btn" class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-semibold rounded-xl transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS FUNCTIONALITY -->
    <script>
        const hamburgerButton = document.getElementById('hamburger-menu');
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
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

        function isMobile() {
            return window.innerWidth < 768; // md breakpoint
        }

        function toggleSidebar() {
            sidebarOpen = !sidebarOpen;
            
            if (sidebarOpen) {
                // Open sidebar
                sidebar.classList.remove('-translate-x-full');
                
                if (isMobile()) {
                    // Mobile: Show backdrop blur overlay
                    sidebarBackdrop.classList.remove('hidden');
                } else {
                    // Desktop: Push effect
                    appWrapper.style.marginLeft = '256px';
                    header.style.left = '256px';
                    mainContent.style.marginLeft = '0';
                }
            } else {
                // Close sidebar
                sidebar.classList.add('-translate-x-full');
                
                // Hide backdrop
                sidebarBackdrop.classList.add('hidden');
                
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
                // Close sidebar if switching from desktop to mobile or vice versa
                if (isMobile()) {
                    appWrapper.style.marginLeft = '0';
                    header.style.left = '0';
                    mainContent.style.marginLeft = '0';
                    sidebarBackdrop.classList.remove('hidden');
                } else {
                    appWrapper.style.marginLeft = '256px';
                    header.style.left = '256px';
                    mainContent.style.marginLeft = '0';
                    sidebarBackdrop.classList.add('hidden');
                }
            }
        });

        // Close sidebar when clicking backdrop or outside
        sidebarBackdrop?.addEventListener('click', function() {
            if (sidebarOpen) {
                toggleSidebar();
            }
        });

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

        // Modal Confirmation System
        let modalCallback = null;

        function showConfirmModal(message, title = 'Konfirmasi Hapus', onConfirm = null) {
            const backdrop = document.getElementById('modal-backdrop');
            const content = document.getElementById('modal-content');
            const titleEl = document.getElementById('modal-title');
            const messageEl = document.getElementById('modal-message');
            const confirmBtn = document.getElementById('modal-confirm-btn');
            
            titleEl.textContent = title;
            messageEl.textContent = message;
            modalCallback = onConfirm;
            
            // Show modal
            backdrop.classList.remove('hidden');
            backdrop.classList.add('flex');
            
            // Animate in
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Update confirm button listener
            confirmBtn.onclick = function() {
                if (modalCallback) {
                    modalCallback();
                }
                closeModal();
            };
        }

        function closeModal() {
            const backdrop = document.getElementById('modal-backdrop');
            const content = document.getElementById('modal-content');
            
            // Animate out
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                backdrop.classList.remove('flex');
                backdrop.classList.add('hidden');
                modalCallback = null;
            }, 300);
        }

        // Close modal on backdrop click
        document.getElementById('modal-backdrop')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Toast Notification System
        function showToast(message, type = 'success', duration = 4000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            // Define colors based on type
            const colors = {
                success: 'bg-green-500 dark:bg-green-600',
                error: 'bg-red-500 dark:bg-red-600',
                warning: 'bg-amber-500 dark:bg-amber-600',
                info: 'bg-blue-500 dark:bg-blue-600'
            };
            
            // Define icons based on type
            const icons = {
                success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
                info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
            };
            
            toast.className = `${colors[type] || colors.success} text-white px-6 py-5 rounded-xl shadow-2xl flex items-center gap-4 w-full pointer-events-auto transform transition-all duration-500 -translate-y-32 opacity-0`;
            toast.innerHTML = `
                <svg class="w-7 h-7 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${icons[type] || icons.success}
                </svg>
                <p class="flex-1 text-base font-semibold leading-relaxed">${message}</p>
                <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white transition-colors ml-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Trigger animation - slide down from top
            setTimeout(() => {
                toast.classList.remove('-translate-y-32', 'opacity-0');
            }, 10);
            
            // Auto remove with slide up animation
            setTimeout(() => {
                toast.classList.add('-translate-y-32', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, duration);
        }

        // Show session flash messages as toast
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        @if(session('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @endif
        @if(session('info'))
            showToast('{{ session('info') }}', 'info');
        @endif
    </script>

@else
    <!-- GUEST (LOGIN) -->
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 py-12 transition-colors duration-300">
        @yield('content')
    </div>
@endauth
</body>
</html>
