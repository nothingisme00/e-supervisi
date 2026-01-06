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

        /* Custom Dropdown Styles */
        .dropdown-menu-custom {
            display: none;
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dropdown-menu-custom.show {
            display: block;
            opacity: 1;
            transform: scale(1);
        }
        .dropdown-item.active {
            background-color: rgb(238 242 255);
            color: rgb(79 70 229);
        }
        .dark .dropdown-item.active {
            background-color: rgba(49, 46, 129, 0.3);
            color: rgb(129 140 248);
        }

        /* Guide Modal Styles - Seamless Single Card Transitions */
        #guideModalContent {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        #guideProgressBar {
            transition: width 0.6s cubic-bezier(0.65, 0, 0.35, 1);
        }
        
        #journeyStepsContainer {
            position: relative;
            min-height: 400px;
        }
        
        .step-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            width: 100%;
        }
        
        .step-content.active {
            position: relative;
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            z-index: 1;
        }
        
        @keyframes fadeInUp {
            from { 
                opacity: 0; 
                transform: translateY(15px);
            }
            to { 
                opacity: 1; 
                transform: translateY(0);
            }
        }

        .guide-card {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .step-content.active .guide-card {
            animation: fadeInUp 0.5s ease backwards;
        }
        
        .step-content.active .guide-card:nth-child(1) { animation-delay: 0.1s; }
        .step-content.active .guide-card:nth-child(2) { animation-delay: 0.2s; }
        .step-content.active .guide-card:nth-child(3) { animation-delay: 0.3s; }
        .step-content.active .guide-card:nth-child(4) { animation-delay: 0.4s; }
        .dark .guide-card {
            border-color: rgba(255,255,255,0.05);
        }

        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .animate-bounce-subtle {
            animation: bounce-subtle 2s infinite ease-in-out;
        }
        }
    </style>
    
    @livewireStyles
</head>
<body class="bg-gray-300 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-300" @auth data-role="{{ auth()->user()->role }}" @endauth>

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
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isGuru() ? route('guru.home') : (Auth::user()->isKepalaSekolah() ? route('kepala.dashboard') : url('/'))) }}" class="flex items-center gap-1.5 sm:gap-2 md:gap-2 lg:gap-2.5 hover:opacity-80 transition-opacity cursor-pointer group">
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
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isGuru() ? route('guru.home') : (Auth::user()->isKepalaSekolah() ? route('kepala.dashboard') : url('/'))) }}" 
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

{{-- Global Help Modals - Available on all pages --}}
@auth
<!-- Tips Modal (Role-Aware) -->
<div id="tipsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[75] flex items-end md:items-center justify-center p-0 md:p-4" style="display: none;" onclick="closeTipsModal()">
    <div id="tipsModalContent" class="bg-white dark:bg-gray-800 w-full md:max-w-md max-h-[85vh] overflow-hidden transform transition-all duration-300 rounded-t-3xl md:rounded-2xl shadow-2xl flex flex-col" onclick="event.stopPropagation()">
        
        <!-- Mobile Drag Handle -->
        <div class="md:hidden flex justify-center pt-3 pb-1">
            <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
        </div>

        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none shrink-0">
                    <span class="material-symbols-outlined text-white text-xl">lightbulb</span>
                </div>
                <div>
                    <h3 class="text-sm md:text-base font-bold text-gray-900 dark:text-white leading-tight">Tips & Informasi</h3>
                    <p class="text-[10px] text-blue-600 dark:text-blue-400 font-medium uppercase tracking-wider">Panduan Cepat</p>
                </div>
            </div>
            <button onclick="closeTipsModal()" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors text-gray-400">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
            <div class="space-y-4">
                @if(auth()->user()->role === 'admin')
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-4 border border-blue-100 dark:border-blue-800/50">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-blue-600">group</span>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Kelola User</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Gunakan menu <strong class="text-blue-600">"Users"</strong> untuk menambah, mengedit, atau menghapus akun pengguna.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-4 border border-emerald-100 dark:border-emerald-800/50">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-emerald-600">key</span>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Reset Password</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Anda dapat mereset password user yang lupa melalui tombol kunci di daftar user.</p>
                            </div>
                        </div>
                    </div>
                @elseif(auth()->user()->role === 'kepala_sekolah')
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-4 border border-blue-100 dark:border-blue-800/50">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-blue-600">assignment_turned_in</span>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Tinjau Evaluasi</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Klik menu <strong class="text-blue-600">"Evaluasi"</strong> untuk melihat daftar guru yang perlu dinilai.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-4 border border-emerald-100 dark:border-emerald-800/50">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-emerald-600">chat</span>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Berikan Feedback</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Gunakan kolom komentar untuk memberikan masukan yang membangun kepada guru.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-4 border border-blue-100 dark:border-blue-800/50">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-blue-600">help</span>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Lihat Panduan</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Tap menu <strong class="text-blue-600">"Bantuan"</strong> di bawah, lalu pilih <strong class="text-blue-600">"Panduan"</strong> untuk melihat langkah lengkap supervisi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-4 border border-emerald-100 dark:border-emerald-800/50">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-emerald-600">track_changes</span>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Lacak Status</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Lihat badge status supervisi: Draft, Disubmit, Direview, atau Selesai.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <button onclick="closeTipsModal()" class="w-full py-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-xl border border-gray-200 dark:border-gray-600 transition-all text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>
</div>


