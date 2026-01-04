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
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    
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
        /* Wire Navigate Loading Bar */
        .navigate-loading-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #a855f7, #6366f1);
            background-size: 200% 100%;
            animation: loading-shimmer 1.5s ease-in-out infinite;
            z-index: 9999;
            transition: width 0.2s ease;
        }
        
        @keyframes loading-shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Page transition animation */
        [wire\:navigate] {
            transition: opacity 0.15s ease;
        }
    </style>
    
    @livewireStyles
</head>
<body class="bg-gray-300 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-300">

@auth
    <!-- Livewire Navigate Loading Bar -->
    <div id="navigate-loading-bar" class="navigate-loading-bar" style="width: 0%; display: none;"></div>

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
        <header id="header" class="fixed top-0 left-0 right-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 sm:px-5 md:px-5 lg:px-6 py-4 sm:py-5 md:py-4 lg:py-5 z-50 transition-all duration-300 ease-in-out shadow-sm">
            <div class="flex items-center justify-between h-full">
                <!-- Left: Hamburger Menu + Logo & Brand -->
                <div class="flex items-center gap-2 md:gap-2 lg:gap-3">
                    <!-- Hamburger Menu Button (Hidden on Mobile, visible on Tablet and Desktop) -->
                    <button id="hamburger-menu" class="hidden md:block p-1.5 md:p-1.5 lg:p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-all duration-300 ease-in-out" style="transform-origin: center;">
                        <svg class="w-5 h-5 md:w-5 md:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                    <!-- Logo & Brand - Clickable (Full reload to avoid SPA issues) -->
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
                
                <!-- Right: Profile Dropdown (Hidden on mobile, show on md+) -->
                <div class="hidden md:flex items-center gap-2.5 lg:gap-3">
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
                                <a href="{{ route('settings.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
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
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="flex-1">Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="flex-1">Kelola Pengguna</span>
                    @if(request()->routeIs('admin.users.*'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>
                <a href="{{ route('admin.carousel.index') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.carousel.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="flex-1">Carousel Login</span>
                    @if(request()->routeIs('admin.carousel.*'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>
            @elseif(Auth::user()->isGuru())
                <a href="{{ route('guru.home') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guru.home') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="flex-1">Beranda</span>
                    @if(request()->routeIs('guru.home'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>
                <a href="{{ route('guru.my-supervisi') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guru.my-supervisi') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="flex-1">Supervisi Saya</span>
                    @if(request()->routeIs('guru.my-supervisi'))
                        <div class="w-1.5 h-1.5 bg-emerald-600 dark:bg-emerald-400 rounded-full"></div>
                    @endif
                </a>
            @elseif(Auth::user()->isKepalaSekolah())
                <a href="{{ route('kepala.dashboard') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('kepala.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="flex-1">Dashboard</span>
                    @if(request()->routeIs('kepala.dashboard'))
                        <div class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                    @endif
                </a>
                <a href="{{ route('kepala.evaluasi.index') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('kepala.evaluasi.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
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
                <a href="{{ route('settings.index') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
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
        <main id="main-content" class="min-h-screen pt-[72px] sm:pt-[88px] md:pt-[72px] lg:pt-[84px] pb-20 sm:pb-20 md:pb-0 transition-all duration-300 flex flex-col bg-gray-50 dark:bg-gray-900">
        <div class="p-2.5 sm:p-4 lg:p-8 bg-gray-50 dark:bg-gray-900 flex-1">
            @yield('content')
        </div>
        
        <!-- FOOTER -->
        <footer class="hidden md:block py-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Left: Brand -->
                    <a href="@if(Auth::user()->isAdmin()){{ route('admin.dashboard') }}@elseif(Auth::user()->isGuru()){{ route('guru.home') }}@elseif(Auth::user()->isKepalaSekolah()){{ route('kepala.dashboard') }}@endif" 
                       wire:navigate class="flex items-center gap-3 hover:opacity-80 transition-opacity group">
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
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 transition-transform duration-300 {{ request()->routeIs('admin.dashboard') ? 'scale-110' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Dashboard</span>
                </a>
                <!-- Admin: Users -->
                <a href="{{ route('admin.users.index') }}" wire:navigate class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('admin.users.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 transition-transform duration-300 {{ request()->routeIs('admin.users.*') ? 'scale-110' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Users</span>
                </a>
                <!-- Admin: Bantuan (Help Menu) -->
                <div class="relative" x-data="{ open: false }" id="adminBantuanMenu">
                    <button @click="open = !open" class="flex flex-col items-center justify-center gap-1 px-3 sm:px-4 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-semibold">Bantuan</span>
                    </button>
                    
                    <!-- Bantuan Popup Menu -->
                    <div x-show="open" x-cloak @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95 translate-y-2" x-transition:enter-end="opacity-100 transform scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100 translate-y-0" x-transition:leave-end="opacity-0 transform scale-95 translate-y-2" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                        <!-- Header -->
                        <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Pusat Bantuan</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Tips & panduan admin</p>
                        </div>
                        <div class="py-2">
                            <!-- Tips & Informasi -->
                            <button @click="open = false; if(typeof toggleTips === 'function') toggleTips();" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">Tips & Informasi</span>
                            </button>
                            <!-- Panduan -->
                            <button @click="open = false; if(typeof openGuideModal === 'function') openGuideModal();" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">Panduan Admin</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Admin: Profile -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-semibold">Profil</span>
                    </button>
                    
                    <!-- Profile Popup Menu -->
                    <div x-show="open" x-cloak @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95 translate-y-2" x-transition:enter-end="opacity-100 transform scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100 translate-y-0" x-transition:leave-end="opacity-0 transform scale-95 translate-y-2" class="absolute bottom-full right-0 mb-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                        <!-- User Info -->
                        <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Administrator</p>
                        </div>
                        <div class="py-2">
                            <!-- Settings -->
                            <a href="{{ route('settings.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-medium">Pengaturan</span>
                            </a>
                            <!-- Theme Toggle -->
                            <button id="mobile-theme-toggle-admin" onclick="toggleTheme()" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span class="font-medium dark:hidden">Mode Gelap</span>
                                <span class="font-medium hidden dark:inline">Mode Terang</span>
                            </button>
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <!-- Logout -->
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span class="font-medium">Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->isGuru())
                <!-- Guru: Beranda (Left) -->
                <a href="{{ route('guru.home') }}" wire:navigate class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('guru.home') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 transition-transform duration-300 {{ request()->routeIs('guru.home') ? 'scale-110' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Beranda</span>
                </a>
                <!-- Guru: Supervisi Saya (Center) -->
                <a href="{{ route('guru.my-supervisi') }}" wire:navigate class="flex flex-col items-center justify-center gap-1 px-3 sm:px-4 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('guru.my-supervisi') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 transition-transform duration-300 {{ request()->routeIs('guru.my-supervisi') ? 'scale-110' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Supervisi</span>
                </a>
                <!-- Guru: Bantuan (Help Menu) -->
                <div class="relative" x-data="{ open: false }" id="bantuanMenu">
                    <button @click="open = !open" class="flex flex-col items-center justify-center gap-1 px-3 sm:px-4 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-semibold">Bantuan</span>
                    </button>
                    
                    <!-- Bantuan Popup Menu -->
                    <div x-show="open" x-cloak @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95 translate-y-2" x-transition:enter-end="opacity-100 transform scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100 translate-y-0" x-transition:leave-end="opacity-0 transform scale-95 translate-y-2" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                        <!-- Header -->
                        <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Pusat Bantuan</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Tips & panduan supervisi</p>
                        </div>
                        <div class="py-2">
                            <!-- Tips & Informasi -->
                            <button @click="open = false; toggleTipsFromNav();" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">Tips & Informasi</span>
                            </button>
                            <!-- Panduan -->
                            <button @click="open = false; openGuideModal();" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">Panduan Supervisi</span>
                            </button>
                            <!-- Fitur Penting -->
                            <button @click="open = false; openFiturPentingModal();" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">Fitur Penting</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Guru: Profile -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-semibold">Profil</span>
                    </button>
                    
                    <!-- Profile Popup Menu -->
                    <div x-show="open" x-cloak @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95 translate-y-2" x-transition:enter-end="opacity-100 transform scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100 translate-y-0" x-transition:leave-end="opacity-0 transform scale-95 translate-y-2" class="absolute bottom-full right-0 mb-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                        <!-- User Info -->
                        <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Guru</p>
                        </div>
                        <div class="py-2">
                            <!-- Settings -->
                            <a href="{{ route('settings.index') }}" wire:navigate @click="open = false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-medium">Pengaturan</span>
                            </a>
                            <!-- Theme Toggle -->
                            <button @click="open = false; toggleTheme();" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span class="font-medium dark:hidden">Mode Gelap</span>
                                <span class="font-medium hidden dark:inline">Mode Terang</span>
                            </button>
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <!-- Logout -->
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" @click="open = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span class="font-medium">Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->isKepalaSekolah())
                <!-- Kepala: Dashboard -->
                <a href="{{ route('kepala.dashboard') }}" wire:navigate class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all {{ request()->routeIs('kepala.dashboard') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Dashboard</span>
                </a>
                <!-- Kepala: Evaluasi -->
                <a href="{{ route('kepala.evaluasi.index') }}" wire:navigate class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all {{ request()->routeIs('kepala.evaluasi.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/40 scale-105 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">Evaluasi</span>
                </a>
                <!-- Kepala: Profile -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex flex-col items-center justify-center gap-1 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all duration-300 ease-in-out text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-semibold">Profil</span>
                    </button>
                    
                    <!-- Profile Popup Menu -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95 translate-y-2" x-transition:enter-end="opacity-100 transform scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100 translate-y-0" x-transition:leave-end="opacity-0 transform scale-95 translate-y-2" class="absolute bottom-full right-0 mb-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                        <!-- User Info -->
                        <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Kepala Sekolah</p>
                        </div>
                        <div class="py-2">
                            <!-- Settings -->
                            <a href="{{ route('settings.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-medium">Pengaturan</span>
                            </a>
                            <!-- Theme Toggle -->
                            <button onclick="toggleTheme()" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span class="font-medium dark:hidden">Mode Gelap</span>
                                <span class="font-medium hidden dark:inline">Mode Terang</span>
                            </button>
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <!-- Logout -->
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span class="font-medium">Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
            </div>
        </nav>

    </div> <!-- Close app-wrapper -->

    @auth
    @if(Auth::user()->isAdmin() && !request()->routeIs('admin.dashboard'))
    <!-- Admin Tips Modal (for non-dashboard pages) -->
    <div id="adminTipsModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[90] hidden items-center justify-center p-4 opacity-0 transition-opacity duration-500" onclick="if(event.target === this) closeAdminTipsModal();">
        <div id="adminTipsModalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden transform scale-90 opacity-0 transition-all duration-500">
            <!-- Header -->
            <div class="bg-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Tips & Informasi</h3>
                            <p class="text-xs text-white opacity-70">Panduan cepat admin</p>
                        </div>
                    </div>
                    <button onclick="closeAdminTipsModal()" class="w-9 h-9 rounded-lg hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Content -->
            <div class="p-4 overflow-y-auto max-h-[calc(90vh-140px)]">
                <div class="space-y-3">
                    <!-- Tip 1: Bottom Navigation -->
                    <div class="flex gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-sm text-gray-900 dark:text-white mb-1">Navigasi Bawah</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Gunakan menu di bagian bawah layar untuk berpindah antara Dashboard, Users, dan Profil.</p>
                        </div>
                    </div>

                    <!-- Tip 2: Dashboard Cards -->
                    <div class="flex gap-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-sm text-gray-900 dark:text-white mb-1">Monitoring Supervisi</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Scroll card di Dashboard untuk melihat data Guru, Supervisi Dalam Proses, dan yang Selesai.</p>
                        </div>
                    </div>

                    <!-- Tip 3: Add User -->
                    <div class="flex gap-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-sm text-gray-900 dark:text-white mb-1">Tambah Pengguna</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Tap menu "Users" di navigasi bawah, lalu gunakan tombol + untuk menambah pengguna baru.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="border-t-2 border-gray-300 dark:border-gray-600 px-4 py-3 bg-gray-50 dark:bg-gray-900/50">
                <button onclick="closeAdminTipsModal()" class="w-full px-5 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg text-sm">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- Admin Guide Modal (for non-dashboard pages) -->
    <div id="adminGuideModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[90] hidden items-center justify-center p-4 opacity-0 transition-opacity duration-500" onclick="if(event.target === this) closeAdminGuideModal();">
        <div id="adminGuideModalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden transform scale-90 opacity-0 transition-all duration-500">
            <!-- Header -->
            <div class="bg-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Panduan Admin</h3>
                            <p class="text-xs text-white opacity-70">Cara menggunakan aplikasi</p>
                        </div>
                    </div>
                    <button onclick="closeAdminGuideModal()" class="w-9 h-9 rounded-lg hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Content -->
            <div class="p-6 overflow-y-auto max-h-[60vh]">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Panduan penggunaan aplikasi untuk perangkat mobile.</p>
                
                <div class="space-y-3">
                    <!-- Item 1: Navigasi -->
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-600 dark:bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-1">Navigasi Bawah</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Gunakan menu di bagian bawah layar untuk berpindah antara Dashboard, Users, Bantuan, dan Profil.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2: Tambah User -->
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-green-600 dark:bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-1">Tambah User Baru</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Tap menu "Users" di navigasi bawah, lalu gunakan tombol + untuk menambah pengguna baru.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3: Kelola User -->
                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-purple-600 dark:bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-1">Kelola Pengguna</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Tap user di daftar untuk mengedit data, reset password, atau menghapus akun.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Item 4: Dashboard -->
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-200 dark:border-indigo-800">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-indigo-600 dark:bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-1">Monitor Dashboard</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Scroll card di Dashboard untuk melihat Data Guru, Supervisi Dalam Proses, dan Supervisi Selesai.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="border-t-2 border-gray-300 dark:border-gray-600 px-4 py-3 bg-gray-50 dark:bg-gray-900/50">
                <button onclick="closeAdminGuideModal()" class="w-full px-5 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg text-sm">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
    // Admin Tips Modal Functions
    function toggleTips() {
        openAdminTipsModal();
    }
    
    function openAdminTipsModal() {
        const modal = document.getElementById('adminTipsModal');
        const content = document.getElementById('adminTipsModalContent');
        if (modal && content) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                content.classList.remove('scale-90', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 50);
        }
    }
    
    function closeAdminTipsModal() {
        const modal = document.getElementById('adminTipsModal');
        const content = document.getElementById('adminTipsModalContent');
        if (modal && content) {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-90', 'opacity-0');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }
    }
    
    // Admin Guide Modal Functions
    function openGuideModal() {
        openAdminGuideModal();
    }
    
    function openAdminGuideModal() {
        const modal = document.getElementById('adminGuideModal');
        const content = document.getElementById('adminGuideModalContent');
        if (modal && content) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                content.classList.remove('scale-90', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 50);
        }
    }
    
    function closeAdminGuideModal() {
        const modal = document.getElementById('adminGuideModal');
        const content = document.getElementById('adminGuideModalContent');
        if (modal && content) {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-90', 'opacity-0');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }
    }
    
    // ESC key handler for admin modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('adminTipsModal')?.classList.contains('flex')) {
                closeAdminTipsModal();
            }
            if (document.getElementById('adminGuideModal')?.classList.contains('flex')) {
                closeAdminGuideModal();
            }
        }
    });
    </script>
    @endif
    @endauth

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
    (function() {
        // Clear flag to allow fresh initialization on each page load
        window._layoutInitialized = true;

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

        // Reset sidebar state after Livewire navigation (wire:navigate)
        document.addEventListener('livewire:navigated', function() {
            sidebarOpen = false;
        });

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
            // Fresh DOM lookup for wire:navigate compatibility
            const currentSidebar = document.getElementById('sidebar');
            const currentHamburgerButton = document.getElementById('hamburger-menu');
            const currentSidebarBackdrop = document.getElementById('sidebar-backdrop');
            const currentAppWrapper = document.getElementById('app-wrapper');
            const currentHeader = document.getElementById('header');
            
            if (!currentSidebar) return; // Safety check
            
            sidebarOpen = !sidebarOpen;
            
            if (sidebarOpen) {
                // Open sidebar
                currentSidebar.classList.remove('-translate-x-full');
                
                // Make hamburger "stay in place" - instant swap without animation
                // Just hide header hamburger, show sidebar hamburger instantly
                if (currentHamburgerButton) {
                    currentHamburgerButton.style.opacity = '0';
                    currentHamburgerButton.style.visibility = 'hidden';
                }
                
                if (isMobile()) {
                    // Mobile: Show backdrop blur overlay
                    if (currentSidebarBackdrop) currentSidebarBackdrop.classList.remove('hidden');
                } else {
                    // Desktop: Push effect - push the entire wrapper and header
                    if (currentAppWrapper) currentAppWrapper.style.marginLeft = '288px'; // w-72 = 288px
                    if (currentHeader) currentHeader.style.left = '288px'; // Push header too
                }
            } else {
                // Close sidebar
                currentSidebar.classList.add('-translate-x-full');
                
                // Show header hamburger back after sidebar closes
                setTimeout(() => {
                    if (currentHamburgerButton) {
                        currentHamburgerButton.style.visibility = 'visible';
                        currentHamburgerButton.style.opacity = '1';
                    }
                }, 300); // After sidebar animation completes
                
                // Hide backdrop
                if (currentSidebarBackdrop) currentSidebarBackdrop.classList.add('hidden');
                
                // Reset push effect
                if (currentAppWrapper) currentAppWrapper.style.marginLeft = '0';
                if (currentHeader) currentHeader.style.left = '0'; // Reset header position
            }
        }

        function toggleTheme() {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        }
        
        // Expose toggleTheme to global scope for onclick handlers
        window.toggleTheme = toggleTheme;

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

        // Event listeners using event delegation for wire:navigate compatibility
        document.body.addEventListener('click', function(e) {
            // Hamburger menu button (in header)
            const hamburgerBtn = e.target.closest('#hamburger-menu');
            if (hamburgerBtn) {
                e.stopPropagation();
                toggleSidebar();
                return;
            }
            
            // Hamburger button in sidebar
            const hamburgerSidebarBtn = e.target.closest('#hamburger-menu-sidebar');
            if (hamburgerSidebarBtn) {
                e.stopPropagation();
                toggleSidebar();
                return;
            }
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
    })(); // Close IIFE
    </script>

@else
    <!-- GUEST (LOGIN) -->
    <div class="min-h-screen flex">
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
// Back to Top Button Functionality - Wrapped in IIFE to prevent re-declaration on wire:navigate
(function() {
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
})();
</script>

<!-- Livewire Navigate Loading Bar Script -->
<script>
    document.addEventListener('livewire:navigate', () => {
        const loadingBar = document.getElementById('navigate-loading-bar');
        if (loadingBar) {
            loadingBar.style.display = 'block';
            loadingBar.style.width = '0%';
            
            // Animate to 70% quickly
            setTimeout(() => loadingBar.style.width = '30%', 50);
            setTimeout(() => loadingBar.style.width = '60%', 150);
            setTimeout(() => loadingBar.style.width = '80%', 300);
        }
    });

    document.addEventListener('livewire:navigated', () => {
        const loadingBar = document.getElementById('navigate-loading-bar');
        if (loadingBar) {
            loadingBar.style.width = '100%';
            setTimeout(() => {
                loadingBar.style.display = 'none';
                loadingBar.style.width = '0%';
            }, 200);
        }
        
        // Re-apply theme after navigation to fix dark mode reset
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Re-initialize theme toggle icons if they exist
        if (typeof updateThemeIcons === 'function') {
            updateThemeIcons();
        }
    });
</script>

@auth
<!-- Auto Logout after Inactivity -->
<script>
(function() {
    // CONFIGURATION
    const INACTIVITY_TIMEOUT = 10 * 60 * 1000; // 10 menit
    const WARNING_SECONDS = 15; // Warning 15 detik sebelum logout
    
    let logoutTimer = null;
    let countdownInterval = null;
    let warningModal = null;
    let isLoggingOut = false;
    
    // Hitung kapan warning muncul
    const WARNING_TIME = INACTIVITY_TIMEOUT - (WARNING_SECONDS * 1000);
    
    // Stop semua timer
    function stopAllTimers() {
        if (logoutTimer) {
            clearTimeout(logoutTimer);
            logoutTimer = null;
        }
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
    }
    
    // Reset dan mulai timer baru
    function resetTimer() {
        if (isLoggingOut) return; // Jangan reset jika sedang logout
        
        stopAllTimers();
        hideWarning();
        
        // Set timer untuk menampilkan warning
        logoutTimer = setTimeout(showWarningAndStartCountdown, WARNING_TIME);
    }
    
    // Tampilkan warning dan mulai countdown
    function showWarningAndStartCountdown() {
        if (isLoggingOut) return;
        
        let secondsRemaining = WARNING_SECONDS;
        
        // Buat modal jika belum ada
        if (!warningModal) {
            warningModal = document.createElement('div');
            warningModal.id = 'inactivity-warning-modal';
            document.body.appendChild(warningModal);
        }
        
        // Render modal content
        warningModal.innerHTML = `
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[99999] flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-sm w-full p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sesi Akan Berakhir</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Anda akan dikeluarkan dalam <span id="logout-countdown" class="font-bold text-amber-600 dark:text-amber-400">${secondsRemaining}</span> detik.
                    </p>
                    <button id="stay-logged-in-btn" class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                        Tetap Login
                    </button>
                </div>
            </div>
        `;
        warningModal.style.display = 'block';
        warningModal.style.position = 'relative';
        warningModal.style.zIndex = '2147483647';
        
        // Attach button handler
        document.getElementById('stay-logged-in-btn').addEventListener('click', function() {
            resetTimer();
        });
        
        // Countdown interval
        countdownInterval = setInterval(function() {
            secondsRemaining--;
            const countdownEl = document.getElementById('logout-countdown');
            if (countdownEl) {
                countdownEl.textContent = secondsRemaining;
            }
            
            // Waktu habis - logout
            if (secondsRemaining <= 0) {
                stopAllTimers();
                performLogout();
            }
        }, 1000);
    }
    
    // Sembunyikan warning modal
    function hideWarning() {
        if (warningModal) {
            warningModal.style.display = 'none';
        }
    }
    
    // Proses logout
    function performLogout() {
        if (isLoggingOut) return; // Cegah double logout
        isLoggingOut = true;
        
        stopAllTimers();
        hideWarning();
        
        // Submit logout form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("logout") }}';
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
    
    // Track aktivitas user
    const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
    activityEvents.forEach(function(eventName) {
        document.addEventListener(eventName, resetTimer, { passive: true });
    });
    
    // Mulai timer saat load
    resetTimer();
    
    // Re-init saat Livewire navigate
    document.addEventListener('livewire:navigated', function() {
        isLoggingOut = false;
        resetTimer();
    });
    
    // Cleanup saat halaman unload
    window.addEventListener('beforeunload', stopAllTimers);
})();
</script>
@endauth

{{-- Guru Help Modals - Available on all pages --}}
@auth
@if(Auth::user()->isGuru())
<!-- Tips Modal (For Guru) -->
<div id="tipsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[75] items-center justify-center p-4" style="display: none;" onclick="closeTipsModal()">
    <div id="tipsModalContent" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md max-h-[85vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" onclick="event.stopPropagation()">
        <!-- Modal Header with Subtitle -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-700 dark:to-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Tips & Informasi</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Panduan cepat supervisi</p>
                </div>
            </div>
            <button onclick="closeTipsModal()" class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <!-- Modal Content - Mobile Usage Tips -->
        <div class="p-4 overflow-y-auto max-h-[calc(85vh-80px)]">
            <div class="space-y-3">
                <!-- Tip 1: Quick Navigation -->
                <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4 border-l-4 border-blue-500">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-blue-600 dark:bg-blue-500 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-blue-900 dark:text-blue-300 mb-1">Lihat Panduan</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Tap menu <strong>"Bantuan"</strong> di bawah, lalu pilih <strong>"Panduan"</strong> untuk melihat langkah lengkap supervisi</p>
                        </div>
                    </div>
                </div>
                <!-- Tip 2: Lacak Status -->
                <div class="bg-emerald-50 dark:bg-emerald-900/30 rounded-xl p-4 border-l-4 border-emerald-500">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-emerald-600 dark:bg-emerald-500 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-emerald-900 dark:text-emerald-300 mb-1">Lacak Status</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Lihat badge status supervisi: Draft, Disubmit, Direview, atau Selesai</p>
                        </div>
                    </div>
                </div>
                <!-- Tip 3: Kolaborasi -->
                <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-4 border-l-4 border-purple-500">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-purple-600 dark:bg-purple-500 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-purple-900 dark:text-purple-300 mb-1">Kolaborasi</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Tap accordion <strong>"Komentar"</strong> di kartu supervisi untuk melihat feedback dari Kepala Sekolah</p>
                        </div>
                    </div>
                </div>
            </div>
            <button onclick="closeTipsModal()" class="w-full mt-4 px-4 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>


<!-- Fitur Penting Modal (For Guru) -->
<div id="fiturPentingModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[75] items-center justify-center p-4" style="display: none;" onclick="closeFiturPentingModal()">
    <div id="fiturPentingModalContent" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md max-h-[85vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" onclick="event.stopPropagation()">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Fitur Penting</h3>
            </div>
            <button onclick="closeFiturPentingModal()" class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-3 overflow-y-auto max-h-[calc(85vh-60px)]">
            <div class="space-y-2">
                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-3 border border-amber-200 dark:border-amber-800">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-7 h-7 bg-amber-600 dark:bg-amber-500 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Kelola Draft & Revisi</h4>
                    </div>
                    <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1 ml-1">
                        <li>• <strong>Lanjutkan Draft:</strong> Klik tombol biru</li>
                        <li>• <strong>Revisi:</strong> Klik tombol oranye</li>
                        <li>• <strong>Auto-Save:</strong> Tersimpan tiap 30 detik</li>
                    </ul>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-3 border border-indigo-200 dark:border-indigo-800">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-7 h-7 bg-indigo-600 dark:bg-indigo-500 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Lihat & Beri Komentar</h4>
                    </div>
                    <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1 ml-1">
                        <li>• <strong>Komentar:</strong> Klik badge komentar</li>
                        <li>• <strong>Feedback:</strong> Lihat catatan Kepala Sekolah</li>
                        <li>• <strong>Diskusi:</strong> Balas komentar secara langsung</li>
                    </ul>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 border border-green-200 dark:border-green-800">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-7 h-7 bg-green-600 dark:bg-green-500 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Kolaborasi Guru</h4>
                    </div>
                    <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1 ml-1">
                        <li>• <strong>Lihat Supervisi:</strong> Klik tombol abu "Lihat"</li>
                        <li>• <strong>Dokumen:</strong> Lihat 7 dokumen evaluasi</li>
                        <li>• <strong>Video:</strong> Akses video pembelajaran</li>
                    </ul>
                </div>
            </div>
            <button onclick="closeFiturPentingModal()" class="w-full mt-3 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-colors text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Panduan Modal (For Guru) -->
<div id="guideModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[75] items-center justify-center p-4" style="display: none;" onclick="closeGuideModal()">
    <div id="guideModalContent" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg max-h-[80vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" onclick="event.stopPropagation()">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-700 dark:to-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-r from-amber-600 to-orange-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Panduan Supervisi</h3>
            </div>
            <button onclick="closeGuideModal()" class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-3 overflow-y-auto max-h-[calc(80vh-60px)]">
            <div class="space-y-2.5">
                <!-- LANGKAH 1 -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border-l-4 border-blue-500">
                    <span class="inline-block px-2 py-0.5 bg-blue-600 text-white text-[10px] font-bold rounded-full mb-1">LANGKAH 1</span>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Buat Supervisi Baru</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tap menu <strong>"Home"</strong>, lalu tap tombol <strong>"Mulai Supervisi"</strong> dan isi tanggal.</p>
                </div>

                <!-- LANGKAH 2 -->
                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-3 border-l-4 border-purple-500">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-block px-2 py-0.5 bg-purple-600 text-white text-[10px] font-bold rounded-full">LANGKAH 2</span>
                        <span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 text-[10px] font-bold rounded">WAJIB</span>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Upload 7 Dokumen</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tap <strong>"Lanjutkan"</strong> di kartu supervisi, lalu upload dokumen satu per satu.</p>
                </div>

                <!-- LANGKAH 3 -->
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 border-l-4 border-green-500">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-block px-2 py-0.5 bg-green-600 text-white text-[10px] font-bold rounded-full">LANGKAH 3</span>
                        <span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 text-[10px] font-bold rounded">WAJIB</span>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Isi Proses Pembelajaran</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tap tab <strong>"Proses"</strong>, masukkan link video dan jawab 5 refleksi.</p>
                </div>

                <!-- LANGKAH 4 -->
                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-3 border-l-4 border-amber-500">
                    <span class="inline-block px-2 py-0.5 bg-amber-600 text-white text-[10px] font-bold rounded-full mb-1">LANGKAH 4</span>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Submit Supervisi</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tap tombol <strong>"Submit"</strong> untuk kirim ke Kepala Sekolah.</p>
                </div>

                <!-- LANGKAH 5 -->
                <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-3 border-l-4 border-indigo-500">
                    <span class="inline-block px-2 py-0.5 bg-indigo-600 text-white text-[10px] font-bold rounded-full mb-1">LANGKAH 5</span>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Tunggu Review</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Cek status di kartu supervisi. Tap <strong>"Komentar"</strong> untuk melihat feedback.</p>
                </div>
            </div>
            <button onclick="closeGuideModal()" class="w-full mt-3 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-colors text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
// Guru Help Modal Functions
function toggleTipsFromNav() {
    openTipsModal();
}

function openTipsModal() {
    const modal = document.getElementById('tipsModal');
    const content = document.getElementById('tipsModalContent');
    if (modal && content) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
}

function closeTipsModal() {
    const modal = document.getElementById('tipsModal');
    const content = document.getElementById('tipsModalContent');
    if (modal && content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }
}

function openFiturPentingModal() {
    const modal = document.getElementById('fiturPentingModal');
    const content = document.getElementById('fiturPentingModalContent');
    if (modal && content) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
}

function closeFiturPentingModal() {
    const modal = document.getElementById('fiturPentingModal');
    const content = document.getElementById('fiturPentingModalContent');
    if (modal && content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }
}

function openGuideModal() {
    const modal = document.getElementById('guideModal');
    const content = document.getElementById('guideModalContent');
    if (modal && content) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
}

function closeGuideModal() {
    const modal = document.getElementById('guideModal');
    const content = document.getElementById('guideModalContent');
    if (modal && content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }
}
</script>
@endif
@endauth

@livewireScripts

</body>
</html>
