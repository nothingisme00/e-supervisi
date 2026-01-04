<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ganti Password - {{ config('app.name', 'E-Supervisi') }}</title>
    
    <!-- User Provided Tailwind CDN and Config -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <style type="text/tailwindcss">
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
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            color: white;
            padding: 0;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            pointer-events: auto;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .carousel-nav-button:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.5);
        }
        .carousel-dots {
            display: flex;
            gap: 0.5rem;
            pointer-events: auto;
            background: rgba(0,0,0,0.25);
            padding: 0.5rem 0.875rem;
            border-radius: 99px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.15);
        }
        .carousel-dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .carousel-dot:hover {
            background-color: rgba(255, 255, 255, 0.7);
            transform: scale(1.2);
        }
        .carousel-dot.active {
            background-color: #ffffff;
            width: 1.5rem;
            border-radius: 99px;
        }
        
        /* Hide scrollbar */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;  /* Chrome, Safari and Opera */
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6366F1",
                        primaryHover: "#4F46E5",
                        "background-light": "#F8FAFC", 
                        "background-dark": "#0F172A", 
                        "card-light": "#FFFFFF",
                        "card-dark": "#1E293B",
                    },
                    fontFamily: {
                        sans: ["Inter", "sans-serif"],
                    },
                },
            },
        };

        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                if(themeIcon) themeIcon.textContent = 'light_mode';
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if(themeIcon) themeIcon.textContent = 'dark_mode';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Theme Initialization
            const themeIcon = document.getElementById('theme-icon');
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                if(themeIcon) themeIcon.textContent = 'dark_mode';
            } else {
                document.documentElement.classList.remove('dark');
                if(themeIcon) themeIcon.textContent = 'light_mode';
            }

            // Carousel Logic
            const carouselInner = document.querySelector('.carousel-inner');
            const slides = document.querySelectorAll('.carousel-slide');
            const dotsContainer = document.querySelector('.carousel-dots');
            let currentSlide = 0;
            let slideInterval;

            function showSlide(index) {
                if (slides.length === 0) return;
                if (carouselInner) carouselInner.style.transform = `translateX(-${index * 100}%)`;
                slides.forEach((slide, i) => {
                    if (dotsContainer && dotsContainer.children[i]) dotsContainer.children[i].classList.remove('active');
                });
                if (dotsContainer && dotsContainer.children[index]) dotsContainer.children[index].classList.add('active');
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

            if (dotsContainer && slides.length > 1) {
                slides.forEach((_, i) => {
                    const dot = document.createElement('div');
                    dot.classList.add('carousel-dot');
                    dot.addEventListener('click', () => { showSlide(i); resetInterval(); });
                    dotsContainer.appendChild(dot);
                });
            }

            if (slides.length > 0) {
                showSlide(0);
                if (slides.length > 1) startInterval();
            }

            document.getElementById('prevSlide')?.addEventListener('click', () => { prevSlide(); resetInterval(); });
            document.getElementById('nextSlide')?.addEventListener('click', () => { nextSlide(); resetInterval(); });

            function startInterval() { slideInterval = setInterval(nextSlide, 5000); }
            function resetInterval() { clearInterval(slideInterval); startInterval(); }
            
            // Password Toggle
            window.togglePassword = function(inputId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(inputId + '-icon');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility_off';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility';
                }
            };

            // Password Requirements Validation
            const passwordInput = document.getElementById('password');
            const reqLength = document.getElementById('req-length');
            const reqMixed = document.getElementById('req-mixed');
            const reqNumber = document.getElementById('req-number');

            passwordInput.addEventListener('input', function() {
                const val = this.value;
                
                // Length
                if (val.length >= 8) {
                    updateReq(reqLength, true);
                } else {
                    updateReq(reqLength, false);
                }

                // Mixed Case
                if (/[a-z]/.test(val) && /[A-Z]/.test(val)) {
                    updateReq(reqMixed, true);
                } else {
                    updateReq(reqMixed, false);
                }

                // Number
                if (/[0-9]/.test(val)) {
                    updateReq(reqNumber, true);
                } else {
                    updateReq(reqNumber, false);
                }
            });

            function updateReq(el, valid) {
                const icon = el.querySelector('.material-symbols-outlined');
                if (valid) {
                    el.classList.remove('text-gray-400', 'dark:text-gray-500');
                    el.classList.add('text-indigo-600', 'dark:text-indigo-400');
                    icon.textContent = 'check_circle';
                    icon.classList.add('fill-1');
                } else {
                    el.classList.add('text-gray-400', 'dark:text-gray-500');
                    el.classList.remove('text-indigo-600', 'dark:text-indigo-400');
                    icon.textContent = 'radio_button_unchecked';
                    icon.classList.remove('fill-1');
                }
            }
        });
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-sans antialiased h-screen w-full flex overflow-hidden selection:bg-primary selection:text-white">

