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
    
    <style>
        /* Global button cursor pointer */
        button, 
        input[type="button"], 
        input[type="submit"], 
        input[type="reset"],
        .btn,
        [role="button"] {
            cursor: pointer !important;
        }

        /* Pull to Refresh Indicator */
        #pull-to-refresh-indicator {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%) translateY(-100%);
            z-index: 60; /* Below header z-50, changed from 9999 */
            transition: transform 0.3s ease;
            background: rgba(99, 102, 241, 0.95);
            color: white;
            padding: 12px 24px;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: none; /* Hidden by default */
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            pointer-events: none; /* Don't block clicks */
        }

        /* Only show on mobile and tablet */
        @media (max-width: 1024px) {
            #pull-to-refresh-indicator {
                display: flex;
            }
        }

        #pull-to-refresh-indicator.show {
            transform: translateX(-50%) translateY(0);
            pointer-events: auto; /* Allow clicks when visible */
        }

        #pull-to-refresh-indicator svg {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
    
    @livewireStyles
</head>
<body class="bg-gray-300 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-300">

@auth
    <!-- Pull to Refresh Indicator -->
    <div id="pull-to-refresh-indicator">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <span>Tarik untuk refresh...</span>
    </div>

    <!-- Wrapper untuk push effect -->
    <div id="app-wrapper" class="transition-all duration-300 ease-in-out">
        
        <!-- HEADER -->
        <header id="header" class="fixed top-0 left-0 right-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-3 sm:px-4 md:px-4 lg:px-6 py-2.5 sm:py-3 md:py-2 lg:py-2.5 z-50 transition-all duration-300 ease-in-out shadow-sm">
            <div class="flex items-center justify-between h-full">
                <!-- Left: Hamburger Menu + Logo & Brand -->
                <div class="flex items-center gap-2 md:gap-2 lg:gap-3">
                    <!-- Hamburger Menu Button (Hidden on Mobile, visible on Tablet and Desktop) -->
                    <button id="hamburger-menu" class="hidden md:block p-1.5 md:p-1.5 lg:p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-all duration-300 ease-in-out" style="transform-origin: center;">
                        <svg class="w-5 h-5 md:w-5 md:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                    <!-- Logo & Brand - Clickable -->
                    <a href="@if(Auth::user()->isAdmin()){{ route('admin.dashboard') }}@elseif(Auth::user()->isGuru()){{ route('guru.home') }}@elseif(Auth::user()->isKepalaSekolah()){{ route('kepala.dashboard') }}@else{{ url('/') }}@endif" class="flex items-center gap-1.5 sm:gap-2 md:gap-2 lg:gap-2.5 hover:opacity-80 transition-opacity cursor-pointer group">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-8 md:h-8 lg:w-9 lg:h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                            <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 md:w-5 md:h-5 lg:w-5 lg:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <!-- Always show brand text, just adjust size -->
                        <div>
                            <h3 class="text-[13px] sm:text-sm md:text-sm lg:text-base font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">E-Supervisi</h3>
                            <p class="text-[9px] sm:text-[10px] md:text-xs lg:text-sm text-gray-500 dark:text-gray-400 hidden sm:block">Sistem Supervisi Pembelajaran</p>
                        </div>
                    </a>
                </div>
                
                <!-- Right: Profile Dropdown -->
                <div class="flex items-center gap-2 md:gap-2.5 lg:gap-3">
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profile-dropdown-btn" class="flex items-center gap-1 sm:gap-1.5 md:gap-1.5 lg:gap-2 px-1.5 sm:px-2.5 md:px-2 lg:px-2.5 py-1.5 sm:py-2 md:py-1.5 lg:py-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-8 md:h-8 lg:w-9 lg:h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-[13px] sm:text-sm md:text-sm lg:text-base shadow-md">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-xs lg:text-sm font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] lg:text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(Auth::user()->role) }}</p>
                            </div>
                            <svg id="profile-arrow-icon" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5 text-gray-500 dark:text-gray-400 hidden md:block transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown-menu" class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50 origin-top-right transform transition-all duration-200 ease-out scale-95 opacity-0">
                            <!-- User Info -->
                            <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                        <span class="text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            @if(Auth::user()->isAdmin())
                                                Administrator
                                            @elseif(Auth::user()->isGuru())
                                                Guru
                                            @elseif(Auth::user()->isKepalaSekolah())
                                                Kepala Sekolah
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-2">
                                <!-- Dark Mode Toggle -->
                                <button id="theme-toggle-dropdown" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg id="theme-icon-sun-dropdown" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <svg id="theme-icon-moon-dropdown" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                        </svg>
                                    </div>
                                    <span id="theme-text-dropdown" class="flex-1 text-left font-medium">Mode Gelap</span>
                                </button>

                                <!-- Settings -->
                                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="flex-1 font-medium">Pengaturan</span>
                                </a>

                                <!-- Divider -->
                                <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>

                                <!-- Logout -->
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <div class="w-9 h-9 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                        </div>
                                        <span class="flex-1 text-left font-medium">Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- SIDEBAR BACKDROP (Mobile Only) -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 hidden md:hidden"></div>

        <!-- SIDEBAR (Hidden by default, toggleable via hamburger) -->
        <aside id="sidebar" class="hidden md:block fixed top-0 left-0 h-screen w-72 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-transform duration-300 ease-in-out z-50 -translate-x-full shadow-lg">
        <div class="h-full flex flex-col">
            <!-- User Profile Section with Hamburger - Match Header Height -->
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-3 md:px-4 lg:px-6 py-1.5 md:py-2 lg:py-2.5">
                <div class="flex items-center justify-between h-full gap-1.5 md:gap-2 lg:gap-3">
                    <!-- Hamburger Icon -->
                    <button id="hamburger-menu-sidebar" type="button" class="p-1.5 md:p-1.5 lg:p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 md:w-5 md:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- User Profile Info -->
                    <div class="flex items-center gap-1.5 md:gap-2 lg:gap-2.5 flex-1 min-w-0">
                        <div class="w-7 h-7 md:w-8 md:h-8 lg:w-9 lg:h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                            <span class="text-xs md:text-sm lg:text-base text-white font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="text-xs md:text-sm lg:text-base font-bold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</h3>
                            <p class="text-[10px] md:text-xs lg:text-sm text-gray-500 dark:text-gray-400">
                                @if(Auth::user()->isAdmin())
                                    Administrator
                                @elseif(Auth::user()->isGuru())
                                    @if(Auth::user()->tingkat)
                                        {{ Auth::user()->tingkat }} - {{ Auth::user()->mata_pelajaran }}
                                    @else
                                        Guru {{ Auth::user()->mata_pelajaran }}
                                    @endif
                                @elseif(Auth::user()->isKepalaSekolah())
                                    Kepala Sekolah {{ Auth::user()->tingkat }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NAVIGATION -->
            <nav class="flex-1 px-3 py-4 overflow-y-auto">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="flex-1">Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="flex-1">Kelola Pengguna</span>
                    @if(request()->routeIs('admin.users.*'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>
            @elseif(Auth::user()->isGuru())
                <a href="{{ route('guru.home') }}" class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guru.home') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="flex-1">Beranda</span>
                    @if(request()->routeIs('guru.home'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>
                <a href="{{ route('guru.my-supervisi') }}" class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guru.my-supervisi') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="flex-1">Supervisi Saya</span>
                    @if(request()->routeIs('guru.my-supervisi'))
                        <div class="w-1.5 h-1.5 bg-emerald-600 dark:bg-emerald-400 rounded-full"></div>
                    @endif
                </a>
            @elseif(Auth::user()->isKepalaSekolah())
                <a href="{{ route('kepala.dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('kepala.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="flex-1">Dashboard</span>
                    @if(request()->routeIs('kepala.dashboard'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>
                <a href="{{ route('kepala.evaluasi.index') }}" class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('kepala.evaluasi.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span class="flex-1">Evaluasi Supervisi</span>
                    @if(request()->routeIs('kepala.evaluasi.*'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>
            @endif
            </nav>

            <!-- SIDEBAR FOOTER -->
            <div class="border-t border-gray-200 dark:border-gray-700 px-3 py-3">
                <!-- Dark Mode Toggle -->
                <button id="theme-toggle-sidebar" class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 w-full">
                    <svg id="theme-icon-sun-sidebar" class="w-5 h-5 flex-shrink-0 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <svg id="theme-icon-moon-sidebar" class="w-5 h-5 flex-shrink-0 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <span id="theme-text-sidebar" class="flex-1 text-left">Mode Gelap</span>
                </button>

                <!-- Settings Link -->
                <a href="{{ route('settings.index') }}" class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="flex-1">Pengaturan</span>
                    @if(request()->routeIs('settings.*'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 w-full">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="flex-1 text-left">Keluar</span>
                    </button>
                </form>
                
                <!-- Footer Text -->
                <div class="text-center text-xs text-gray-400 dark:text-gray-500 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center gap-1.5">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>
                        <span>E-Supervisi 2025</span>
                    </div>
                </div>
            </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main id="main-content" class="min-h-screen pt-16 sm:pt-20 md:pt-14 lg:pt-16 pb-20 sm:pb-20 md:pb-0 transition-all duration-300 flex flex-col bg-gray-50 dark:bg-gray-900">
        <div class="p-2.5 sm:p-4 lg:p-8 bg-gray-50 dark:bg-gray-900 flex-1">
            @yield('content')
        </div>
        
        <!-- FOOTER -->
        <footer class="hidden md:block py-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Left: Brand -->
                    <a href="@if(Auth::user()->isAdmin()){{ route('admin.dashboard') }}@elseif(Auth::user()->isGuru()){{ route('guru.home') }}@elseif(Auth::user()->isKepalaSekolah()){{ route('kepala.dashboard') }}@endif" 
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

        <!-- BOTTOM NAVIGATION (Mobile Only) -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 z-50 safe-area-inset shadow-lg">
            <div class="flex items-center justify-center gap-4 sm:gap-8 px-3 sm:px-4 py-2 sm:py-3">
            @if(Auth::user()->isAdmin())
                <!-- Admin: Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 transition-transform duration-300 {{ request()->routeIs('admin.dashboard') ? 'scale-110' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Dashboard</span>
                </a>
                <!-- Admin: Users -->
                <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('admin.users.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 transition-transform duration-300 {{ request()->routeIs('admin.users.*') ? 'scale-110' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Users</span>
                </a>
                <!-- Admin: Settings -->
                <a href="{{ route('settings.index') }}" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('settings.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 transition-transform duration-300 {{ request()->routeIs('settings.*') ? 'scale-110' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Settings</span>
                </a>
            @elseif(Auth::user()->isGuru())
                <!-- Guru: Beranda (Left) -->
                <a href="{{ route('guru.home') }}" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('guru.home') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 transition-transform duration-300 {{ request()->routeIs('guru.home') ? 'scale-110' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Beranda</span>
                </a>
                <!-- Guru: Supervisi Saya (Center) -->
                <a href="{{ route('guru.my-supervisi') }}" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('guru.my-supervisi') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 transition-transform duration-300 {{ request()->routeIs('guru.my-supervisi') ? 'scale-110' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Supervisi</span>
                </a>
                <!-- Guru: Settings (Right) -->
                <a href="{{ route('settings.index') }}" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('settings.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 transition-transform duration-300 {{ request()->routeIs('settings.*') ? 'scale-110' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Settings</span>
                </a>
            @elseif(Auth::user()->isKepalaSekolah())
                <!-- Kepala: Dashboard -->
                <a href="{{ route('kepala.dashboard') }}" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all {{ request()->routeIs('kepala.dashboard') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Dashboard</span>
                </a>
                <!-- Kepala: Evaluasi -->
                <a href="{{ route('kepala.evaluasi.index') }}" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all {{ request()->routeIs('kepala.evaluasi.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Evaluasi</span>
                </a>
                <!-- Kepala: Settings -->
                <a href="{{ route('settings.index') }}" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all {{ request()->routeIs('settings.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Settings</span>
                </a>
            @endif
            </div>
        </nav>

    </div> <!-- Close app-wrapper -->

    <!-- TOAST CONTAINER -->
    <div id="toast-container" class="fixed top-24 left-1/2 -translate-x-1/2 z-[60] flex flex-col gap-3 pointer-events-none w-full max-w-2xl px-4"></div>

    <!-- MODAL CONFIRMATION -->
    <div id="modal-backdrop" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[70] items-center justify-center p-4">
        <div id="modal-content" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
            <!-- Header with Icon -->
            <div class="flex items-start gap-4 p-6 pb-4">
                <div id="modal-icon" class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-amber-100 dark:bg-amber-900/30">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 id="modal-title" class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                        Konfirmasi
                    </h3>
                    <p id="modal-message" class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Apakah Anda yakin ingin melanjutkan?
                    </p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 px-6 pb-6">
                <button onclick="closeModal()" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors">
                    Batal
                </button>
                <button id="modal-confirm-btn" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition-colors">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <!-- JS FUNCTIONALITY -->
    <script>
        const hamburgerButton = document.getElementById('hamburger-menu');
        const hamburgerSidebarButton = document.getElementById('hamburger-menu-sidebar');
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
        const appWrapper = document.getElementById('app-wrapper');
        const header = document.getElementById('header');
        const mainContent = document.getElementById('main-content');
        const html = document.documentElement;

        // Profile Dropdown
        const profileDropdownBtn = document.getElementById('profile-dropdown-btn');
        const profileDropdownMenu = document.getElementById('profile-dropdown-menu');
        const profileArrowIcon = document.getElementById('profile-arrow-icon');
        const themeToggleDropdown = document.getElementById('theme-toggle-dropdown');
        const themeSunIconDropdown = document.getElementById('theme-icon-sun-dropdown');
        const themeMoonIconDropdown = document.getElementById('theme-icon-moon-dropdown');
        const themeTextDropdown = document.getElementById('theme-text-dropdown');
        const themeToggleSidebar = document.getElementById('theme-toggle-sidebar');
        const themeSunIconSidebar = document.getElementById('theme-icon-sun-sidebar');
        const themeMoonIconSidebar = document.getElementById('theme-icon-moon-sidebar');
        const themeTextSidebar = document.getElementById('theme-text-sidebar');

        let sidebarOpen = false;

        // Hide/Show Header on Scroll
        let lastScrollTop = 0;
        let scrollTimeout;
        let isScrolling = false;
        const scrollThreshold = 10; // Minimum scroll before triggering hide/show

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Clear previous timeout
            clearTimeout(scrollTimeout);
            
            // Only trigger hide if scrolled past threshold
            if (Math.abs(scrollTop - lastScrollTop) >= scrollThreshold) {
                isScrolling = true;
                
                if (scrollTop > lastScrollTop && scrollTop > 80) {
                    // Scrolling DOWN - Hide header
                    header.style.transform = 'translateY(-100%)';
                }
                
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            }
            
            // Set timeout to detect when scrolling stops
            scrollTimeout = setTimeout(function() {
                // User stopped scrolling - Always show header
                isScrolling = false;
                header.style.transform = 'translateY(0)';
            }, 200); // Show header 200ms after user stops scrolling (increased for smoother feel)
        }, { passive: true });

        // Update all theme icons and text
        function updateThemeIcons() {
            const isDark = html.classList.contains('dark');
            
            // Dropdown icons and text
            if (themeSunIconDropdown && themeMoonIconDropdown && themeTextDropdown) {
                themeSunIconDropdown.classList.toggle('hidden', !isDark);
                themeMoonIconDropdown.classList.toggle('hidden', isDark);
                themeTextDropdown.textContent = isDark ? 'Mode Terang' : 'Mode Gelap';
            }
            
            // Sidebar icons and text
            if (themeSunIconSidebar && themeMoonIconSidebar && themeTextSidebar) {
                themeSunIconSidebar.classList.toggle('hidden', !isDark);
                themeMoonIconSidebar.classList.toggle('hidden', isDark);
                themeTextSidebar.textContent = isDark ? 'Mode Terang' : 'Mode Gelap';
            }
        }

        // Initialize theme icons on load
        updateThemeIcons();

        // Profile Dropdown functionality
        profileDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            if (profileDropdownMenu.classList.contains('hidden')) {
                // Show dropdown
                profileDropdownMenu.classList.remove('hidden');
                profileArrowIcon.style.transform = 'rotate(180deg)';
                setTimeout(() => {
                    profileDropdownMenu.classList.remove('scale-95', 'opacity-0');
                    profileDropdownMenu.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                // Hide dropdown
                profileDropdownMenu.classList.remove('scale-100', 'opacity-100');
                profileDropdownMenu.classList.add('scale-95', 'opacity-0');
                profileArrowIcon.style.transform = 'rotate(0deg)';
                setTimeout(() => {
                    profileDropdownMenu.classList.add('hidden');
                }, 200);
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileDropdownBtn.contains(e.target) && !profileDropdownMenu.contains(e.target)) {
                if (!profileDropdownMenu.classList.contains('hidden')) {
                    profileDropdownMenu.classList.remove('scale-100', 'opacity-100');
                    profileDropdownMenu.classList.add('scale-95', 'opacity-0');
                    profileArrowIcon.style.transform = 'rotate(0deg)';
                    setTimeout(() => {
                        profileDropdownMenu.classList.add('hidden');
                    }, 200);
                }
            }
        });

        function isMobile() {
            return window.innerWidth < 768; // md breakpoint
        }

        function toggleSidebar() {
            sidebarOpen = !sidebarOpen;
            
            if (sidebarOpen) {
                // Open sidebar
                sidebar.classList.remove('-translate-x-full');
                
                // Make hamburger "stay in place" - instant swap without animation
                // Just hide header hamburger, show sidebar hamburger instantly
                hamburgerButton.style.opacity = '0';
                hamburgerButton.style.visibility = 'hidden';
                
                if (isMobile()) {
                    // Mobile: Show backdrop blur overlay
                    sidebarBackdrop.classList.remove('hidden');
                } else {
                    // Desktop: Push effect - push the entire wrapper and header
                    appWrapper.style.marginLeft = '288px'; // w-72 = 288px
                    header.style.left = '288px'; // Push header too
                }
            } else {
                // Close sidebar
                sidebar.classList.add('-translate-x-full');
                
                // Show header hamburger back after sidebar closes
                setTimeout(() => {
                    hamburgerButton.style.visibility = 'visible';
                    hamburgerButton.style.opacity = '1';
                }, 300); // After sidebar animation completes
                
                // Hide backdrop
                sidebarBackdrop.classList.add('hidden');
                
                // Reset push effect
                appWrapper.style.marginLeft = '0';
                header.style.left = '0'; // Reset header position
            }
        }

        function toggleTheme() {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        }

        // Theme toggle event listeners
        if (themeToggleDropdown) {
            console.log('Theme toggle dropdown found:', themeToggleDropdown);
            themeToggleDropdown.addEventListener('click', function(e) {
                console.log('Dropdown clicked!');
                e.preventDefault();
                e.stopPropagation();
                toggleTheme();
            }, true); // Use capture phase
        }

        if (themeToggleSidebar) {
            themeToggleSidebar.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleTheme();
            }, true); // Use capture phase
        }

        // Reset sidebar on window resize
        window.addEventListener('resize', function() {
            if (sidebarOpen) {
                // Adjust push effect based on screen size
                if (isMobile()) {
                    // Mobile: Remove push, show backdrop
                    appWrapper.style.marginLeft = '0';
                    header.style.left = '0';
                    sidebarBackdrop.classList.remove('hidden');
                } else {
                    // Desktop: Apply push, hide backdrop
                    appWrapper.style.marginLeft = '288px'; // w-72 = 288px
                    header.style.left = '288px';
                    sidebarBackdrop.classList.add('hidden');
                }
            }
        });

        // Close sidebar when clicking backdrop
        sidebarBackdrop?.addEventListener('click', function() {
            if (sidebarOpen) {
                toggleSidebar();
            }
        });

        // Handle outside clicks for sidebar (but not dropdown)
        document.addEventListener('click', function(event) {
            // Check for dropdown clicks first
            if (profileDropdownBtn.contains(event.target) || profileDropdownMenu.contains(event.target)) {
                return;
            }
            
            // Then handle sidebar
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
        
        // Hamburger button in sidebar
        hamburgerSidebarButton?.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });

        // Modal functions
        let modalCallback = null;

        function showConfirmModal(message, title = 'Konfirmasi', onConfirm = null, options = {}) {
            const backdrop = document.getElementById('modal-backdrop');
            const content = document.getElementById('modal-content');
            const titleEl = document.getElementById('modal-title');
            const messageEl = document.getElementById('modal-message');
            const confirmBtn = document.getElementById('modal-confirm-btn');
            const icon = document.getElementById('modal-icon');

            // Set default options
            const config = {
                type: 'warning', // warning, danger, info
                confirmText: 'Ya, Lanjutkan',
                confirmClass: 'bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600',
                iconBg: 'bg-amber-100 dark:bg-amber-900/30',
                iconColor: 'text-amber-600 dark:text-amber-400',
                ...options
            };

            // Update modal type styling
            if (config.type === 'danger') {
                config.confirmText = options.confirmText || 'Ya, Hapus';
                config.confirmClass = 'bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600';
                config.iconBg = 'bg-red-100 dark:bg-red-900/30';
                config.iconColor = 'text-red-600 dark:text-red-400';
            } else if (config.type === 'info') {
                config.confirmText = options.confirmText || 'OK';
                config.confirmClass = 'bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600';
                config.iconBg = 'bg-blue-100 dark:bg-blue-900/30';
                config.iconColor = 'text-blue-600 dark:text-blue-400';
            }

            // Update icon styling
            icon.className = `flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center ${config.iconBg}`;
            const iconSvg = icon.querySelector('svg');
            iconSvg.className = `w-6 h-6 ${config.iconColor}`;

            // Update content
            titleEl.textContent = title;
            messageEl.textContent = message;
            modalCallback = onConfirm;

            // Update confirm button
            confirmBtn.textContent = config.confirmText;
            confirmBtn.className = `flex-1 px-4 py-2.5 ${config.confirmClass} text-white font-medium rounded-lg transition-colors`;

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

        // Pull to Refresh Functionality (Mobile & Tablet only) - TEMPORARILY DISABLED FOR DEBUGGING
        if (false) { // DISABLED to fix hamburger menu and profile dropdown click issues
        let startY = 0;
        let currentY = 0;
        let isPulling = false;
        const pullThreshold = 80;
        const pullIndicator = document.getElementById('pull-to-refresh-indicator');
        const mainContent = document.getElementById('main-content');

        // Only enable on mobile and tablet
        function isMobileOrTablet() {
            return window.innerWidth <= 1024; // <= lg breakpoint
        }

        if (isMobileOrTablet()) {
            // Touch start
            mainContent?.addEventListener('touchstart', function(e) {
                if (window.scrollY === 0) {
                    startY = e.touches[0].pageY;
                    isPulling = true;
                }
            }, { passive: true });

            // Touch move
            mainContent?.addEventListener('touchmove', function(e) {
                if (!isPulling || window.scrollY > 0) return;

                currentY = e.touches[0].pageY;
                const pullDistance = currentY - startY;

                if (pullDistance > 0 && pullDistance < pullThreshold * 2) {
                    // Show indicator as user pulls down
                    const progress = Math.min(pullDistance / pullThreshold, 1);
                    pullIndicator.style.transform = `translateX(-50%) translateY(${progress * 100 - 100}%)`;
                    pullIndicator.querySelector('span').textContent = 
                        pullDistance > pullThreshold ? 'Lepaskan untuk refresh...' : 'Tarik untuk refresh...';
                }
            }, { passive: true });

            // Touch end
            mainContent?.addEventListener('touchend', function(e) {
                if (!isPulling) return;

                const pullDistance = currentY - startY;

                if (pullDistance > pullThreshold) {
                    // Trigger refresh
                    pullIndicator.classList.add('show');
                    pullIndicator.querySelector('span').textContent = 'Memuat ulang...';
                    
                    // Reload page after short delay
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    // Reset indicator
                    pullIndicator.style.transform = 'translateX(-50%) translateY(-100%)';
                }

                isPulling = false;
                startY = 0;
                currentY = 0;
            });

            // Resize handler
            window.addEventListener('resize', function() {
                if (!isMobileOrTablet()) {
                    isPulling = false;
                    pullIndicator.style.transform = 'translateX(-50%) translateY(-100%)';
                    pullIndicator.classList.remove('show');
                }
            });
        }
        } // END OF DISABLED PULL-TO-REFRESH
    </script>

@else
    <!-- GUEST (LOGIN) -->
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 py-12 transition-colors duration-300">
        @yield('content')
    </div>
@endauth

<!-- Back to Top Button -->
@if(!View::hasSection('hide-back-to-top'))
<button id="back-to-top" class="fixed bottom-24 md:bottom-8 left-1/2 -translate-x-1/2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2.5 rounded-full shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible transition-all duration-300 z-[55] hover:-translate-y-1 hover:shadow-xl group">
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
        <span class="text-xs font-medium">Kembali ke Atas</span>
    </div>
</button>
@endif

<script>
// Back to Top Button Functionality
const backToTopBtn = document.getElementById('back-to-top');

if (backToTopBtn) {
    let isModalOpen = false;

    // Function to check if any modal/overlay is open
    function checkModalState() {
        const modalBackdrop = document.getElementById('modal-backdrop');
        const confirmModal = document.getElementById('confirmModal');
        const successModal = document.getElementById('successModal');

        // Check if any modal is visible (not hidden and has flex display)
        isModalOpen = (modalBackdrop && !modalBackdrop.classList.contains('hidden')) ||
                      (confirmModal && !confirmModal.classList.contains('hidden')) ||
                      (successModal && !successModal.classList.contains('hidden'));

        // Update button visibility based on scroll and modal state
        updateButtonVisibility();
    }

    // Function to update button visibility
    function updateButtonVisibility() {
        if (isModalOpen) {
            // Hide button when modal is open
            backToTopBtn.classList.remove('opacity-100', 'visible');
            backToTopBtn.classList.add('opacity-0', 'invisible');
        } else if (window.scrollY > 100) {
            // Show button when scrolled and no modal
            backToTopBtn.classList.remove('opacity-0', 'invisible');
            backToTopBtn.classList.add('opacity-100', 'visible');
        } else {
            // Hide button when at top
            backToTopBtn.classList.remove('opacity-100', 'visible');
            backToTopBtn.classList.add('opacity-0', 'invisible');
        }
    }

    // Show/hide button based on scroll position
    window.addEventListener('scroll', () => {
        updateButtonVisibility();
    });

    // Scroll to top when clicked
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Observe modal state changes using MutationObserver
    const observer = new MutationObserver(() => {
        checkModalState();
    });

    // Observe all potential modal elements
    const modalBackdrop = document.getElementById('modal-backdrop');
    const confirmModal = document.getElementById('confirmModal');
    const successModal = document.getElementById('successModal');

    if (modalBackdrop) {
        observer.observe(modalBackdrop, { attributes: true, attributeFilter: ['class'] });
    }
    if (confirmModal) {
        observer.observe(confirmModal, { attributes: true, attributeFilter: ['class'] });
    }
    if (successModal) {
        observer.observe(successModal, { attributes: true, attributeFilter: ['class'] });
    }

    // Initial check
    checkModalState();
}
</script>

@livewireScripts

</body>
</html>