<!-- Fitur Penting Modal (For Guru) -->
<div id="fiturPentingModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[75] flex items-end md:items-center justify-center p-0 md:p-4" style="display: none;" onclick="closeFiturPentingModal()">
    <div id="fiturPentingModalContent" class="bg-white dark:bg-gray-800 w-full md:max-w-md max-h-[85vh] overflow-hidden transform transition-all duration-300 rounded-t-3xl md:rounded-2xl shadow-2xl flex flex-col" onclick="event.stopPropagation()">
        
        <!-- Mobile Drag Handle -->
        <div class="md:hidden flex justify-center pt-3 pb-1">
            <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
        </div>

        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none shrink-0">
                    <span class="material-symbols-outlined text-white text-xl">auto_awesome</span>
                </div>
                <div>
                    <h3 class="text-sm md:text-base font-bold text-gray-900 dark:text-white leading-tight">Fitur Penting</h3>
                    <p class="text-[10px] text-purple-600 dark:text-purple-400 font-medium uppercase tracking-wider">Optimalkan Penggunaan</p>
                </div>
            </div>
            <button onclick="closeFiturPentingModal()" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors text-gray-400">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
            <div class="space-y-4">
                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl p-4 border border-purple-100 dark:border-purple-800/50">
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-purple-600">save</span>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Auto-Save</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Sistem menyimpan progres Anda secara otomatis setiap 30 detik. Jangan takut kehilangan data.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl p-4 border border-indigo-100 dark:border-indigo-800/50">
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-indigo-600">history</span>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Riwayat Revisi</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Anda dapat melihat catatan revisi sebelumnya dari Kepala Sekolah untuk perbaikan yang lebih baik.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-2xl p-4 border border-amber-100 dark:border-amber-800/50">
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-amber-600">notifications_active</span>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Notifikasi Real-time</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Dapatkan pemberitahuan langsung saat status supervisi Anda berubah atau ada feedback baru.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <button onclick="closeFiturPentingModal()" class="w-full py-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-xl border border-gray-200 dark:border-gray-600 transition-all text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Guide Modal (Clean Professional Design) -->
<div id="guideModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-[75] flex items-center justify-center p-4 md:p-6" style="display: none;" onclick="closeGuideModal()">
    <div id="guideModalContent" class="bg-white dark:bg-gray-800 w-full max-w-[480px] overflow-hidden transform transition-all duration-500 rounded-[24px] shadow-2xl flex flex-col relative" onclick="event.stopPropagation()">
        
        <!-- Progress Bar (Top) -->
        <div class="h-1 bg-gray-200 dark:bg-gray-700">
            <div id="guideProgressBar" class="h-full transition-all duration-500 @if(auth()->user()->role === 'admin') bg-indigo-600 @elseif(auth()->user()->role === 'kepala_sekolah') bg-rose-500 @else bg-blue-600 @endif" style="width: 25%;"></div>
        </div>

        <!-- Header -->
        <div class="px-6 pt-6 pb-4">
            <div class="flex items-start justify-between gap-4 mb-2">
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-0.5">
                        @if(auth()->user()->role === 'admin')
                            Admin Guide
                        @elseif(auth()->user()->role === 'kepala_sekolah')
                            Kepala Sekolah Guide
                        @else
                            Guru Guide
                        @endif
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <span id="guideStepCounter" class="text-xs text-gray-500 dark:text-gray-400 font-medium">Step 1 of 4</span>
                    <button onclick="closeGuideModal()" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                        <span class="material-symbols-outlined text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 text-xl">close</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="px-6 pb-6 flex-1 overflow-y-auto custom-scrollbar overflow-x-hidden max-h-[65vh]">
            <div id="journeyStepsContainer" class="relative">
                @if(auth()->user()->role === 'admin')
                    <!-- ADMIN STEPS -->
                    <!-- Step 1: User Management -->
                    <div class="step-content active" data-step="1">
                        <div class="bg-indigo-50/50 dark:bg-indigo-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">manage_accounts</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Manajemen Pengguna</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Kelola seluruh akun guru dan kepala sekolah dalam sistem. Anda memiliki kontrol penuh untuk menambah, mengedit, atau menonaktifkan pengguna.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-sm">check</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Validasi NIK & Data Personal</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">NIK 16 digit wajib valid sesuai KTP. Lengkapi nama, email, dan nomor telepon aktif untuk notifikasi sistem.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-sm">check</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Atur Role & Hak Akses</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Pilih role: Admin (full access), Kepala Sekolah (review & approve), atau Guru (submit & view).</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-sm">check</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Reset Password & Recovery</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Fitur reset password tersedia jika user lupa. Password default dikirim otomatis via email.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg flex-shrink-0">info</span>
                            <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed">
                                <strong>Keamanan:</strong> Semua pengguna baru wajib mengganti password saat login pertama. Email verifikasi otomatis terkirim untuk aktivasi akun.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2: Monitoring Dashboard -->
                    <div class="step-content" data-step="2">
                        <div class="bg-purple-50/50 dark:bg-purple-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">monitoring</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Dashboard & Monitoring</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Pantau seluruh aktivitas supervisi dari satu layar. Lihat statistik real-time, status pengajuan, dan progres evaluasi semua guru.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 mb-5 guide-card">
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-200 dark:border-emerald-800">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-emerald-500 text-white mx-auto mb-2">
                                    <span class="material-symbols-outlined text-xl">task_alt</span>
                                </div>
                                <p class="text-xs font-bold text-gray-900 dark:text-white text-center">Total Supervisi</p>
                                <p class="text-[10px] text-gray-600 dark:text-gray-400 text-center mt-1">Completed & In Progress</p>
                            </div>
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-500 text-white mx-auto mb-2">
                                    <span class="material-symbols-outlined text-xl">groups</span>
                                </div>
                                <p class="text-xs font-bold text-gray-900 dark:text-white text-center">Active Users</p>
                                <p class="text-[10px] text-gray-600 dark:text-gray-400 text-center mt-1">Guru & Kepala Sekolah</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2 guide-card">
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-purple-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Completed</span>
                                </div>
                                <span class="text-xs text-gray-500">Supervisi selesai</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-purple-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">In Progress</span>
                                </div>
                                <span class="text-xs text-gray-500">Sedang direview</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-purple-300 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Revision</span>
                                </div>
                                <span class="text-xs text-gray-500">Perlu perbaikan</span>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-lg flex-shrink-0">insights</span>
                            <p class="text-xs text-purple-800 dark:text-purple-300 leading-relaxed">
                                <strong>Analytics:</strong> Grafik dan chart otomatis ter-generate untuk laporan bulanan dan tahunan kepada dinas pendidikan.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3: Carousel & Content Management -->
                    <div class="step-content" data-step="3">
                        <div class="bg-teal-50/50 dark:bg-teal-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">image</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Carousel & Konten</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Kelola banner dan slide carousel di halaman login. Tampilkan informasi penting, pencapaian sekolah, atau motivasi untuk guru.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-teal-300 dark:hover:border-teal-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-teal-600 dark:text-teal-400 text-sm">add_photo_alternate</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Upload Gambar Banner</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Format JPG/PNG, maksimal 2MB. Resolusi optimal 1920x1080px untuk tampilan terbaik.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-teal-300 dark:hover:border-teal-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-teal-600 dark:text-teal-400 text-sm">sort</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Atur Urutan Slide</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Drag & drop untuk mengubah urutan tampilan. Slide pertama akan menjadi highlight utama.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-teal-300 dark:hover:border-teal-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-teal-600 dark:text-teal-400 text-sm">toggle_on</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Aktif/Nonaktifkan Slide</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Toggle untuk menampilkan/menyembunyikan slide tertentu tanpa menghapus permanent.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-teal-50 dark:bg-teal-900/20 rounded-xl border border-teal-200 dark:border-teal-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-teal-600 dark:text-teal-400 text-lg flex-shrink-0">auto_awesome</span>
                            <p class="text-xs text-teal-800 dark:text-teal-300 leading-relaxed">
                                <strong>Tips:</strong> Gunakan gambar berkualitas tinggi dengan pesan motivasi atau pencapaian sekolah untuk meningkatkan engagement user.
                            </p>
                        </div>
                    </div>
                            <div class="guide-card bg-amber-50/50 dark:bg-amber-900/10 p-6 border-amber-100 dark:border-amber-900/30 text-center">
                                <div class="w-20 h-20 bg-amber-500 rounded-[2rem] flex items-center justify-center shadow-xl shadow-amber-200 dark:shadow-none mx-auto mb-6">
                                    <span class="material-symbols-outlined text-white text-4xl">monitoring</span>
                                </div>
                                <h4 class="text-2xl font-black text-gray-900 dark:text-white mb-3">Monitoring Real-time</h4>
                                <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm">
                                    Pantau statistik pengajuan secara live. Lihat siapa yang perlu diingatkan atau diprioritaskan.
                                </p>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-900 text-white flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
                                    <span class="text-xs font-bold uppercase">Live Statistics</span>
                                </div>
                                <span class="material-symbols-outlined text-amber-500">troubleshoot</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Reports & Analytics -->
                    <div class="step-content" data-step="4">
                        <div class="bg-amber-50/50 dark:bg-amber-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">analytics</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Laporan & Analitik</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Generate laporan komprehensif untuk keperluan administratif, monitoring kinerja, dan pelaporan ke dinas pendidikan.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-amber-300 dark:hover:border-amber-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-sm">table_chart</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Export Excel/CSV</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Download data supervisi lengkap dalam format spreadsheet untuk analisis lanjutan atau arsip.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-amber-300 dark:hover:border-amber-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-sm">picture_as_pdf</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Cetak PDF Official</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Laporan PDF dengan kop sekolah, tanda tangan digital, dan format resmi untuk dokumentasi.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-amber-300 dark:hover:border-amber-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-sm">query_stats</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Dashboard Analytics</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Grafik performa guru, tren kualitas pembelajaran, dan statistik periode untuk evaluasi berkala.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg flex-shrink-0">verified</span>
                            <p class="text-xs text-green-800 dark:text-green-300 leading-relaxed">
                                <strong>Otomatis:</strong> Sistem akan auto-generate grafik dan statistik setiap akhir bulan untuk memudahkan monitoring berkelanjutan.
                            </p>
                        </div>
                    </div>

                @elseif(auth()->user()->role === 'kepala_sekolah')
                    <!-- KEPALA SEKOLAH STEPS -->
                    <!-- Step 1: Review Schedule -->
                    <div class="step-content active" data-step="1">
                        <div class="bg-rose-50/50 dark:bg-rose-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-rose-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">event_note</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Review Jadwal Pengajuan</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Tinjau dan validasi jadwal supervisi yang diajukan guru. Pastikan tidak bentrok dengan agenda sekolah dan sesuai dengan kalender akademik.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-rose-300 dark:hover:border-rose-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-sm">check_circle</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Approve Jadwal</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Setujui jadwal yang sesuai. Guru akan menerima notifikasi konfirmasi dan dapat melanjutkan persiapan.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-rose-300 dark:hover:border-rose-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-sm">schedule</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Reschedule</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Ajukan waktu alternatif jika jadwal usulan bentrok dengan agenda penting sekolah.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-rose-300 dark:hover:border-rose-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-sm">event_busy</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Reject dengan Alasan</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Berikan penjelasan jelas jika pengajuan ditolak, sertakan saran waktu yang lebih tepat.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-rose-50 dark:bg-rose-900/20 rounded-xl border border-rose-200 dark:border-rose-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-lg flex-shrink-0">alarm</span>
                            <p class="text-xs text-rose-800 dark:text-rose-300 leading-relaxed">
                                <strong>Responsif:</strong> Usahakan merespons pengajuan dalam 1x24 jam agar guru punya waktu memadai untuk persiapan mengajar.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2: Evaluasi Dokumen -->
                    <div class="step-content" data-step="2">
                        <div class="bg-orange-50/50 dark:bg-orange-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">fact_check</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Review Perangkat Pembelajaran</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Periksa kelengkapan dan kualitas RPP, media pembelajaran, dan instrumen evaluasi yang diupload guru sebelum observasi kelas.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-orange-300 dark:hover:border-orange-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-sm">description</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Cek Kelengkapan RPP</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Verifikasi komponen: KD, indikator, tujuan, materi, metode, langkah, dan penilaian sesuai standar.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-orange-300 dark:hover:border-orange-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-sm">rate_review</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Berikan Feedback Konstruktif</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Saran perbaikan spesifik dan actionable jika ada komponen yang perlu ditingkatkan kualitasnya.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-orange-300 dark:hover:border-orange-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-sm">task_alt</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Approve atau Revise</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Setujui jika sudah memenuhi standar, atau minta revisi dengan catatan detail untuk perbaikan.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-lg flex-shrink-0">lightbulb</span>
                            <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                                <strong>Tips:</strong> Gunakan fitur komentar inline untuk feedback yang lebih spesifik pada bagian tertentu dari dokumen.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3: Observasi Langsung -->
                    <div class="step-content" data-step="3">
                        <div class="bg-teal-50/50 dark:bg-teal-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">visibility</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Observasi Pembelajaran</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Lakukan pengamatan langsung di kelas menggunakan instrumen supervisi digital. Fokus pada kualitas pembelajaran, interaksi, dan pengelolaan kelas.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-teal-300 dark:hover:border-teal-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-teal-600 dark:text-teal-400 text-sm">checklist</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Isi Instrumen Digital</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Gunakan rubrik yang tersedia: pembukaan, inti, penutup, pengelolaan waktu, dan interaksi siswa.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-teal-300 dark:hover:border-teal-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-teal-600 dark:text-teal-400 text-sm">photo_camera</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Dokumentasi Visual</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Upload foto suasana kelas, karya siswa, atau media yang digunakan sebagai bukti observasi.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-teal-300 dark:hover:border-teal-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-teal-600 dark:text-teal-400 text-sm">edit_note</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Catatan Lapangan</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Tulis observasi spesifik: momen menarik, kendala yang dihadapi guru, atau inovasi metode mengajar.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-teal-50 dark:bg-teal-900/20 rounded-xl border border-teal-200 dark:border-teal-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-teal-600 dark:text-teal-400 text-lg flex-shrink-0">timer</span>
                            <p class="text-xs text-teal-800 dark:text-teal-300 leading-relaxed">
                                <strong>Objektif:</strong> Observasi dilakukan minimal 1 jam pelajaran penuh untuk mendapat gambaran utuh proses pembelajaran.
                            </p>
                        </div>
                    </div>

                    <!-- Step 4: Rekap & Feedback -->
                    <div class="step-content" data-step="4">
                        <div class="bg-purple-50/50 dark:bg-purple-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">rate_review</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Finalisasi & Tindak Lanjut</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Berikan penilaian akhir, feedback konstruktif, dan rekomendasi pengembangan profesional untuk peningkatan kualitas guru.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-sm">stars</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Input Skor Final</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Sistem auto-kalkulasi dari instrumen observasi. Review dan konfirmasi hasil penilaian total.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-sm">psychology</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Feedback Pengembangan</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Berikan saran konkret untuk peningkatan: metode, media, pengelolaan kelas, atau strategi asesmen.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-sm">rocket_launch</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Rencana Tindak Lanjut</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Rekomendasikan pelatihan, mentoring, atau workshop yang sesuai dengan kebutuhan pengembangan guru.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl border border-purple-200 dark:border-purple-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-lg flex-shrink-0">workspace_premium</span>
                            <p class="text-xs text-purple-800 dark:text-purple-300 leading-relaxed">
                                <strong>Apresiasi:</strong> Jangan lupa berikan apresiasi untuk aspek positif yang telah dilakukan guru dengan baik.
                            </p>
                        </div>
                    </div>

                @else
                    <!-- GURU STEPS -->
                    <!-- Step 1: Scheduling -->
                    <div class="step-content active" data-step="1">
                        <div class="bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">edit_calendar</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Ajukan Jadwal Supervisi</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Tentukan tanggal dan waktu supervisi yang sesuai dengan jadwal mengajar Anda. Koordinasi dengan kepala sekolah untuk memastikan ketersediaan.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">event</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Pilih Tanggal & Waktu</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Minimal H-3 sebelum pelaksanaan. Pilih slot waktu yang tidak bentrok dengan agenda sekolah lain.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">description</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Siapkan Dokumen Awal</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Pastikan RPP/Modul Ajar sudah dalam bentuk draft final untuk direview kepala sekolah.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">notes</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Isi Catatan Tambahan</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Jelaskan materi yang akan diajarkan dan target pembelajaran yang ingin dicapai.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-lg flex-shrink-0">schedule</span>
                            <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                                <strong>Waktu Ideal:</strong> Ajukan supervisi minimal 3 hari kerja sebelumnya agar kepala sekolah memiliki waktu cukup untuk persiapan dan review dokumen.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2: Upload Files -->
                    <div class="step-content" data-step="2">
                        <div class="bg-indigo-50/50 dark:bg-indigo-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">upload_file</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Lengkapi Dokumen</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Unggah perangkat pembelajaran sebagai bahan evaluasi. Dokumen lengkap mempercepat proses review dan approval dari kepala sekolah.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-sm">description</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">RPP/Modul Ajar (Wajib)</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Format PDF/DOCX max 10MB. Pastikan sesuai format K13 atau Kurikulum Merdeka yang berlaku.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-sm">video_library</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Media Pembelajaran (Opsional)</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">PPT, video, atau link Google Drive ke materi pendukung yang akan digunakan saat mengajar.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-sm">assignment</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Instrumen Penilaian</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Rubrik penilaian, kisi-kisi soal, atau lembar observasi yang akan digunakan untuk evaluasi siswa.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg flex-shrink-0">cloud_upload</span>
                            <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed">
                                <strong>Upload:</strong> Sistem mendukung drag & drop. File otomatis tersimpan dan bisa direvisi sebelum pengajuan final.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3: Monitor Status -->
                    <div class="step-content" data-step="3">
                        <div class="bg-amber-50/50 dark:bg-amber-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">notifications_active</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Pantau Status</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Cek berkala status pengajuan Anda. Sistem akan mengirim notifikasi otomatis setiap ada perubahan atau feedback dari kepala sekolah.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-amber-300 dark:hover:border-amber-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">pending</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Status: Submitted</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Pengajuan terkirim dan menunggu review dari kepala sekolah. Biasanya 1-2 hari kerja.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-amber-300 dark:hover:border-amber-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-sm">edit_note</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Status: Revision Requested</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Ada catatan perbaikan. Baca feedback di kolom komentar, update dokumen, lalu ajukan ulang.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-amber-300 dark:hover:border-amber-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-sm">check_circle</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Status: Approved</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Jadwal disetujui! Supervisi akan dilaksanakan sesuai waktu yang telah ditentukan.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-lg flex-shrink-0">mark_email_read</span>
                            <p class="text-xs text-purple-800 dark:text-purple-300 leading-relaxed">
                                <strong>Notifikasi:</strong> Email dan notifikasi in-app akan otomatis terkirim setiap ada update status atau feedback baru.
                            </p>
                        </div>
                    </div>

                    <!-- Step 4: Final Feedback -->
                    <div class="step-content" data-step="4">
                        <div class="bg-emerald-50/50 dark:bg-emerald-900/10 rounded-2xl p-6 mb-5 guide-card">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">assignment_turned_in</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Hasil & Evaluasi</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">
                                Akses hasil supervisi lengkap dengan skor, feedback konstruktif, dan saran pengembangan untuk meningkatkan kualitas pembelajaran.
                            </p>
                        </div>
                        
                        <div class="space-y-3 guide-card">
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">star</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Skor Penilaian</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Nilai berdasarkan instrumen supervisi: perencanaan, pelaksanaan, evaluasi, dan profesionalisme.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">comment</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Feedback Kepala Sekolah</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Saran perbaikan, kelebihan yang dipertahankan, dan area yang perlu dikembangkan.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">download</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Download Laporan</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Cetak laporan PDF dengan tanda tangan digital untuk portofolio atau keperluan sertifikasi.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800 flex items-start gap-2 guide-card">
                            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-lg flex-shrink-0">workspace_premium</span>
                            <p class="text-xs text-emerald-800 dark:text-emerald-300 leading-relaxed">
                                <strong>Sertifikat:</strong> Guru dengan performa konsisten akan mendapat sertifikat digital apresiasi di akhir semester.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-5 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between gap-3">
                <button id="prevStepBtn" onclick="prevStep()" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all disabled:opacity-0 disabled:invisible">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    <span>Back</span>
                </button>
                
                <button id="nextStepBtn" onclick="nextStep()" class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white @if(auth()->user()->role === 'admin') bg-indigo-600 hover:bg-indigo-700 @elseif(auth()->user()->role === 'kepala_sekolah') bg-rose-500 hover:bg-rose-600 @else bg-blue-600 hover:bg-blue-700 @endif transition-all shadow-sm">
                    <span id="nextStepText">Continue</span>
                    <span class="material-symbols-outlined text-lg" id="nextStepIcon">arrow_forward</span>
                </button>
            </div>
            
            <div class="text-center mt-4">
                <button onclick="closeGuideModal()" class="text-xs font-medium text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors uppercase tracking-wider">
                    Lewati Panduan
                </button>
            </div>
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
        
        // Reset initial state
        content.style.opacity = '0';
        if (window.innerWidth < 768) {
            content.style.transform = 'translateY(100%)';
        } else {
            content.style.transform = 'translateY(1rem) scale(0.95)';
        }
        
        // Animate in
        setTimeout(() => {
            content.style.opacity = '1';
            if (window.innerWidth < 768) {
                content.style.transform = 'translateY(0)';
            } else {
                content.style.transform = 'translateY(0) scale(1)';
            }
            // Initialize step display
            if (typeof updateStepDisplay === 'function') {
                updateStepDisplay();
            }
        }, 50);
    }
}

