@extends('layouts.modern')

@section('page-title', 'Beranda')

@section('content')
<div class="w-full lg:w-11/12 xl:w-5/6 mx-auto px-0 sm:px-3 md:px-6 lg:px-8">
    <!-- Hero Carousel Section -->
    @if(isset($carouselSlides) && $carouselSlides->count() > 0)
    <div class="mb-3 sm:mb-4 md:mb-6">
        <div class="guru-carousel-container relative w-full h-32 sm:h-44 md:h-56 lg:h-64 rounded-lg sm:rounded-xl md:rounded-2xl overflow-hidden shadow-md sm:shadow-lg">
            <!-- Carousel Inner -->
            <div class="guru-carousel-inner flex w-full h-full transition-transform duration-700 ease-out">
                @foreach($carouselSlides as $index => $slide)
                <div class="guru-carousel-slide flex-shrink-0 w-full h-full relative">
                    @if($slide->image_path)
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 flex items-center justify-center">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Outer Container: Timeline Supervisi - Container + Inner Cards Architecture -->
    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg md:rounded-xl lg:rounded-2xl p-1.5 sm:p-3 md:p-5 lg:p-6 mb-2 sm:mb-3 md:mb-4 lg:mb-6 min-h-[60vh] {{ $supervisiList->count() == 0 ? 'flex items-center justify-center' : '' }}">
        <!-- Cards Wrapper with flex column and gap -->
        <div class="flex flex-col gap-1.5 sm:gap-3 md:gap-4 w-full">

            <!-- Tips & Informasi - Hidden div for desktop accordion (shown via JS on tablet/desktop) -->
            @if($supervisiList->count() > 0)
            <div id="tips-content" class="hidden md:block overflow-hidden transition-all duration-300 ease-in-out bg-white dark:bg-gray-800 rounded-md sm:rounded-lg md:rounded-xl shadow" style="max-height: 0; opacity: 0;">
                <div class="p-3 sm:p-4 md:p-5">
                    <div class="flex items-center gap-2 mb-3 sm:mb-4">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm sm:text-base md:text-lg font-bold text-gray-900 dark:text-white">Tips & Informasi</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Tip 1: Quick Navigation -->
                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 border border-blue-100 dark:border-blue-900/30">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-1">Quick Navigation</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Gunakan tombol "Panduan" untuk melihat langkah lengkap supervisi</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tip 2: Track Progress -->
                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 border border-emerald-100 dark:border-emerald-900/30">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-emerald-900 dark:text-emerald-300 mb-1">Lacak Status</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Lihat badge status supervisi: Draft, Disubmit, Direview, atau Selesai</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tip 3: Collaboration -->
                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 border border-purple-100 dark:border-purple-900/30">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-purple-900 dark:text-purple-300 mb-1">Kolaborasi</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Klik "Komentar" untuk melihat feedback dari Kepala Sekolah</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Inner Card 2: Timeline Content Cards -->
            @if($supervisiList->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-lg md:rounded-xl shadow w-full">
                    <div class="px-2 py-2 sm:px-3 sm:py-2.5 md:px-5 md:py-4 lg:px-6 lg:py-5">
                        <div class="space-y-2 sm:space-y-3 md:space-y-4">
                @foreach($supervisiList as $item)
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl md:rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition-all duration-200 overflow-hidden">
                    <!-- Header Card -->
                    <div class="p-3 sm:p-3 md:p-4 bg-gradient-to-r from-indigo-50/80 to-purple-50/80 dark:from-indigo-900/20 dark:to-purple-900/20">
                        <div class="flex items-start justify-between gap-3 sm:gap-2.5 md:gap-3">
                            <div class="flex items-center gap-3 sm:gap-2.5 md:gap-3 flex-1 min-w-0">
                                <div class="shrink-0">
                                    <div class="w-11 h-11 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-base sm:text-base md:text-lg shadow-md ring-2 ring-white dark:ring-gray-800">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 sm:gap-2 flex-wrap">
                                        <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base sm:text-base truncate">
                                            {{ $item->user->name }}
                                        </h4>
                                        @if($item->user_id == auth()->id())
                                            <span class="inline-flex items-center gap-1 sm:gap-1 px-2 py-0.5 sm:px-2 bg-indigo-600 dark:bg-indigo-500 text-white text-xs sm:text-xs font-medium rounded-full">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Saya
                                            </span>
                                        @endif
                                    </div>
                                    @if($item->user && ($item->user->mata_pelajaran || $item->user->tingkat))
                                    <p class="text-sm sm:text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1.5 sm:gap-1.5 mt-0.5">
                                        @if($item->user->mata_pelajaran)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            {{ $item->user->mata_pelajaran }}
                                        </span>
                                        @endif
                                        @if($item->user->mata_pelajaran && $item->user->tingkat)
                                        <span class="text-gray-400">•</span>
                                        @endif
                                        @if($item->user->tingkat)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            {{ $item->user->tingkat }}
                                        </span>
                                        @endif
                                    </p>
                                    @endif
                                    <p class="text-xs sm:text-xs text-gray-500 dark:text-gray-500 mt-1">
                                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($item->tanggal_supervisi)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="shrink-0">
                                @if($item->status == 'draft')
                                    <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 bg-gray-100 text-gray-700 text-[10px] sm:text-xs font-semibold rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                        Draft
                                    </span>
                                @elseif($item->status == 'submitted')
                                    <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 bg-amber-100 text-amber-700 text-[10px] sm:text-xs font-semibold rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                        Disubmit
                                    </span>
                                @elseif($item->status == 'under_review')
                                <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 bg-indigo-100 text-indigo-700 text-[10px] sm:text-xs font-semibold rounded-full">
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Direview
                                </span>
                            @elseif($item->status == 'completed')
                                <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 bg-emerald-100 text-emerald-700 text-[10px] sm:text-xs font-semibold rounded-full">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Selesai
                                </span>
                            @elseif($item->status == 'revision')
                                {{-- Badge for revision status --}}
                                <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 bg-rose-100 text-rose-700 text-[10px] sm:text-xs font-semibold rounded-full">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Perlu Revisi
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="px-3 py-2.5 sm:px-3 sm:py-2.5 md:px-4 md:py-3 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                    <!-- Info Cards (Dokumen & Proses) -->
                    <div class="flex items-center gap-2 sm:gap-2 md:gap-3 flex-wrap mb-3 sm:mb-2.5 md:mb-3">
                        @php
                            $docCount = $item->dokumenEvaluasi->count();
                            $hasProses = $item->prosesPembelajaran != null;
                        @endphp

                        <div class="flex items-center gap-1.5 sm:gap-1.5 md:gap-2 px-2.5 py-1.5 sm:px-2.5 sm:py-1.5 md:px-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg md:rounded-lg border border-indigo-100 dark:border-indigo-800">
                            <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-xs sm:text-xs font-semibold text-gray-700 dark:text-gray-300">Dokumen: <span class="{{ $docCount == 7 ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">{{ $docCount }}/7</span></span>
                        </div>

                        @if($hasProses)
                            <div class="flex items-center gap-1 sm:gap-1.5 md:gap-2 px-2 py-1 sm:px-2.5 sm:py-1.5 md:px-3 bg-green-50 dark:bg-green-900/20 rounded-md md:rounded-lg border border-green-100 dark:border-green-800">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-[10px] sm:text-xs font-semibold text-green-700 dark:text-green-300">Proses Selesai</span>
                            </div>
                        @else
                            <div class="flex items-center gap-1 sm:gap-1.5 md:gap-2 px-2 py-1 sm:px-2.5 sm:py-1.5 md:px-3 bg-gray-50 dark:bg-gray-700/50 rounded-md md:rounded-lg border border-gray-200 dark:border-gray-600">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400">Proses Belum</span>
                            </div>
                        @endif

                        @if($item->feedback->count() > 0)
                            <div class="flex items-center gap-1 sm:gap-1.5 md:gap-2 px-2 py-1 sm:px-2.5 sm:py-1.5 md:px-3 bg-purple-50 dark:bg-purple-900/20 rounded-md md:rounded-lg border border-purple-100 dark:border-purple-800">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <span class="text-[10px] sm:text-xs font-semibold text-purple-700 dark:text-purple-300">{{ $item->feedback->count() }} Feedback</span>
                            </div>
                        @endif
                    </div>

                    <!-- Komentar Terbaru (hanya tampil jika sudah submit) - Accordion -->
                    @if($item->status !== 'draft')
                    <div class="mb-3">
                        <button type="button"
                                onclick="toggleComments('{{ $item->id }}')"
                                class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-slate-50 dark:bg-gray-900/30 hover:bg-slate-100 dark:hover:bg-gray-900/50 rounded-lg border border-slate-200 dark:border-gray-700 transition-colors">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span class="text-xs font-semibold text-slate-600 dark:text-gray-400">
                                    {{ $item->feedback ? count($item->feedback) : 0 }} Komentar
                                </span>
                            </div>
                            <svg id="chevron-{{ $item->id }}" class="w-4 h-4 text-slate-500 dark:text-gray-400 transform transition-transform duration-300 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="comments-{{ $item->id }}" class="overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0; opacity: 0;">
                            <div class="mt-2 space-y-2 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                                @if($item->feedback && count($item->feedback) > 0)
                                    @php
                                        $parentComments = $item->feedback->whereNull('parent_id')->sortByDesc('created_at');
                                    @endphp
                                    @foreach($parentComments as $fb)
                                    <div class="bg-slate-50 dark:bg-gray-900/50 rounded-lg p-2.5 border border-slate-200 dark:border-gray-700">
                                        <div class="flex items-start gap-2">
                                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                                                {{ strtoupper(substr($fb->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                                                    <p class="text-xs font-semibold text-slate-700 dark:text-gray-300">{{ $fb->user->name ?? 'User' }}</p>
                                                    @if($fb->user && $fb->user->role === 'kepala_sekolah')
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                                                        <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                        </svg>
                                                        Kepsek
                                                    </span>
                                                    @elseif($fb->user_id == auth()->id())
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                        Anda
                                                    </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-slate-600 dark:text-gray-400">{{ $fb->komentar }}</p>

                                                <!-- Nested Replies -->
                                                @if($fb->replies && $fb->replies->count() > 0)
                                                    <div class="mt-2 ml-4 space-y-2 pl-2 border-l-2 border-slate-200 dark:border-gray-700">
                                                        @foreach($fb->replies->take(2) as $reply)
                                                        <div class="bg-white dark:bg-gray-800/50 rounded p-2">
                                                            <div class="flex items-start gap-1.5">
                                                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                                                                    {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <div class="flex items-center gap-1 mb-0.5">
                                                                        <span class="text-xs font-semibold text-slate-700 dark:text-gray-300">{{ $reply->user->name ?? 'User' }}</span>
                                                                        @if($reply->user_id == auth()->id())
                                                                        <span class="inline-flex items-center px-1 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                                            Anda
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                    <p class="text-xs text-slate-600 dark:text-gray-400">{{ $reply->komentar }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                        @if($fb->replies->count() > 2)
                                                        <p class="text-xs text-slate-500 dark:text-gray-400 italic pl-2">+{{ $fb->replies->count() - 2 }} balasan lainnya...</p>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-3">
                                        <p class="text-xs text-slate-500 dark:text-gray-400">Belum ada komentar</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action Footer -->
                <div class="px-3 py-2.5 sm:px-3 sm:py-2.5 md:px-4 md:py-3 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-2.5 sm:gap-2.5 md:gap-3">
                    <div class="flex items-center gap-2 sm:gap-2">
                        @if($item->user_id == auth()->id())
                            {{-- Only show delete button for draft status, not for revision --}}
                            @if($item->status == 'draft')
                                <form id="delete-supervisi-{{ $item->id }}" method="POST" action="{{ route('guru.supervisi.delete', $item->id) }}" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="button"
                                        onclick="confirmDeleteSupervisi({{ $item->id }})"
                                        class="inline-flex items-center gap-1.5 sm:gap-1.5 px-3 py-2 sm:px-2.5 sm:py-1.5 md:px-3 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 text-xs sm:text-xs font-semibold rounded-lg md:rounded-lg transition-all border border-red-200 dark:border-red-800"
                                        title="Hapus supervisi"
                                    >
                                        <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="hidden sm:inline">Hapus</span>
                                    </button>
                                </form>
                            @endif

                            @if($item->status == 'draft')
                                <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 sm:gap-1.5 px-4 py-2 sm:px-3 sm:py-1.5 md:px-4 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs sm:text-xs font-semibold rounded-lg md:rounded-lg transition-all shadow-sm">
                                    <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                    Lanjutkan
                                </a>
                            @elseif($item->status == 'revision')
                                {{-- Button to edit/revise supervisi --}}
                                <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 sm:gap-1.5 px-4 py-2 sm:px-3 sm:py-1.5 md:px-4 bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white text-xs sm:text-xs font-semibold rounded-lg md:rounded-lg transition-all shadow-sm">
                                    <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Revisi</span>
                                    <span class="sm:hidden">Edit</span>
                                </a>
                            @else
                                <a href="{{ route('guru.supervisi.detail', $item->id) }}" class="inline-flex items-center gap-1.5 sm:gap-1.5 px-4 py-2 sm:px-3 sm:py-1.5 md:px-4 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-xs sm:text-xs font-semibold rounded-lg md:rounded-lg transition-all shadow-sm">
                                    <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                            @endif
                        @else
                            <a href="{{ route('guru.supervisi.view', $item->id) }}" class="inline-flex items-center gap-1.5 sm:gap-1.5 px-4 py-2 sm:px-3 sm:py-1.5 md:px-4 bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 text-white text-xs sm:text-xs font-semibold rounded-lg md:rounded-lg transition-all shadow-sm">
                                <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
                        </div>
                        <!-- End space-y-4 -->
                        
                        <!-- Pagination Links -->
                        @if($supervisiList->hasPages())
                        <div class="mt-6 px-2">
                            {{ $supervisiList->links() }}
                        </div>
                        @endif
                    </div>
                    <!-- End padding wrapper -->
                </div>
                <!-- End Inner Card 2: Timeline Content -->
            @else
                <!-- Inner Card 2: Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-xl shadow w-full">
                    <div class="px-3 py-4 sm:px-5 sm:py-6 md:px-6 md:py-8 lg:px-8 lg:py-10">
                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl sm:rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-6 sm:p-10 md:p-14 lg:p-16 text-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-5 md:mb-6 shadow-inner">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg md:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-3">Belum Ada Supervisi</h3>
                            <p class="text-sm sm:text-base md:text-base text-gray-500 dark:text-gray-400 mb-5 sm:mb-6 md:mb-8 max-w-md mx-auto leading-relaxed">Anda belum memiliki supervisi apapun. Mulai dengan membuat supervisi baru.</p>

                            <button onclick="openSupervisiModal()" class="inline-flex items-center gap-2 sm:gap-2.5 px-5 py-2.5 sm:px-6 sm:py-3 md:px-7 md:py-3.5 lg:px-8 lg:py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm sm:text-base md:text-base font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 sm:w-5 sm:h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Buat Supervisi Baru
                            </button>
                        </div>
                    </div>
                </div>
                <!-- End Inner Card 2: Empty State -->
            @endif
        </div>
        <!-- End Cards Wrapper -->
    </div>
    <!-- End Outer Container -->
</div>

<!-- Welcome Onboarding Modal (First Time User) -->
<div id="welcomeModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[80] items-center justify-center p-2 sm:p-3 md:p-4 opacity-0 transition-opacity duration-500" style="display: none;" onclick="closeWelcomeModal()">
    <div id="welcomeModalContent" class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl md:rounded-2xl shadow-2xl w-[92%] sm:w-[85%] md:max-w-lg transform scale-90 opacity-0 transition-all duration-500" onclick="event.stopPropagation()">
        <!-- Modal Content -->
        <div class="relative overflow-hidden">
            <div class="relative px-4 py-5 sm:px-5 sm:py-6 md:px-6 md:py-7">
                <!-- Icon -->
                <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>

                <!-- Title -->
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 dark:text-white text-center mb-2 sm:mb-2.5 md:mb-3">
                    Selamat Datang! 👋
                </h3>

                <!-- Description -->
                <p class="text-sm sm:text-base md:text-lg text-gray-600 dark:text-gray-300 text-center mb-3 sm:mb-4 md:mb-5 leading-relaxed">
                    Belum ada supervisi. <strong>Baca panduan</strong> untuk memulai dengan baik.
                </p>

                <!-- Benefits List - Simplified to 2 items -->
                <div class="space-y-2 sm:space-y-2.5 mb-4 sm:mb-5 bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 sm:p-3.5 md:p-4">
                    <div class="flex items-start gap-2.5 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300">Pahami alur proses supervisi</p>
                    </div>
                    <div class="flex items-start gap-2.5 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300">Ketahui dokumen yang diperlukan</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-2.5 sm:gap-3">
                    <button onclick="openGuideFromWelcome()" class="w-full px-5 py-3 sm:px-6 sm:py-3.5 md:px-7 md:py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg sm:rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-sm sm:text-base md:text-lg">
                        <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Baca Panduan Sekarang
                    </button>

                    <button onclick="closeWelcomeModal()" class="w-full px-5 py-2.5 sm:px-6 sm:py-3 md:px-7 md:py-3.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg sm:rounded-xl transition-all text-sm sm:text-base md:text-lg">
                        Nanti Saja
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown hover effect (border color only)
        const allSelects = document.querySelectorAll('select');
        allSelects.forEach(select => {
            select.addEventListener('mouseenter', function() {
                if (document.activeElement !== this) {
                    this.style.borderColor = '#818cf8';
                }
            });
            select.addEventListener('mouseleave', function() {
                if (document.activeElement !== this) {
                    this.style.borderColor = '';
                }
            });
        });
    });

    // Toggle Tips & Informasi Accordion
    function toggleTips() {
        const content = document.getElementById('tips-content');
        const chevron = document.getElementById('tips-chevron');

        if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
            // Open
            content.style.maxHeight = content.scrollHeight + 'px';
            content.style.opacity = '1';
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        } else {
            // Close
            content.style.maxHeight = '0px';
            content.style.opacity = '0';
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    }

    // Delete supervisi function with modal confirmation
    function confirmDeleteSupervisi(supervisiId) {
        showConfirmModal(
            'Apakah Anda yakin ingin menghapus supervisi ini? Semua data termasuk dokumen yang telah diupload akan dihapus secara permanen.',
            'Konfirmasi Hapus Supervisi',
            function() {
                document.getElementById('delete-supervisi-' + supervisiId).submit();
            }
        );
    }

    // Delete supervisi function (async version - if still used)
    async function deleteSupervisi(supervisiId) {
        showConfirmModal(
            'Apakah Anda yakin ingin menghapus supervisi ini? Semua data termasuk dokumen yang telah diupload akan dihapus secara permanen.',
            'Konfirmasi Hapus Supervisi',
            async function() {
                try {
                    const response = await fetch(`/guru/supervisi/${supervisiId}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        showToast(result.message, 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showToast(result.message || 'Gagal menghapus supervisi', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan: ' + error.message, 'error');
                }
            }
        );
    }

    // Welcome Modal Functions
    function showWelcomeModal() {
        const modal = document.getElementById('welcomeModal');
        const content = document.getElementById('welcomeModalContent');

        modal.style.display = 'flex';

        // Trigger reflow
        modal.offsetHeight;

        // Start animation
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-90', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeWelcomeModal() {
        const modal = document.getElementById('welcomeModal');
        const content = document.getElementById('welcomeModalContent');

        // Animate out
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-90', 'opacity-0');

        setTimeout(() => {
            modal.style.display = 'none';
        }, 500);
    }

    function openGuideFromWelcome() {
        // Close welcome modal first
        const welcomeModal = document.getElementById('welcomeModal');
        const welcomeContent = document.getElementById('welcomeModalContent');

        welcomeModal.classList.remove('opacity-100');
        welcomeModal.classList.add('opacity-0');
        welcomeContent.classList.remove('scale-100', 'opacity-100');
        welcomeContent.classList.add('scale-90', 'opacity-0');

        setTimeout(() => {
            welcomeModal.style.display = 'none';
            // Open guide modal (global function in layouts.modern)
            openGuideModal();
        }, 500);
    }


    // Check if user should see welcome modal on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Always show if user has no supervisions (regardless of whether they've seen it before)
        const hasSupervisions = {{ $supervisiList->count() > 0 ? 'true' : 'false' }};

        if (!hasSupervisions) {
            // Show welcome modal after a brief delay for better UX
            setTimeout(() => {
                showWelcomeModal();
            }, 800);
        }
    });

    // Toggle Comments Accordion with smooth animation
    function toggleComments(id) {
        const commentsDiv = document.getElementById('comments-' + id);
        const chevron = document.getElementById('chevron-' + id);
        
        if (commentsDiv.style.maxHeight === '0px' || commentsDiv.style.maxHeight === '') {
            // Open
            commentsDiv.style.maxHeight = commentsDiv.scrollHeight + 'px';
            commentsDiv.style.opacity = '1';
            chevron.style.transform = 'rotate(180deg)';
        } else {
            // Close
            commentsDiv.style.maxHeight = '0px';
            commentsDiv.style.opacity = '0';
            chevron.style.transform = 'rotate(0deg)';
        }
    }

    // Supervisi Confirmation Modal Functions
    function openSupervisiModal() {
        const modal = document.getElementById('supervisiModal');
        const modalContent = document.getElementById('supervisiModalContent');

        modal.style.display = 'flex';

        // Trigger animation after a brief delay
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');

            modalContent.classList.remove('scale-90', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeSupervisiModal() {
        const modal = document.getElementById('supervisiModal');
        const modalContent = document.getElementById('supervisiModalContent');

        // Animate out
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-90', 'opacity-0');

        // Hide after animation
        setTimeout(() => {
            modal.style.display = 'none';
        }, 500);
    }

    // Submit form supervisi
    function submitSupervisiForm() {
        document.getElementById('supervisiForm').submit();
    }

    // Close modal on ESC key for supervisi modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('supervisiModal').style.display === 'flex') {
            closeSupervisiModal();
        }
    });
</script>

<!-- Modal Konfirmasi Supervisi -->
<div id="supervisiModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-[90] flex items-center justify-center p-4 opacity-0 transition-opacity duration-500" style="display: none;" onclick="if(event.target === this) closeSupervisiModal()">
    <div id="supervisiModalContent" class="bg-white dark:bg-gray-800 rounded-[24px] shadow-2xl w-full max-w-md transform scale-90 opacity-0 transition-all duration-500 overflow-hidden" onclick="event.stopPropagation()">

        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-violet-600 dark:from-purple-700 dark:to-violet-700 px-6 py-6 text-center relative">
            <button onclick="closeSupervisiModal()" class="absolute top-4 right-4 w-9 h-9 rounded-xl hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-white mb-2">Mulai Supervisi Baru?</h2>
            <p class="text-purple-100 text-sm">
                Tanggal supervisi tercatat saat submit
            </p>
        </div>

        <!-- Body Content -->
        <div class="p-6">
            <!-- Yang Perlu Disiapkan -->
            <div class="mb-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Yang Perlu Disiapkan:</h3>
                <div class="space-y-2.5">
                    <div class="flex items-start gap-3 p-3.5 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">7 Dokumen</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">(RPP, Silabus, dll)</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3.5 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                        <div class="w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Video & Refleksi</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Dokumentasi pembelajaran</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3.5 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                        <div class="w-5 h-5 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Info Pembelajaran</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Detail proses mengajar</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alur Proses -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 mb-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-2 mb-2.5">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg flex-shrink-0">info</span>
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-200">Alur:</p>
                </div>
                <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800 dark:text-blue-300 ml-6">
                    <li>Upload dokumen</li>
                    <li>Isi info & video</li>
                    <li>Submit review</li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <form id="supervisiForm" action="{{ route('guru.supervisi.store') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <div class="flex gap-3">
                <button onclick="closeSupervisiModal()" class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all text-sm">
                    Batal
                </button>
                <button onclick="submitSupervisiForm()" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl text-sm">
                    Mulai
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Guru Carousel Script -->
<script>
(function() {
    const carouselInner = document.querySelector('.guru-carousel-inner');
    const slides = document.querySelectorAll('.guru-carousel-slide');
    
    if (!carouselInner || slides.length <= 1) return;
    
    let currentSlide = 0;
    const SLIDE_DURATION = 4000; // 4 seconds
    
    function showSlide(index) {
        if (index >= slides.length) index = 0;
        currentSlide = index;
        carouselInner.style.transform = `translateX(-${index * 100}%)`;
    }
    
    function nextSlide() {
        showSlide(currentSlide + 1);
    }
    
    // Start auto-sliding
    setInterval(nextSlide, SLIDE_DURATION);
})();
</script>

@endsection
