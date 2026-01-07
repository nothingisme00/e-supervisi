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
                <!-- Fallback Static Slide (User Design) -->
                <div class="carousel-slide">
                    <img alt="Teachers collaborating on E-Supervisi" class="transform scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAGIzlDZr5Wfd2gYd6cD-0hcujbc1twwDB47vpHpt4n8A7FwHHFcPJKF_o0Ad0VpVYKKpuTFf5ozXdN1eEmuyME3UY40ZKqfHhab5SS5lr_NFrhpqgwcTQ7S_rha-KHxU2ybGQ_Y-Bui4ZLL46RIYhEQbbEVYEJhPmS4twltgQPPV-lV2jApRm7E5tcyDYeWxKLDeyh64ztLYENS8e5pRkMZnK92edrMF_73JW_LLzHo8lg4qVTOPh3SgwW7KiHSUP20dZ6ZTjkkafI"/>
                    <div class="absolute inset-0 bg-gradient-to-r from-background-dark/80 via-transparent to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-background-dark/20 to-transparent"></div>
                    
                    <div class="absolute bottom-0 w-full p-12 lg:p-16 flex items-end justify-between z-10">
                        <div class="space-y-6 max-w-xl">
                            <div class="inline-flex px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-medium tracking-wide uppercase">
                                Fitur Unggulan
                            </div>
                            <h2 class="text-4xl lg:text-5xl font-bold text-white leading-tight drop-shadow-sm">
                                Meningkatkan Kualitas <br/>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-white">Pendidikan Digital</span>
                            </h2>
                            <p class="text-gray-300 text-lg max-w-md leading-relaxed">
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
    <div class="h-full flex flex-col justify-center px-6 py-4 sm:px-10 lg:px-14 max-w-lg mx-auto w-full overflow-y-auto scrollbar-hide">
        <!-- Header Section -->
        <div class="mb-8 sm:mb-10 text-center animate-[fadeIn_0.6s_ease-out]">
            <!-- Logo -->
            <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 bg-indigo-600 rounded-xl mb-5 shadow-lg">
                <span class="material-symbols-outlined text-2xl sm:text-3xl text-white">school</span>
            </div>
            
            <!-- App Name -->
            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-white mb-1">
                {{ config('app.name', 'E-Supervisi') }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                Sistem Supervisi Pembelajaran Terpadu
            </p>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
        <div class="mb-5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-3.5 rounded-lg text-sm border border-red-100 dark:border-red-800/50 flex items-center gap-3 animate-[slideUp_0.4s_ease-out]">
            <span class="material-symbols-outlined text-lg">error</span>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <!-- Welcome Text -->
        <div class="mb-6 animate-[slideUp_0.4s_ease-out]">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-1">Selamat Datang</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Masuk untuk melanjutkan ke sistem</p>
        </div>

            <form action="{{ route('login') }}" class="space-y-4 sm:space-y-6 animate-[slideUp_0.5s_ease-out_0.2s_both]" method="POST" id="loginForm">
                @csrf
                <div class="space-y-1.5 sm:space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300" for="nik">
                        NIK
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-xl group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">badge</span>
                        </div>
                        <input autocomplete="username" 
                               class="block w-full pl-12 pr-4 py-3 sm:py-3.5 border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-all duration-200 text-sm font-medium hover:border-gray-300 dark:hover:border-slate-600" 
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
                <div class="space-y-1.5 sm:space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300" for="password">
                        Password
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-xl group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">lock</span>
                        </div>
                        <input autocomplete="current-password" 
                               class="block w-full pl-12 pr-14 py-3 sm:py-3.5 border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-all duration-200 text-sm font-medium hover:border-gray-300 dark:hover:border-slate-600" 
                               id="password" 
                               name="password" 
                               placeholder="Password" 
                               type="password"
                               required/>
                        <div id="toggle-password-btn" class="absolute inset-y-0 right-0 pr-5 flex items-center cursor-pointer text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span class="material-symbols-outlined text-xl">visibility</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1.5 sm:space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300" for="role">
                        Role
                    </label>
                    <div class="relative" id="custom-dropdown">
                        <input type="hidden" name="role" id="role-input" value="{{ old('role') }}" required>
                        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-4 flex items-center pointer-events-none z-10">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-xl group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">admin_panel_settings</span>
                        </div>
                        
                        <button type="button" 
                                id="dropdown-button"
                                class="relative w-full pl-12 pr-14 py-3 sm:py-3.5 text-left border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-all duration-200 text-sm font-medium hover:border-gray-300 dark:hover:border-slate-600 cursor-pointer">
                            <span id="dropdown-label" class="{{ old('role') ? '' : 'text-gray-400 dark:text-gray-500' }}">
                                {{ old('role') ? ucfirst(str_replace('_', ' ', old('role'))) : 'Pilih role' }}
                            </span>
                            <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                <span class="material-symbols-outlined text-xl transition-transform duration-200" id="dropdown-arrow">expand_more</span>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="dropdown-menu" 
                             class="dropdown-menu-custom absolute top-full mt-2 left-0 w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-2xl z-50 hidden opacity-0 transform scale-95 transition-all duration-200 origin-top">
                            <div class="p-1.5 space-y-1">
                                <div class="dropdown-item px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-3" data-value="admin">
                                    <span class="material-symbols-outlined text-lg">shield_person</span>
                                    Admin
                                </div>
                                <div class="dropdown-item px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-3" data-value="kepala_sekolah">
                                    <span class="material-symbols-outlined text-lg">account_balance</span>
                                    Kepala Sekolah
                                </div>
                                <div class="dropdown-item px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition-colors flex items-center gap-3" data-value="guru">
                                    <span class="material-symbols-outlined text-lg">person</span>
                                    Guru
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer group select-none">
                        <div class="relative">
                            <input class="peer sr-only" id="remember-me" name="remember" type="checkbox"/>
                            <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-slate-900 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all duration-200"></div>
                            <span class="material-symbols-outlined absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-sm opacity-0 peer-checked:opacity-100 transition-opacity duration-200 font-bold">check</span>
                        </div>
                        <span class="ml-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Ingat saya</span>
                    </label>
                </div>
                <div class="pt-4 sm:pt-6">
                    <button id="loginBtn" class="group relative w-full flex justify-center items-center py-3 sm:py-3.5 px-6 rounded-lg text-sm sm:text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-900 transition-colors duration-200 disabled:opacity-70 disabled:cursor-not-allowed" type="submit">
                        <span id="loginBtnText">Masuk</span>
                        <svg id="loginSpinner" class="hidden ml-2 h-4 w-4 sm:h-5 sm:w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>

        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between animate-[fadeIn_0.6s_ease-out_0.2s_both]">
            <button class="group flex items-center space-x-2 px-3.5 py-2 rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-400 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-600 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 transition-all duration-200 shadow-sm" onclick="toggleTheme()">
                <span class="material-symbols-outlined text-base transition-transform duration-300 group-hover:rotate-180" id="theme-icon">light_mode</span>
                <span id="theme-text">Light Mode</span>
            </button>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-500">
                © {{ date('Y') }} E-Supervisi
            </p>
        </div>
    </div>
</aside>

</body>

</body>
</html>