function closeGuideModal() {
    const modal = document.getElementById('guideModal');
    const content = document.getElementById('guideModalContent');
    
    if (modal && content) {
        // Animate out
        content.style.opacity = '0';
        if (window.innerWidth < 768) {
            content.style.transform = 'translateY(100%)';
        } else {
            content.style.transform = 'translateY(1rem) scale(0.95)';
        }
        
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            // Reset to step 1 for next open
            currentStep = 1;
            if (typeof updateStepDisplay === 'function') {
                updateStepDisplay();
            }
        }, 300);
    }
}

// Handle resize for guide modal
window.addEventListener('resize', () => {
    const modal = document.getElementById('guideModal');
    if (modal && modal.style.display === 'flex') {
        updateStepDisplay();
    }
});

// Step Navigation (Desktop Only)
let currentStep = 1;

function getTotalSteps() {
    const items = document.querySelectorAll('.step-content');
    return items.length;
}

function goToStep(step) {
    const totalSteps = getTotalSteps();
    if (step >= 1 && step <= totalSteps) {
        currentStep = step;
        updateStepDisplay();
    }
}

function nextStep() {
    const totalSteps = getTotalSteps();
    if (currentStep < totalSteps) {
        currentStep++;
        updateStepDisplay();
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepDisplay();
    }
}

