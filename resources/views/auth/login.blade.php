<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'E-Supervisi') }}</title>
    
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
        .modern-input:focus ~ label,
        .modern-input:not(:placeholder-shown) ~ label {
            transform: translateY(-0.7rem) scale(0.85);
            color: var(--primary-color);
        }
        
        /* Hide default select arrow */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none;
        }
        select::-ms-expand {
            display: none;
        }
        
        /* Modern Select Dropdown Styling */
        .dropdown-item.active {
            background-color: #6366f1;
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
        
        select option[disabled] {
            color: #94a3b8;
            font-style: italic;
        }
        
        /* Hide scrollbar */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;  /* Chrome, Safari and Opera */
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6366F1", // Indigo-500 for a fresher look
                        primaryHover: "#4F46E5", // Indigo-600
                        "background-light": "#F8FAFC", 
                        "background-dark": "#0F172A", 
                        "card-light": "#FFFFFF",
                        "card-dark": "#1E293B",
                        "text-primary-light": "#1E293B",
                        "text-primary-dark": "#F8FAFC",
                        "text-secondary-light": "#64748B",
                        "text-secondary-dark": "#94A3B8",
                        "border-light": "#E2E8F0",
                        "border-dark": "#334155",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                        sans: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        xl: "1rem",
                        "2xl": "1.5rem",
                        "3xl": "2rem",
                    },
                    boxShadow: {
                        'soft': '0 20px 40px -10px rgba(0,0,0,0.08)',
                        'card': '0 0 0 1px rgba(0,0,0,0.03), 0 1px 2px rgba(0,0,0,0.05), 0 10px 40px -10px rgba(0,0,0,0.05)',
                        'input-focus': '0 0 0 4px rgba(99, 102, 241, 0.1)',
                    }
                },
            },
        };

        function toggleTheme() {
            const html = document.documentElement;
            const themeText = document.getElementById('theme-text');
            const themeIcon = document.getElementById('theme-icon');
            
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                if(themeText) themeText.textContent = 'Light Mode';
                if(themeIcon) themeIcon.textContent = 'light_mode';
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if(themeText) themeText.textContent = 'Dark Mode';
                if(themeIcon) themeIcon.textContent = 'dark_mode';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Theme Initialization
            const themeText = document.getElementById('theme-text');
            const themeIcon = document.getElementById('theme-icon');
            
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                if(themeText) themeText.textContent = 'Dark Mode';
                if(themeIcon) themeIcon.textContent = 'dark_mode';
            } else {
                document.documentElement.classList.remove('dark');
                if(themeText) themeText.textContent = 'Light Mode';
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
            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    const iconSpan = this.querySelector('span');
                    if(iconSpan) {
                         iconSpan.textContent = type === 'password' ? 'visibility' : 'visibility_off';
                    }
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
                        const content = item.innerHTML;
                        
                        roleInput.value = value;
                        
                        // Extract text only, excluding material icons to avoid "person Guru" issue
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = content;
                        const icons = tempDiv.querySelectorAll('.material-symbols-outlined');
                        icons.forEach(icon => icon.remove());
                        
                        dropdownLabel.textContent = tempDiv.textContent.trim();
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
                        <img alt="{{ $slide->title }}" class="transform scale-105" src="{{ $slide->image_url }}" loading="lazy" decoding="async"/>
                    @else
                        <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-9xl text-slate-600">image</span>
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-gradient-to-r from-background-dark/80 via-transparent to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-background-dark/20 to-transparent"></div>
                    
                    <div class="absolute bottom-0 w-full p-8 lg:p-12 xl:p-16 flex items-end justify-between z-10 pointer-events-none">
                        @if($slide->title || $slide->description)
                        <div class="space-y-4 lg:space-y-6 max-w-lg">
                            @if($slide->title)
                            <h2 class="text-2xl lg:text-4xl xl:text-5xl font-bold text-white leading-tight drop-shadow-sm">
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
                <!-- Fallback Static Slide (User Design) -->
                <div class="carousel-slide">
                    <img alt="Teachers collaborating on E-Supervisi" class="transform scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAGIzlDZr5Wfd2gYd6cD-0hcujbc1twwDB47vpHpt4n8A7FwHHFcPJKF_o0Ad0VpVYKKpuTFf5ozXdN1eEmuyME3UY40ZKqfHhab5SS5lr_NFrhpqgwcTQ7S_rha-KHxU2ybGQ_Y-Bui4ZLL46RIYhEQbbEVYEJhPmS4twltgQPPV-lV2jApRm7E5tcyDYeWxKLDeyh64ztLYENS8e5pRkMZnK92edrMF_73JW_LLzHo8lg4qVTOPh3SgwW7KiHSUP20dZ6ZTjkkafI" loading="lazy" decoding="async"/>
                    <div class="absolute inset-0 bg-gradient-to-r from-background-dark/80 via-transparent to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-background-dark/20 to-transparent"></div>
                    
                    <div class="absolute bottom-0 w-full p-8 lg:p-12 xl:p-16 flex items-end justify-between z-10">
                        <div class="space-y-4 lg:space-y-6 max-w-lg">
                            <div class="inline-flex px-3 lg:px-4 py-1 lg:py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] lg:text-xs font-medium tracking-wide uppercase">
                                Fitur Unggulan
                            </div>
                            <h2 class="text-2xl lg:text-4xl xl:text-5xl font-bold text-white leading-tight drop-shadow-sm">
                                Meningkatkan Kualitas <br/>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-white">Pendidikan Digital</span>
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
        <button aria-label="Previous Slide" class="carousel-nav-button group w-10 h-10 lg:w-14 lg:h-14" id="prevSlide">
            <span class="material-symbols-outlined text-xl lg:text-3xl group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
        </button>
        <button aria-label="Next Slide" class="carousel-nav-button group w-10 h-10 lg:w-14 lg:h-14" id="nextSlide">
            <span class="material-symbols-outlined text-xl lg:text-3xl group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
        </button>
    </div>
</main>

<aside class="w-full md:w-[420px] lg:w-[480px] xl:w-[540px] 2xl:w-[600px] flex-shrink-0 flex flex-col h-screen bg-white dark:bg-slate-900 border-l border-gray-200 dark:border-slate-800 shadow-2xl z-20 overflow-hidden relative transition-colors duration-300">
    <div class="h-full flex flex-col justify-center px-6 py-4 sm:px-8 sm:py-5 lg:py-6 xl:px-10 max-w-md mx-auto w-full overflow-y-auto scrollbar-hide">
        <!-- Header Section -->
        <div class="mb-4 lg:mb-6 text-center animate-[fadeIn_0.6s_ease-out]">
            <!-- Logo -->
            <div class="inline-flex items-center justify-center w-12 h-12 lg:w-14 lg:h-14 bg-indigo-600 rounded-xl mb-3 lg:mb-4 shadow-lg">
                <span class="material-symbols-outlined text-xl lg:text-2xl text-white">school</span>
            </div>
            
            <!-- App Name -->
            <h1 class="text-xl lg:text-2xl font-semibold text-gray-900 dark:text-white mb-0.5">
                {{ config('app.name', 'E-Supervisi') }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-xs lg:text-sm">
                Sistem Supervisi Pembelajaran Terpadu
            </p>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
        <div class="mb-3 lg:mb-4 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-2.5 lg:p-3 rounded-lg text-xs lg:text-sm border border-red-100 dark:border-red-800/50 flex items-center gap-2 animate-[slideUp_0.4s_ease-out]">
            <span class="material-symbols-outlined text-base lg:text-lg">error</span>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <!-- Welcome Text -->
        <div class="mb-3 lg:mb-4 animate-[slideUp_0.4s_ease-out]">
            <h2 class="text-base lg:text-lg font-semibold text-gray-900 dark:text-white mb-0.5">Selamat Datang</h2>
            <p class="text-gray-500 dark:text-gray-400 text-xs lg:text-sm">Masuk untuk melanjutkan ke sistem</p>
        </div>

            <form action="{{ route('login') }}" class="space-y-3 lg:space-y-4 animate-[slideUp_0.5s_ease-out_0.2s_both]" method="POST" id="loginForm">
                @csrf
                <div class="space-y-1 lg:space-y-1.5">
                    <label class="block text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300" for="nik">
                        NIK
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 lg:pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-lg lg:text-xl group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">badge</span>
                        </div>
                        <input autocomplete="username" 
                               class="block w-full pl-10 lg:pl-12 pr-4 py-2.5 lg:py-3 border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-all duration-200 text-xs lg:text-sm font-medium hover:border-gray-300 dark:hover:border-slate-600" 
                               id="nik" 
                               name="nik" 
                               value="{{ old('nik') }}"
                               placeholder="16 digit NIK" 
                               type="text"
                               required
                               maxlength="16"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)"/>
                    </div>
                </div>
                <div class="space-y-1 lg:space-y-1.5">
                    <label class="block text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300" for="password">
                        Password
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 lg:pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-lg lg:text-xl group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">lock</span>
                        </div>
                        <input autocomplete="current-password" 
                               class="block w-full pl-10 lg:pl-12 pr-12 py-2.5 lg:py-3 border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-all duration-200 text-xs lg:text-sm font-medium hover:border-gray-300 dark:hover:border-slate-600" 
                               id="password" 
                               name="password" 
                               placeholder="Password" 
                               type="password"
                               required/>
                        <div id="toggle-password-btn" class="absolute inset-y-0 right-0 pr-3 lg:pr-4 flex items-center cursor-pointer text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span class="material-symbols-outlined text-lg lg:text-xl">visibility</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1 lg:space-y-1.5">
                    <label class="block text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300" for="role">
                        Role
                    </label>
                    <div class="relative" id="custom-dropdown">
                        <input type="hidden" name="role" id="role-input" value="{{ old('role') }}" required>
                        <div class="absolute inset-y-0 left-0 pl-3 lg:pl-4 flex items-center pointer-events-none z-10">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-lg lg:text-xl group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">admin_panel_settings</span>
                        </div>
                        
                        <button type="button" 
                                id="dropdown-button"
                                class="relative w-full pl-10 lg:pl-12 pr-12 py-2.5 lg:py-3 text-left border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-all duration-200 text-xs lg:text-sm font-medium hover:border-gray-300 dark:hover:border-slate-600 cursor-pointer">
                            <span id="dropdown-label" class="{{ old('role') ? '' : 'text-gray-400 dark:text-gray-500' }}">
                                {{ old('role') ? ucfirst(str_replace('_', ' ', old('role'))) : 'Pilih role' }}
                            </span>
                            <div class="absolute inset-y-0 right-0 pr-3 lg:pr-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                <span class="material-symbols-outlined text-lg lg:text-xl transition-transform duration-200" id="dropdown-arrow">expand_more</span>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="dropdown-menu" 
                             class="dropdown-menu-custom absolute top-full mt-1.5 left-0 w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-2xl z-50 hidden opacity-0 transform scale-95 transition-all duration-200 origin-top">
                            <div class="p-1 space-y-0.5">
                                <div class="dropdown-item px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-2 lg:gap-3" data-value="admin">
                                    <span class="material-symbols-outlined text-base lg:text-lg">shield_person</span>
                                    Admin
                                </div>
                                <div class="dropdown-item px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-2 lg:gap-3" data-value="kepala_sekolah">
                                    <span class="material-symbols-outlined text-base lg:text-lg">account_balance</span>
                                    Kepala Sekolah
                                </div>
                                <div class="dropdown-item px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-2 lg:gap-3" data-value="guru">
                                    <span class="material-symbols-outlined text-base lg:text-lg">person</span>
                                    Guru
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center cursor-pointer group select-none">
                        <div class="relative">
                            <input class="peer sr-only" id="remember-me" name="remember" type="checkbox" value="1"/>
                            <div class="w-4 h-4 lg:w-5 lg:h-5 border-2 border-gray-300 dark:border-gray-600 rounded-md lg:rounded-lg bg-white dark:bg-slate-900 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all duration-200"></div>
                            <span class="material-symbols-outlined absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity duration-200 font-bold">check</span>
                        </div>
                        <span class="ml-2 text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Ingat saya</span>
                    </label>
                </div>
                <div class="pt-2 lg:pt-3">
                    <button id="loginBtn" class="group relative w-full flex justify-center items-center py-2.5 lg:py-3 px-6 rounded-xl text-xs lg:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-900 transition-colors duration-200 disabled:opacity-70 disabled:cursor-not-allowed shadow-lg shadow-indigo-500/25" type="submit">
                        <span id="loginBtnText">Masuk</span>
                        <svg id="loginSpinner" class="hidden ml-2 h-4 w-4 lg:h-5 lg:w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>

        <div class="mt-4 lg:mt-5 pt-3 lg:pt-4 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between animate-[fadeIn_0.6s_ease-out_0.2s_both]">
            <button class="group flex items-center space-x-1.5 px-2.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-semibold text-gray-600 dark:text-gray-400 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-600 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 transition-all duration-200 shadow-sm" onclick="toggleTheme()">
                <span class="material-symbols-outlined text-sm transition-transform duration-300 group-hover:rotate-180" id="theme-icon">light_mode</span>
                <span id="theme-text">Light Mode</span>
            </button>
            <p class="text-[10px] lg:text-xs font-medium text-gray-500 dark:text-gray-500">
                © {{ date('Y') }} E-Supervisi
            </p>
        </div>
    </div>
</aside>

</body>

</body>
</html>