<main class="hidden md:flex flex-1 relative bg-slate-100 dark:bg-slate-900 overflow-hidden h-screen">
    <!-- Carousel Slides Dynamic -->
    <div class="carousel-container absolute inset-0">
        <div class="carousel-inner">
            @if(isset($carouselSlides) && $carouselSlides->count() > 0)
                @foreach($carouselSlides as $index => $slide)
                <div class="carousel-slide">
                    @if($slide->image_path)
                        <img alt="{{ $slide->title }}" class="transform scale-105" src="{{ $slide->image_url }}"/>
                    @else
                        <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-9xl text-slate-600">image</span>
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-gradient-to-r from-background-dark/80 via-transparent to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-background-dark/20 to-transparent"></div>
                    
                    <div class="absolute bottom-0 w-full p-12 lg:p-16 flex items-end justify-between z-10 pointer-events-none">
                        <div class="space-y-6 max-w-xl">
                            <div class="inline-flex px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-medium tracking-wide uppercase">
                                Fitur Unggulan
                            </div>
                            <h2 class="text-4xl lg:text-5xl font-bold text-white leading-tight drop-shadow-sm">
                                {{ $slide->title }} <br/>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-white">{{ $slide->subtitle }}</span>
                            </h2>
                            @if($slide->description)
                            <p class="text-gray-300 text-lg max-w-md leading-relaxed">
                                {{ $slide->description }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback Static Slide -->
                <div class="carousel-slide">
                    <img alt="Teachers collaborating" class="transform scale-105" src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"/>
                    <div class="absolute inset-0 bg-gradient-to-r from-background-dark/80 via-transparent to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-background-dark/20 to-transparent"></div>
                    
                    <div class="absolute bottom-0 w-full p-12 lg:p-16 flex items-end justify-between z-10">
                        <div class="space-y-6 max-w-xl">
                            <div class="inline-flex px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-medium tracking-wide uppercase">
                                Keamanan Akun
                            </div>
                            <h2 class="text-4xl lg:text-5xl font-bold text-white leading-tight drop-shadow-sm">
                                Lindungi Akun <br/>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-white">Anda Sekarang</span>
                            </h2>
                            <p class="text-gray-300 text-lg max-w-md leading-relaxed">
                                Mengganti password secara berkala adalah langkah penting untuk menjaga keamanan data dan privasi Anda.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Dots Container -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
            <div class="carousel-dots"></div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="absolute bottom-12 right-12 flex items-center gap-5 z-20">
        <button aria-label="Previous Slide" class="carousel-nav-button group" id="prevSlide">
            <span class="material-symbols-outlined text-3xl group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
        </button>
        <button aria-label="Next Slide" class="carousel-nav-button group" id="nextSlide">
            <span class="material-symbols-outlined text-3xl group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
        </button>
    </div>
</main>

<aside class="w-full md:w-[560px] lg:w-[600px] xl:w-[640px] flex-shrink-0 flex flex-col h-screen bg-white dark:bg-slate-900 border-l border-gray-200 dark:border-slate-800 shadow-2xl z-20 overflow-hidden relative transition-colors duration-300">
    <!-- Top Actions -->
    <div class="absolute top-6 right-6 flex items-center gap-3 z-30">
        <button onclick="toggleTheme()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all shadow-sm">
            <span class="material-symbols-outlined text-xl" id="theme-icon">light_mode</span>
        </button>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all font-semibold text-sm shadow-sm">
                <span class="material-symbols-outlined text-lg">logout</span>
                Keluar
            </button>
        </form>
    </div>

    <div class="h-full flex flex-col justify-center px-6 py-12 sm:px-10 lg:px-14 max-w-lg mx-auto w-full overflow-y-auto scrollbar-hide">
        <div class="mb-8 text-center animate-[fadeIn_0.6s_ease-out]">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl sm:rounded-3xl mb-6 shadow-xl shadow-orange-500/30 transform hover:scale-105 hover:rotate-3 transition-all duration-300">
                <span class="material-symbols-outlined text-4xl sm:text-5xl text-white font-light">key</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">
                Ganti Password
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                Anda harus mengganti password default untuk keamanan akun
            </p>
        </div>

        <!-- Alert Box -->
        <div class="mb-8 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 dark:border-amber-400 p-4 rounded-xl animate-[slideUp_0.5s_ease-out_0.1s_both]">
            <div class="flex gap-3">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">warning</span>
                <p class="text-sm text-amber-800 dark:text-amber-300 font-medium leading-relaxed">
                    Password default tidak aman. Silakan buat password baru yang kuat.
                </p>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-4 rounded-xl text-sm font-medium border border-red-200 dark:border-red-800 animate-[slideUp_0.5s_ease-out_0.1s_both]">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-xl">error</span>
                <span class="font-bold">Validasi Gagal!</span>
            </div>
            <ul class="list-disc list-inside space-y-1 ml-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('change-password.update') }}" class="space-y-6 animate-[slideUp_0.5s_ease-out_0.2s_both]" method="POST">
            @csrf
            
            <!-- Password Baru -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300" for="password">
                    Password Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-xl group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">lock</span>
                    </div>
                    <input class="block w-full pl-12 pr-14 py-3.5 border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-all duration-200 text-sm font-medium hover:border-gray-300 dark:hover:border-slate-600" 
                           id="password" 
                           name="password" 
                           placeholder="Masukkan password baru" 
                           type="password"
                           required/>
                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        <span class="material-symbols-outlined text-xl" id="password-icon">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300" for="password_confirmation">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-xl group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">verified_user</span>
                    </div>
                    <input class="block w-full pl-12 pr-14 py-3.5 border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-all duration-200 text-sm font-medium hover:border-gray-300 dark:hover:border-slate-600" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="Ketik ulang password baru" 
                           type="password"
                           required/>
                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        <span class="material-symbols-outlined text-xl" id="password_confirmation-icon">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Syarat Password Checklist -->
            <div class="p-5 bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/30 rounded-2xl space-y-3">
                <h3 class="text-sm font-bold text-indigo-900 dark:text-indigo-300 flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">info</span>
                    Syarat Password
                </h3>
                <div class="space-y-2.5">
                    <div id="req-length" class="flex items-center gap-3 text-xs font-medium text-gray-400 dark:text-gray-500 transition-colors duration-300">
                        <span class="material-symbols-outlined text-lg">radio_button_unchecked</span>
                        <span>Minimal 8 karakter</span>
                    </div>
                    <div id="req-mixed" class="flex items-center gap-3 text-xs font-medium text-gray-400 dark:text-gray-500 transition-colors duration-300">
                        <span class="material-symbols-outlined text-lg">radio_button_unchecked</span>
                        <span>Huruf besar (A-Z) & huruf kecil (a-z)</span>
                    </div>
                    <div id="req-number" class="flex items-center gap-3 text-xs font-medium text-gray-400 dark:text-gray-500 transition-colors duration-300">
                        <span class="material-symbols-outlined text-lg">radio_button_unchecked</span>
                        <span>Mengandung angka (0-9)</span>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button class="group w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-base font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 hover:shadow-indigo-500/40 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 transition-all duration-200 transform hover:-translate-y-0.5 active:scale-[0.98]" type="submit">
                    <span class="material-symbols-outlined mr-2">check</span>
                    Simpan Password Baru
                </button>
                <p class="mt-4 text-center text-xs font-medium text-gray-500 dark:text-gray-500">
                    Setelah mengubah password, Anda akan diarahkan ke dashboard
                </p>
            </div>
        </form>
    </div>
</aside>

</body>
</html>
