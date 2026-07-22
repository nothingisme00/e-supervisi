@extends('layouts.auth')

@section('page-title', 'Login')

@push('styles')
<style>
    .carousel-container {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        border-radius: 0;
    }
    .carousel-inner {
        display: flex;
        width: 100%;
        height: 100%;
        transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);
        will-change: transform;
        backface-visibility: hidden;
        perspective: 1000px;
    }
    .carousel-slide {
        flex: 0 0 100%;
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .carousel-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    .carousel-nav-button {
        background-color: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(4px);
        color: rgba(255, 255, 255, 0.6);
        padding: 0;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        pointer-events: auto;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .carousel-nav-button:hover {
        background-color: rgba(255, 255, 255, 0.15);
        color: rgba(255, 255, 255, 0.9);
        border-color: rgba(255, 255, 255, 0.2);
    }
    .carousel-nav-button:focus-visible {
        outline: 2px solid rgba(255, 255, 255, 0.9);
        outline-offset: 2px;
    }
    .carousel-dots {
        display: flex;
        gap: 0.375rem;
        pointer-events: auto;
        background: transparent;
        padding: 0;
        border-radius: 0;
        backdrop-filter: none;
        border: none;
    }
    .carousel-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .carousel-dot:hover {
        background-color: rgba(255, 255, 255, 0.5);
    }
    .carousel-dot.active {
        background-color: rgba(255, 255, 255, 0.9);
        width: 18px;
        border-radius: 99px;
    }

    /* Dropdown role kustom (vanilla JS, bukan Alpine) — toggle tampil/sembunyi
       lewat kelas `.show` ber-!important karena hanya mengandalkan kelas
       Tailwind statis (hidden/opacity-0/scale-95) tidak cukup untuk dipaksa
       oleh JS tanpa reactive framework. */
    .dropdown-item.active {
        background-color: var(--color-primary-700);
        color: white !important;
    }
    .dropdown-item.active span {
        color: white !important;
    }
    .dropdown-menu-custom.show {
        display: block !important;
        opacity: 1 !important;
        transform: scale(1) !important;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@section('content')
<main class="hidden md:flex flex-1 relative bg-gray-100 dark:bg-gray-900 overflow-hidden h-screen">
    <!-- Carousel Slides Dynamic -->
    <div class="carousel-container absolute inset-0">
        <div class="carousel-inner">
            @if(isset($carouselSlides) && $carouselSlides->count() > 0)
                @foreach($carouselSlides as $index => $slide)
                <div class="carousel-slide">
                    @if($slide->image_path)
                        <img alt="{{ $slide->title }}" class="transform scale-105" src="{{ $slide->image_url }}" loading="lazy" decoding="async"/>
                    @else
                        <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                            <x-icon name="document" class="w-24 h-24 lg:w-32 lg:h-32 text-gray-600" />
                        </div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-transparent to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent"></div>

                    <div class="absolute bottom-0 w-full p-8 lg:p-12 xl:p-16 flex items-end justify-between z-10 pointer-events-none">
                        @if($slide->title || $slide->description)
                        <div class="space-y-4 lg:space-y-6 max-w-lg">
                            @if($slide->title)
                            <h2 class="text-2xl lg:text-3xl font-bold text-white leading-tight drop-shadow-sm">
                                {{ $slide->title }}
                            </h2>
                            @endif
                            @if($slide->description)
                            <p class="text-gray-300 text-sm lg:text-base xl:text-lg max-w-md leading-relaxed">
                                {{ $slide->description }}
                            </p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback Static Slide -->
                <div class="carousel-slide">
                    <img alt="Teachers collaborating on E-Supervisi" class="transform scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAGIzlDZr5Wfd2gYd6cD-0hcujbc1twwDB47vpHpt4n8A7FwHHFcPJKF_o0Ad0VpVYKKpuTFf5ozXdN1eEmuyME3UY40ZKqfHhab5SS5lr_NFrhpqgwcTQ7S_rha-KHxU2ybGQ_Y-Bui4ZLL46RIYhEQbbEVYEJhPmS4twltgQPPV-lV2jApRm7E5tcyDYeWxKLDeyh64ztLYENS8e5pRkMZnK92edrMF_73JW_LLzHo8lg4qVTOPh3SgwW7KiHSUP20dZ6ZTjkkafI" loading="lazy" decoding="async"/>
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-transparent to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent"></div>

                    <div class="absolute bottom-0 w-full p-8 lg:p-12 xl:p-16 flex items-end justify-between z-10">
                        <div class="space-y-4 lg:space-y-6 max-w-lg">
                            <div class="inline-flex px-3 lg:px-4 py-1 lg:py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] lg:text-xs font-medium tracking-wide uppercase">
                                Fitur Unggulan
                            </div>
                            <h2 class="text-2xl lg:text-3xl font-bold text-white leading-tight drop-shadow-sm">
                                Meningkatkan Kualitas <br/>
                                <span class="text-white">Pendidikan Digital</span>
                            </h2>
                            <p class="text-gray-300 text-sm lg:text-base xl:text-lg max-w-md leading-relaxed">
                                Platform terintegrasi untuk pemantauan, evaluasi, dan pengembangan profesional guru yang lebih efektif.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Dots Container (Center Bottom) -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
            <div class="carousel-dots"></div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="absolute bottom-8 lg:bottom-12 right-8 lg:right-12 flex items-center gap-3 lg:gap-5 z-20">
        <button aria-label="Slide sebelumnya" type="button" class="carousel-nav-button group w-11 h-11 lg:w-14 lg:h-14" id="prevSlide">
            <x-icon name="arrow-left" class="w-5 h-5 lg:w-7 lg:h-7 group-hover:-translate-x-0.5 transition-transform" />
        </button>
        <button aria-label="Slide berikutnya" type="button" class="carousel-nav-button group w-11 h-11 lg:w-14 lg:h-14" id="nextSlide">
            <x-icon name="arrow-left" class="w-5 h-5 lg:w-7 lg:h-7 rotate-180 group-hover:translate-x-0.5 transition-transform" />
        </button>
    </div>
</main>

<aside class="w-full md:w-[420px] lg:w-[480px] xl:w-[540px] 2xl:w-[600px] flex-shrink-0 flex flex-col h-screen bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 shadow-2xl z-20 overflow-hidden relative transition-colors duration-300">
    <div class="h-full overflow-y-auto scrollbar-hide">
      <div class="min-h-full flex flex-col justify-center px-6 py-4 sm:px-8 sm:py-5 lg:py-6 xl:px-10 max-w-md mx-auto w-full">
        <!-- Header Section -->
        <div class="mb-6 lg:mb-8 text-center">
            <!-- Logo: otomatis pakai file logo yayasan bila tersedia di public/images/,
                 jika belum ada jatuh ke ikon buku bawaan tanpa perlu ubah kode. -->
            @if (file_exists(public_path('images/logo-yayasan.png')))
                <img src="{{ asset('images/logo-yayasan.png') }}" alt="Logo {{ config('app.name') }}"
                     class="w-16 h-16 lg:w-20 lg:h-20 mx-auto mb-4 object-contain" />
            @else
                <div class="inline-flex items-center justify-center w-14 h-14 lg:w-16 lg:h-16 bg-primary-600 rounded-2xl mb-4 shadow-lg shadow-primary-500/25">
                    <x-icon name="book-open" class="w-7 h-7 lg:w-8 lg:h-8 text-white" />
                </div>
            @endif

            <!-- App Name -->
            <h1 class="text-2xl lg:text-3xl font-bold tracking-tight text-gray-900 dark:text-white mb-1">
                {{ config('app.name', 'Yayasan Az-Zahroh') }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-xs lg:text-sm">
                Sistem Supervisi Pembelajaran Terpadu
            </p>
        </div>

        <!-- Success Alert (mis. setelah logout) -->
        @if(session('success'))
        <div class="mb-3 lg:mb-4 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 p-2.5 lg:p-3 rounded-lg text-xs lg:text-sm border border-green-100 dark:border-green-800/50 flex items-center gap-2">
            <x-icon name="check-circle" class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0" />
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Warning Alert (mis. sesi berakhir karena idle) -->
        @if(session('warning'))
        <div class="mb-3 lg:mb-4 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 p-2.5 lg:p-3 rounded-lg text-xs lg:text-sm border border-amber-100 dark:border-amber-800/50 flex items-center gap-2">
            <x-icon name="exclamation-triangle" class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0" />
            <span>{{ session('warning') }}</span>
        </div>
        @endif

        <!-- Throttle Alert dengan countdown (tanpa halaman error terpisah) -->
        @if(session('throttle_seconds'))
        <div id="throttle-alert" data-throttle-seconds="{{ (int) session('throttle_seconds') }}"
             class="mb-3 lg:mb-4 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 p-2.5 lg:p-3 rounded-lg text-xs lg:text-sm border border-amber-100 dark:border-amber-800/50 flex items-center gap-2">
            <x-icon name="clock" class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0" />
            <span id="throttle-message">Terlalu banyak percobaan login. Coba lagi dalam <strong id="throttle-countdown" class="tabular-nums">{{ (int) session('throttle_seconds') }}</strong> detik.</span>
        </div>
        @elseif($errors->any())
        <!-- Error Alert -->
        <div class="mb-3 lg:mb-4 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-2.5 lg:p-3 rounded-lg text-xs lg:text-sm border border-red-100 dark:border-red-800/50 flex items-center gap-2">
            <x-icon name="exclamation-triangle" class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0" />
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form action="{{ route('login') }}" class="space-y-4 lg:space-y-5" method="POST" id="loginForm">
            @csrf

            <x-form.field label="NIK" name="nik" :error="''">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 lg:pl-4 flex items-center pointer-events-none">
                        <x-icon name="id-card" class="w-4 h-4 lg:w-5 lg:h-5 text-gray-400 dark:text-gray-500 group-focus-within:text-primary-600 dark:group-focus-within:text-primary-400 transition-colors" />
                    </div>
                    <input autocomplete="username"
                           class="form-control pl-10 lg:pl-12"
                           id="nik"
                           name="nik"
                           value="{{ old('nik') }}"
                           placeholder="16 digit NIK"
                           type="text"
                           required
                           maxlength="16"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)"/>
                </div>
            </x-form.field>

            <x-form.field label="Password" name="password" :error="''">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 lg:pl-4 flex items-center pointer-events-none">
                        <x-icon name="lock" class="w-4 h-4 lg:w-5 lg:h-5 text-gray-400 dark:text-gray-500 group-focus-within:text-primary-600 dark:group-focus-within:text-primary-400 transition-colors" />
                    </div>
                    <input autocomplete="current-password"
                           class="form-control pl-10 lg:pl-12 pr-12"
                           id="password"
                           name="password"
                           placeholder="Password"
                           type="password"
                           required/>
                    <button type="button" id="toggle-password-btn" aria-label="Tampilkan password" class="absolute inset-y-0 right-0 pr-3 lg:pr-4 flex items-center text-gray-400 dark:text-gray-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <x-icon name="eye" id="password-icon-show" class="w-4 h-4 lg:w-5 lg:h-5" />
                        <x-icon name="eye-slash" id="password-icon-hide" class="w-4 h-4 lg:w-5 lg:h-5 hidden" />
                    </button>
                </div>
            </x-form.field>

            <x-form.field label="Role" name="role" :error="''">
                <div class="relative" id="custom-dropdown">
                    <input type="hidden" name="role" id="role-input" value="{{ old('role') }}" required>
                    <div class="absolute inset-y-0 left-0 pl-3 lg:pl-4 flex items-center pointer-events-none z-10">
                        <x-icon name="users" class="w-4 h-4 lg:w-5 lg:h-5 text-gray-400 dark:text-gray-500" />
                    </div>

                    <button type="button"
                            id="dropdown-button"
                            class="relative w-full pl-10 lg:pl-12 pr-12 py-2.5 lg:py-3 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 transition-all duration-200 text-xs lg:text-sm font-medium hover:border-gray-300 dark:hover:border-gray-600 cursor-pointer">
                        <span id="dropdown-label" class="{{ old('role') ? '' : 'text-gray-400 dark:text-gray-500' }}">
                            {{ old('role') ? ucfirst(str_replace('_', ' ', old('role'))) : 'Pilih role' }}
                        </span>
                        <div class="absolute inset-y-0 right-0 pr-3 lg:pr-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                            <x-icon name="chevron-down" id="dropdown-arrow" class="w-4 h-4 lg:w-5 lg:h-5 transition-transform duration-200" />
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="dropdown-menu"
                         class="dropdown-menu-custom absolute top-full mt-1.5 left-0 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl z-50 hidden opacity-0 transform scale-95 transition-all duration-200 origin-top">
                        <div class="p-1 space-y-0.5">
                            <div class="dropdown-item px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 cursor-pointer transition-colors flex items-center gap-2 lg:gap-3" data-value="admin">
                                <x-icon name="clipboard-check" class="w-4 h-4 lg:w-5 lg:h-5" />
                                Admin
                            </div>
                            <div class="dropdown-item px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 cursor-pointer transition-colors flex items-center gap-2 lg:gap-3" data-value="kepala_sekolah">
                                <x-icon name="home" class="w-4 h-4 lg:w-5 lg:h-5" />
                                Kepala Sekolah
                            </div>
                            <div class="dropdown-item px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 cursor-pointer transition-colors flex items-center gap-2 lg:gap-3" data-value="guru">
                                <x-icon name="pencil" class="w-4 h-4 lg:w-5 lg:h-5" />
                                Guru
                            </div>
                        </div>
                    </div>
                </div>
            </x-form.field>

            <div class="pt-2 lg:pt-3">
                <button id="loginBtn" class="group relative w-full flex justify-center items-center py-2.5 lg:py-3 px-6 rounded-lg text-xs lg:text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 transition-colors duration-200 disabled:opacity-70 disabled:cursor-not-allowed shadow-lg shadow-primary-500/25 min-h-[44px]" type="submit">
                    <span id="loginBtnText">Masuk</span>
                    <svg id="loginSpinner" class="hidden ml-2 h-4 w-4 lg:h-5 lg:w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>

        <div class="mt-4 lg:mt-5 pt-3 lg:pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <button type="button" class="group flex items-center space-x-1.5 px-2.5 py-1.5 min-h-11 rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-600 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-950/30 transition-all duration-200 shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500" onclick="toggleTheme()">
                <x-icon name="sun" id="theme-icon-sun" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" />
                <x-icon name="moon" id="theme-icon-moon" class="w-4 h-4 hidden transition-transform duration-300 group-hover:rotate-180" />
                <span id="theme-text">Mode Terang</span>
            </button>
            @include('auth.partials.footer')
        </div>
      </div>
    </div>
</aside>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Bersihkan jejak idle-logout sesi sebelumnya (layouts/modern) —
        // jejak basi di localStorage bisa menendang login yang baru sah.
        localStorage.removeItem('lastActivityTime');
        localStorage.removeItem('sessionLogoutTime');

        // Countdown throttle login: tombol Masuk nonaktif sampai jeda habis.
        const throttleAlert = document.getElementById('throttle-alert');
        if (throttleAlert) {
            const loginBtn = document.getElementById('loginBtn');
            const countdownEl = document.getElementById('throttle-countdown');
            const messageEl = document.getElementById('throttle-message');
            let sisa = parseInt(throttleAlert.dataset.throttleSeconds, 10) || 0;

            if (loginBtn) loginBtn.disabled = true;

            const timer = setInterval(() => {
                sisa -= 1;
                if (sisa > 0) {
                    if (countdownEl) countdownEl.textContent = sisa;
                    return;
                }
                clearInterval(timer);
                if (messageEl) messageEl.textContent = 'Silakan coba login kembali.';
                throttleAlert.classList.remove('bg-amber-50', 'dark:bg-amber-900/20', 'text-amber-700', 'dark:text-amber-400', 'border-amber-100', 'dark:border-amber-800/50');
                throttleAlert.classList.add('bg-green-50', 'dark:bg-green-900/20', 'text-green-600', 'dark:text-green-400', 'border-green-100', 'dark:border-green-800/50');
                if (loginBtn) loginBtn.disabled = false;
            }, 1000);
        }

        // Theme Toggle (dipicu manual oleh tombol — theme-init.blade.php
        // sudah menyetel kelas `dark` di <html> sebelum body dirender).
        const themeText = document.getElementById('theme-text');
        const themeIconSun = document.getElementById('theme-icon-sun');
        const themeIconMoon = document.getElementById('theme-icon-moon');

        function syncThemeUi() {
            const isDark = document.documentElement.classList.contains('dark');
            if (themeText) themeText.textContent = isDark ? 'Mode Gelap' : 'Mode Terang';
            if (themeIconSun) themeIconSun.classList.toggle('hidden', isDark);
            if (themeIconMoon) themeIconMoon.classList.toggle('hidden', !isDark);
        }

        window.toggleTheme = function () {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            syncThemeUi();
        };

        syncThemeUi();

        // Carousel Logic
        const carouselInner = document.querySelector('.carousel-inner');
        const slides = document.querySelectorAll('.carousel-slide');
        const dotsContainer = document.querySelector('.carousel-dots');
        let currentSlide = 0;
        let slideInterval;

        function showSlide(index) {
            if (slides.length === 0) return;

            if (carouselInner) {
                carouselInner.style.transform = `translateX(-${index * 100}%)`;
            }

            slides.forEach((slide, i) => {
                if (dotsContainer && dotsContainer.children[i]) {
                    dotsContainer.children[i].classList.remove('active');
                }
            });

            if (dotsContainer && dotsContainer.children[index]) {
                dotsContainer.children[index].classList.add('active');
            }
            currentSlide = index;
        }

        function nextSlide() {
            if (slides.length === 0) return;
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        function prevSlide() {
            if (slides.length === 0) return;
            let prev = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prev);
        }

        // Build Dots
        if (dotsContainer && slides.length > 1 && dotsContainer.children.length === 0) {
            slides.forEach((_, i) => {
                const dot = document.createElement('div');
                dot.classList.add('carousel-dot');
                dot.addEventListener('click', () => {
                    showSlide(i);
                    resetInterval();
                });
                dotsContainer.appendChild(dot);
            });
        }

        // Hide controls if only one slide
        if (slides.length <= 1) {
            if (dotsContainer) dotsContainer.style.display = 'none';
            const navButtons = document.querySelectorAll('.carousel-nav-button');
            navButtons.forEach(btn => btn.style.display = 'none');
        }

        if (slides.length > 0) {
            showSlide(0);
            if (slides.length > 1) startInterval();
        }

        // Carousel Controls
        document.getElementById('prevSlide')?.addEventListener('click', () => {
            prevSlide();
            resetInterval();
        });
        document.getElementById('nextSlide')?.addEventListener('click', () => {
            nextSlide();
            resetInterval();
        });

        function startInterval() {
            if (slideInterval) clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }

        // Password Toggle
        const togglePasswordBtn = document.getElementById('toggle-password-btn');
        const passwordInput = document.getElementById('password');
        const passwordIconShow = document.getElementById('password-icon-show');
        const passwordIconHide = document.getElementById('password-icon-hide');
        if (togglePasswordBtn && passwordInput) {
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                const showing = type === 'text';
                if (passwordIconShow) passwordIconShow.classList.toggle('hidden', showing);
                if (passwordIconHide) passwordIconHide.classList.toggle('hidden', !showing);
                togglePasswordBtn.setAttribute('aria-label', showing ? 'Sembunyikan password' : 'Tampilkan password');
            });
        }

        // Custom Dropdown Logic
        const dropdownBtn = document.getElementById('dropdown-button');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const dropdownLabel = document.getElementById('dropdown-label');
        const dropdownArrow = document.getElementById('dropdown-arrow');
        const roleInput = document.getElementById('role-input');
        const dropdownItems = document.querySelectorAll('.dropdown-item');

        if (dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isShowing = dropdownMenu.classList.contains('show');

                if (isShowing) {
                    dropdownMenu.classList.remove('show');
                    dropdownArrow.style.transform = 'rotate(0deg)';
                } else {
                    dropdownMenu.classList.add('show');
                    dropdownArrow.style.transform = 'rotate(180deg)';
                }
            });

            dropdownItems.forEach(item => {
                item.addEventListener('click', () => {
                    const value = item.getAttribute('data-value');

                    roleInput.value = value;
                    dropdownLabel.textContent = item.textContent.trim();
                    dropdownLabel.classList.remove('text-gray-400', 'dark:text-gray-500');

                    dropdownItems.forEach(i => i.classList.remove('active'));
                    item.classList.add('active');

                    dropdownMenu.classList.remove('show');
                    dropdownArrow.style.transform = 'rotate(0deg)';
                });
            });

            // Close on click outside
            document.addEventListener('click', (e) => {
                if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                    dropdownArrow.style.transform = 'rotate(0deg)';
                }
            });
        }

        // Login form loading state
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const loginBtnText = document.getElementById('loginBtnText');
        const loginSpinner = document.getElementById('loginSpinner');

        if (loginForm && loginBtn) {
            loginForm.addEventListener('submit', function() {
                loginBtn.disabled = true;
                loginBtnText.textContent = 'Memproses...';
                loginSpinner.classList.remove('hidden');
            });
        }
    });
</script>
@endpush
