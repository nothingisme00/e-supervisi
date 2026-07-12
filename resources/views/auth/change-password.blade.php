@extends('layouts.auth')

@section('page-title', 'Ganti Password')

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
                    <img alt="Keamanan akun E-Supervisi" class="transform scale-105" src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" loading="lazy" decoding="async"/>
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-transparent to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent"></div>

                    <div class="absolute bottom-0 w-full p-8 lg:p-12 xl:p-16 flex items-end justify-between z-10">
                        <div class="space-y-4 lg:space-y-6 max-w-lg">
                            <div class="inline-flex px-3 lg:px-4 py-1 lg:py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] lg:text-xs font-medium tracking-wide uppercase">
                                Keamanan Akun
                            </div>
                            <h2 class="text-2xl lg:text-3xl font-bold text-white leading-tight drop-shadow-sm">
                                Lindungi Akun <br/>
                                <span class="text-white">Anda Sekarang</span>
                            </h2>
                            <p class="text-gray-300 text-sm lg:text-base xl:text-lg max-w-md leading-relaxed">
                                Mengganti password secara berkala adalah langkah penting untuk menjaga keamanan data dan privasi Anda.
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
    <!-- Top Actions -->
    <div class="absolute top-4 right-4 lg:top-6 lg:right-6 flex items-center gap-2 lg:gap-3 z-30">
        <button type="button" id="theme-toggle-btn" aria-label="Ganti mode tampilan" class="w-11 h-11 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-950/30 hover:text-primary-600 dark:hover:text-primary-400 transition-all shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
            <x-icon name="sun" id="theme-icon-sun" class="w-5 h-5" />
            <x-icon name="moon" id="theme-icon-moon" class="w-5 h-5 hidden" />
        </button>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center gap-2 px-3 lg:px-4 py-2 min-h-11 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all font-semibold text-sm shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                <x-icon name="arrow-left" class="w-4 h-4 rotate-180" />
                Keluar
            </button>
        </form>
    </div>

    <div class="h-full flex flex-col justify-center px-6 py-12 sm:px-10 lg:px-14 max-w-lg mx-auto w-full overflow-y-auto scrollbar-hide">
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-amber-500 rounded-2xl sm:rounded-2xl mb-6 shadow-xl shadow-amber-500/30">
                <x-icon name="key" class="w-8 h-8 sm:w-10 sm:h-10 text-white" />
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">
                Ganti Password
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                Anda harus mengganti password default untuk keamanan akun
            </p>
        </div>

        <!-- Alert Box -->
        <div class="mb-8 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 dark:border-amber-400 p-4 rounded-xl">
            <div class="flex gap-3">
                <x-icon name="exclamation-triangle" class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0" />
                <p class="text-sm text-amber-800 dark:text-amber-300 font-medium leading-relaxed">
                    Password default tidak aman. Silakan buat password baru yang kuat.
                </p>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-4 rounded-xl text-sm font-medium border border-red-200 dark:border-red-800">
            <div class="flex items-center gap-2 mb-2">
                <x-icon name="exclamation-triangle" class="w-5 h-5" />
                <span class="font-bold">Validasi Gagal!</span>
            </div>
            <ul class="list-disc list-inside space-y-1 ml-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('change-password.update') }}" class="space-y-6" method="POST">
            @csrf

            <!-- Password Baru -->
            <x-form.field label="Password Baru" name="password" :error="''">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <x-icon name="lock" class="w-5 h-5 text-gray-400 dark:text-gray-500 group-focus-within:text-primary-600 dark:group-focus-within:text-primary-400 transition-colors" />
                    </div>
                    <input class="form-control pl-12 pr-14 py-3.5 rounded-xl"
                           id="password"
                           name="password"
                           placeholder="Masukkan password baru"
                           type="password"
                           autocomplete="new-password"
                           required/>
                    <button type="button" id="toggle-password-btn" aria-label="Tampilkan password" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 dark:text-gray-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <x-icon name="eye" id="password-icon-show" class="w-5 h-5" />
                        <x-icon name="eye-slash" id="password-icon-hide" class="w-5 h-5 hidden" />
                    </button>
                </div>
            </x-form.field>

            <!-- Konfirmasi Password -->
            <x-form.field label="Konfirmasi Password" name="password_confirmation" :error="''">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <x-icon name="lock" class="w-5 h-5 text-gray-400 dark:text-gray-500 group-focus-within:text-primary-600 dark:group-focus-within:text-primary-400 transition-colors" />
                    </div>
                    <input class="form-control pl-12 pr-14 py-3.5 rounded-xl"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Ketik ulang password baru"
                           type="password"
                           autocomplete="new-password"
                           required/>
                    <button type="button" id="toggle-password-confirmation-btn" aria-label="Tampilkan password" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 dark:text-gray-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <x-icon name="eye" id="password-confirmation-icon-show" class="w-5 h-5" />
                        <x-icon name="eye-slash" id="password-confirmation-icon-hide" class="w-5 h-5 hidden" />
                    </button>
                </div>
            </x-form.field>

            <!-- Syarat Password Checklist -->
            <div class="p-5 bg-primary-50/50 dark:bg-primary-900/10 border border-primary-100 dark:border-primary-900/30 rounded-2xl space-y-3">
                <h3 class="text-sm font-bold text-primary-900 dark:text-primary-300 flex items-center gap-2">
                    <x-icon name="information-circle" class="w-5 h-5" />
                    Syarat Password
                </h3>
                <div class="space-y-2.5">
                    <div id="req-length" class="flex items-center gap-3 text-xs font-medium text-gray-400 dark:text-gray-500 transition-colors duration-300">
                        <x-icon name="x-mark" class="w-5 h-5 req-icon-unmet" />
                        <x-icon name="check-circle" class="w-5 h-5 req-icon-met hidden" />
                        <span>Minimal 8 karakter</span>
                    </div>
                    <div id="req-mixed" class="flex items-center gap-3 text-xs font-medium text-gray-400 dark:text-gray-500 transition-colors duration-300">
                        <x-icon name="x-mark" class="w-5 h-5 req-icon-unmet" />
                        <x-icon name="check-circle" class="w-5 h-5 req-icon-met hidden" />
                        <span>Huruf besar (A-Z) &amp; huruf kecil (a-z)</span>
                    </div>
                    <div id="req-number" class="flex items-center gap-3 text-xs font-medium text-gray-400 dark:text-gray-500 transition-colors duration-300">
                        <x-icon name="x-mark" class="w-5 h-5 req-icon-unmet" />
                        <x-icon name="check-circle" class="w-5 h-5 req-icon-met hidden" />
                        <span>Mengandung angka (0-9)</span>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <x-button type="submit" class="w-full py-4! shadow-lg shadow-primary-500/30">
                    <x-icon name="check" class="w-5 h-5" />
                    Simpan Password Baru
                </x-button>
                <p class="mt-4 text-center text-xs font-medium text-gray-500 dark:text-gray-500">
                    Setelah mengubah password, Anda akan diarahkan ke dashboard
                </p>
            </div>
        </form>

        <div class="mt-8 text-center">
            @include('auth.partials.footer')
        </div>
    </div>