function updateStepDisplay() {
    const totalSteps = getTotalSteps();
    
    // Update Progress Bar & Counter
    const progressBar = document.getElementById('guideProgressBar');
    const stepCounter = document.getElementById('guideStepCounter');
    if (progressBar) {
        progressBar.style.width = ((currentStep / totalSteps) * 100) + '%';
    }
    if (stepCounter) {
        stepCounter.textContent = `Step ${currentStep} of ${totalSteps}`;
    }
    
    // Handle step visibility with seamless single card transition
    document.querySelectorAll('.step-content').forEach(content => {
        const step = parseInt(content.dataset.step);
        if (step === currentStep) {
            content.classList.add('active');
        } else {
            content.classList.remove('active');
        }
    });
    
    // Update buttons
    const prevBtn = document.getElementById('prevStepBtn');
    const nextBtn = document.getElementById('nextStepBtn');
    const nextText = document.getElementById('nextStepText');
    const nextIcon = document.getElementById('nextStepIcon');
    
    if (prevBtn) {
        prevBtn.disabled = currentStep === 1;
        prevBtn.style.opacity = currentStep === 1 ? '0' : '1';
    }
    
    if (nextBtn && nextText) {
        if (currentStep === totalSteps) {
            nextText.textContent = 'Finish';
            if (nextIcon) nextIcon.textContent = 'check_circle';
            nextBtn.onclick = closeGuideModal;
        } else {
            nextText.textContent = 'Continue';
            if (nextIcon) nextIcon.textContent = 'arrow_forward';
            nextBtn.onclick = nextStep;
        }
    }
}