</aside>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Theme Toggle (dipicu manual oleh tombol — theme-init.blade.php
        // sudah menyetel kelas `dark` di <html> sebelum body dirender).
        const themeIconSun = document.getElementById('theme-icon-sun');
        const themeIconMoon = document.getElementById('theme-icon-moon');

        function syncThemeUi() {
            const isDark = document.documentElement.classList.contains('dark');
            if (themeIconSun) themeIconSun.classList.toggle('hidden', isDark);
            if (themeIconMoon) themeIconMoon.classList.toggle('hidden', !isDark);
        }

        document.getElementById('theme-toggle-btn')?.addEventListener('click', () => {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            syncThemeUi();
        });

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
            showSlide((currentSlide + 1) % slides.length);
        }

        function prevSlide() {
            if (slides.length === 0) return;
            showSlide((currentSlide - 1 + slides.length) % slides.length);
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
            document.querySelectorAll('.carousel-nav-button').forEach(btn => btn.style.display = 'none');
        }

        if (slides.length > 0) {
            showSlide(0);
            if (slides.length > 1) startInterval();
        }

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

        // Password Toggle (dua field: password & password_confirmation)
        function wireTogglePassword(btnId, inputId, showIconId, hideIconId) {
            const btn = document.getElementById(btnId);
            const input = document.getElementById(inputId);
            const iconShow = document.getElementById(showIconId);
            const iconHide = document.getElementById(hideIconId);
            if (!btn || !input) return;

            btn.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                const showing = type === 'text';
                if (iconShow) iconShow.classList.toggle('hidden', showing);
                if (iconHide) iconHide.classList.toggle('hidden', !showing);
                btn.setAttribute('aria-label', showing ? 'Sembunyikan password' : 'Tampilkan password');
            });
        }

        wireTogglePassword('toggle-password-btn', 'password', 'password-icon-show', 'password-icon-hide');
        wireTogglePassword('toggle-password-confirmation-btn', 'password_confirmation', 'password-confirmation-icon-show', 'password-confirmation-icon-hide');

        // Password Requirements Validation
        const passwordInput = document.getElementById('password');
        const reqLength = document.getElementById('req-length');
        const reqMixed = document.getElementById('req-mixed');
        const reqNumber = document.getElementById('req-number');

        // Status terpenuhi/belum disampaikan lewat BENTUK ikon + warna
        // sekaligus (WCAG 1.4.1: warna tak boleh jadi satu-satunya penanda).
        function updateReq(el, valid) {
            if (!el) return;
            const met = el.querySelector('.req-icon-met');
            const unmet = el.querySelector('.req-icon-unmet');
            if (valid) {
                el.classList.remove('text-gray-400', 'dark:text-gray-500');
                el.classList.add('text-primary-600', 'dark:text-primary-400');
                met?.classList.remove('hidden');
                unmet?.classList.add('hidden');
            } else {
                el.classList.add('text-gray-400', 'dark:text-gray-500');
                el.classList.remove('text-primary-600', 'dark:text-primary-400');
                met?.classList.add('hidden');
                unmet?.classList.remove('hidden');
            }
        }

        passwordInput?.addEventListener('input', function() {
            const val = this.value;
            updateReq(reqLength, val.length >= 8);
            updateReq(reqMixed, /[a-z]/.test(val) && /[A-Z]/.test(val));
            updateReq(reqNumber, /[0-9]/.test(val));
        });
    });
</script>
@endpush