// Global Custom Dropdown Logic
document.addEventListener('DOMContentLoaded', function() {
    initCustomDropdowns();
});

// Re-init on Livewire navigate or load
document.addEventListener('livewire:navigated', () => {
    initCustomDropdowns();
});

function initCustomDropdowns() {
    const dropdownContainers = document.querySelectorAll('.custom-dropdown-container');
    
    dropdownContainers.forEach(container => {
        // Skip if already initialized
        if (container.dataset.initialized) return;
        container.dataset.initialized = 'true';

        const btn = container.querySelector('.dropdown-button');
        const menu = container.querySelector('.dropdown-menu-custom');
        const label = container.querySelector('.dropdown-label');
        const arrow = container.querySelector('.dropdown-arrow');
        const input = container.querySelector('input[type="hidden"]');
        const items = container.querySelectorAll('.dropdown-item');

        if (!btn || !menu) return;

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isShowing = menu.classList.contains('show');
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu-custom').forEach(m => {
                if (m !== menu) {
                    m.classList.remove('show');
                    const otherArrow = m.closest('.custom-dropdown-container')?.querySelector('.dropdown-arrow');
                    if (otherArrow) otherArrow.style.transform = 'rotate(0deg)';
                }
            });

            menu.classList.toggle('show');
            if (arrow) arrow.style.transform = isShowing ? 'rotate(0deg)' : 'rotate(180deg)';
        });

        items.forEach(item => {
            item.addEventListener('click', () => {
                const value = item.getAttribute('data-value');
                const content = item.innerHTML;
                
                if (input) {
                    input.value = value;
                    // Trigger change event for any listeners
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    // For Livewire
                    if (input.hasAttribute('wire:model')) {
                        const model = input.getAttribute('wire:model');
                        if (window.Livewire) {
                            const component = window.Livewire.find(input.closest('[wire\\:id]').getAttribute('wire:id'));
                            if (component) component.set(model, value);
                        }
                    }
                }

                if (label) {
                    // Extract text only, excluding material icons to avoid "person Guru" issue
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = content;
                    const icons = tempDiv.querySelectorAll('.material-symbols-outlined');
                    icons.forEach(icon => icon.remove());
                    
                    label.innerHTML = tempDiv.textContent.trim();
                    label.classList.remove('text-gray-400', 'dark:text-gray-500');
                }
                
                // Update active state
                items.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                
                menu.classList.remove('show');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            });
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.custom-dropdown-container')) {
            document.querySelectorAll('.dropdown-menu-custom').forEach(m => {
                m.classList.remove('show');
                const arrow = m.closest('.custom-dropdown-container')?.querySelector('.dropdown-arrow');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            });
        }
    });
}
</script>
@endauth

@livewireScripts

</body>
</html>
