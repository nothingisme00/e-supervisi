@extends('layouts.modern')

@section('content')
<!-- Breadcrumb -->
<x-breadcrumb :items="[
    ['label' => 'Dashboard Kepala Sekolah', 'icon' => true]
]" />

<!-- Main Container -->
<div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-xl p-2.5 sm:p-5 shadow-sm">
    <!-- Header Section with Border Bottom -->
    <div class="pb-3 sm:pb-6 mb-3 sm:mb-6 border-b border-gray-200 dark:border-gray-700">
        <!-- Header Row: Title + Buttons -->
        <div class="flex flex-row items-center justify-between gap-2 sm:gap-3 md:gap-5 w-full">
                    <!-- LEFT: Icon + Title -->
                    <div class="flex items-center gap-1.5 sm:gap-2 md:gap-4 flex-shrink min-w-0">
                        <div class="w-7 h-7 sm:w-10 sm:h-10 md:w-14 md:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-violet-600 to-purple-600 dark:from-violet-500 dark:to-purple-500 rounded-md sm:rounded-xl flex items-center justify-center shadow-md sm:shadow-lg flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 md:w-7 md:h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-xs sm:text-base md:text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate">Supervisi Pembelajaran</h3>
                            <p class="text-[9px] sm:text-xs md:text-sm text-gray-500 dark:text-gray-400 hidden sm:block">Monitor dan evaluasi supervisi guru</p>
                        </div>
                    </div>

                    <!-- RIGHT: Buttons -->
                    <div class="flex flex-nowrap items-center gap-1 sm:gap-2 md:gap-2.5 lg:gap-2.5 flex-shrink-0">
                        <!-- Button 1: Tips & Info -->
                        <button onclick="toggleTips()" class="inline-flex items-center justify-center gap-0 lg:gap-2 p-1.5 sm:px-2.5 sm:py-2.5 md:px-3 md:py-2.5 lg:px-4 lg:py-2.5 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 hover:from-blue-100 hover:to-cyan-100 dark:hover:from-blue-900/40 dark:hover:to-cyan-900/40 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 font-semibold rounded sm:rounded-lg transition-all shadow-sm hover:shadow-md text-[9px] sm:text-[11px] lg:text-sm whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 md:w-5 md:h-5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden lg:inline">Tips & Info</span>
                            <svg id="tips-chevron" class="hidden sm:block w-4 h-4 sm:w-5 sm:h-5 md:w-5 md:h-5 lg:w-4 lg:h-4 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Button 2: Panduan -->
                        <button onclick="openPanduanModal()" class="inline-flex items-center justify-center gap-0 lg:gap-2 p-1.5 sm:px-2.5 sm:py-2.5 md:px-3 md:py-2.5 lg:px-4 lg:py-2.5 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/30 dark:to-orange-900/30 hover:from-amber-100 hover:to-orange-100 dark:hover:from-amber-900/40 dark:hover:to-orange-900/40 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 font-semibold rounded sm:rounded-lg transition-all shadow-sm hover:shadow-md text-[9px] sm:text-[11px] lg:text-sm whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 md:w-5 md:h-5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="hidden lg:inline">Panduan</span>
                        </button>
                    </div>
                </div>

                <!-- Tips Accordion Content -->
                <div id="tips-content" class="overflow-hidden transition-all duration-300 ease-in-out mt-2 sm:mt-4" style="max-height: 0; opacity: 0;">
                <div class="px-2 pb-2 sm:px-5 sm:pb-5">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-3">
                        <!-- Tip 1: Monitor Real-time -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-violet-200 dark:border-violet-900/30 shadow-sm hover:shadow-md hover:border-violet-300 dark:hover:border-violet-700 transition-all">
                            <div class="flex items-start gap-2.5">
                                <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-purple-500 rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-violet-900 dark:text-violet-300 mb-0.5">Monitor Real-time</p>
                                    <p class="text-[11px] text-gray-600 dark:text-gray-400 leading-snug">3 kartu menampilkan jumlah supervisi: Perlu Review, Sedang Ditinjau, dan Selesai</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tip 2: Prioritas Review -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-amber-200 dark:border-amber-900/30 shadow-sm hover:shadow-md hover:border-amber-300 dark:hover:border-amber-700 transition-all">
                            <div class="flex items-start gap-2.5">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-amber-900 dark:text-amber-300 mb-0.5">Quick Actions</p>
                                    <p class="text-[11px] text-gray-600 dark:text-gray-400 leading-snug">Klik "Mulai Review" atau "Lanjutkan Review" untuk evaluasi cepat</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tip 3: Panduan Lengkap -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-indigo-200 dark:border-indigo-900/30 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition-all">
                            <div class="flex items-start gap-2.5">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-indigo-900 dark:text-indigo-300 mb-0.5">Baca Panduan</p>
                                    <p class="text-[11px] text-gray-600 dark:text-gray-400 leading-snug">Klik tombol "Panduan" untuk melihat alur review dan semua fitur lengkap</p>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
        </div>
        <!-- End Tips Accordion Content -->
    </div>
    <!-- End Header Section -->

    <!-- Grid Cards: 3 Supervisi Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 sm:gap-4">
        <!-- Card 1: Perlu Review -->
        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-md sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
            <div class="p-3 sm:p-6 border-b-2 border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 sm:gap-4">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white">Perlu Review</h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Menunggu peninjauan</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl sm:text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $supervisiPending }}</div>
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">Supervisi</div>
                    </div>
                </div>
            </div>
            <div class="p-3 sm:p-5 max-h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900/50">
                @if($pendingList->count() > 0)
                <div class="space-y-2 sm:space-y-3">
                    @foreach($pendingList as $supervisi)
                    <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-amber-100 dark:border-amber-900/30 hover:border-amber-300 dark:hover:border-amber-700 hover:shadow-lg transition-all">
                        <div class="flex items-start gap-2 sm:gap-3 mb-2 sm:mb-3">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 bg-gradient-to-br from-amber-500 to-orange-500 rounded-md sm:rounded-lg flex items-center justify-center text-white font-bold text-sm sm:text-base shrink-0 shadow-sm">
                                {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white truncate mb-0.5 sm:mb-1">{{ $supervisi->user->name }}</div>
                                <div class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                                    <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-md sm:rounded-lg font-medium text-[10px] sm:text-sm">{{ $supervisi->user->mata_pelajaran ?? '-' }}</span>
                                    <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-md sm:rounded-lg font-medium text-[10px] sm:text-sm">{{ $supervisi->user->tingkat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2 sm:mb-3">
                            <span class="flex items-center gap-1.5 sm:gap-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $supervisi->updated_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <a href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" class="flex items-center justify-center gap-1.5 sm:gap-2 w-full px-3 py-2 sm:px-4 sm:py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-xs sm:text-sm font-semibold rounded-md sm:rounded-lg transition-all shadow-sm hover:shadow-md">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            </svg>
                            Mulai Review
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <x-empty-state 
                    icon="clock"
                    title="Tidak ada supervisi"
                    description="Belum ada supervisi yang perlu direview saat ini"
                    :compact="true"
                />
                @endif
            </div>
        </div>

        <!-- Card 2: Sedang Ditinjau -->
        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-md sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-indigo-400 to-purple-500"></div>
            <div class="p-3 sm:p-6 border-b-2 border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 sm:gap-4">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white">Sedang Ditinjau</h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Dalam proses review</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl sm:text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $supervisiInProgress }}</div>
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">Supervisi</div>
                    </div>
                </div>
            </div>
            <div class="p-3 sm:p-5 max-h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900/50">
                @if($inProgressList->count() > 0)
                <div class="space-y-2 sm:space-y-3">
                    @foreach($inProgressList as $supervisi)
                    <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-indigo-100 dark:border-indigo-900/30 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-lg transition-all">
                        <div class="flex items-start gap-2 sm:gap-3 mb-2 sm:mb-3">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-md sm:rounded-lg flex items-center justify-center text-white font-bold text-sm sm:text-base shrink-0 shadow-sm">
                                {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white truncate mb-0.5 sm:mb-1">{{ $supervisi->user->name }}</div>
                                <div class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                                    <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-md sm:rounded-lg font-medium text-[10px] sm:text-sm">{{ $supervisi->user->mata_pelajaran ?? '-' }}</span>
                                    <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-md sm:rounded-lg font-medium text-[10px] sm:text-sm">{{ $supervisi->user->tingkat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2 sm:mb-3">
                            <span class="flex items-center gap-1.5 sm:gap-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $supervisi->reviewed_at ? $supervisi->reviewed_at->format('d M Y, H:i') : $supervisi->updated_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <a href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" class="flex items-center justify-center gap-1.5 sm:gap-2 w-full px-3 py-2 sm:px-4 sm:py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-xs sm:text-sm font-semibold rounded-md sm:rounded-lg transition-all shadow-sm hover:shadow-md">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Lanjutkan Review
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <x-empty-state 
                    icon="document"
                    title="Tidak ada supervisi"
                    description="Belum ada supervisi yang sedang ditinjau"
                    :compact="true"
                />
                @endif
            </div>
        </div>

        <!-- Card 3: Telah Selesai -->
        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-md sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-emerald-400 to-green-500"></div>
            <div class="p-3 sm:p-6 border-b-2 border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 sm:gap-4">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-emerald-100 to-green-100 dark:from-emerald-900/30 dark:to-green-900/30 rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white">Telah Selesai</h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Review selesai</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $supervisiReviewed }}</div>
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">Supervisi</div>
                    </div>
                </div>
            </div>
            <div class="p-3 sm:p-5 max-h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900/50">
                @if($completedList->count() > 0)
                <div class="space-y-2 sm:space-y-3">
                    @foreach($completedList as $supervisi)
                    <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-emerald-100 dark:border-emerald-900/30 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-lg transition-all">
                        <div class="flex items-start gap-2 sm:gap-3 mb-2 sm:mb-3">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 bg-gradient-to-br from-emerald-500 to-green-500 rounded-md sm:rounded-lg flex items-center justify-center text-white font-bold text-sm sm:text-base shrink-0 shadow-sm">
                                {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white truncate mb-0.5 sm:mb-1">{{ $supervisi->user->name }}</div>
                                <div class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                                    <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-md sm:rounded-lg font-medium text-[10px] sm:text-sm">{{ $supervisi->user->mata_pelajaran ?? '-' }}</span>
                                    <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-md sm:rounded-lg font-medium text-[10px] sm:text-sm">{{ $supervisi->user->tingkat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2 sm:mb-3">
                            <span class="flex items-center gap-1.5 sm:gap-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $supervisi->reviewed_at ? $supervisi->reviewed_at->format('d M Y, H:i') : $supervisi->updated_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <a href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" class="flex items-center justify-center gap-1.5 sm:gap-2 w-full px-3 py-2 sm:px-4 sm:py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white text-xs sm:text-sm font-semibold rounded-md sm:rounded-lg transition-all shadow-sm hover:shadow-md">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Lihat Detail
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <x-empty-state 
                    icon="check" 
                    title="Tidak ada supervisi" 
                    description="Belum ada supervisi yang telah selesai" 
                    :compact="true" 
                />
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End Main Container -->

<!-- Welcome Modal for First Time Users -->
<div id="welcomeModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[80] items-center justify-center p-4 opacity-0 transition-opacity duration-500" style="display: none;">
    <div id="welcomeModalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full transform scale-90 opacity-0 transition-all duration-500">
        <!-- Decorative gradient background -->
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500 via-purple-500 to-indigo-500 opacity-10"></div>
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-violet-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>

            <!-- Content -->
            <div class="relative px-8 py-8">
                <!-- Icon -->
                <div class="w-20 h-20 bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>

                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-3">
                    Selamat Datang, Kepala Sekolah!
                </h3>

                <!-- Description -->
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-6 leading-relaxed">
                    Sistem E-Supervisi siap membantu Anda mengevaluasi dan memberikan feedback kepada guru dengan lebih efektif
                </p>

                <!-- Benefits List -->
                <div class="space-y-3 mb-6 bg-gray-50 dark:bg-gray-900/30 rounded-xl p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-violet-100 dark:bg-violet-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-700 dark:text-gray-300">Review supervisi guru secara terorganisir</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-violet-100 dark:bg-violet-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-700 dark:text-gray-300">Berikan feedback konstruktif dengan mudah</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-violet-100 dark:bg-violet-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-700 dark:text-gray-300">Lacak progres evaluasi dengan real-time</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="openPanduanFromWelcome()" class="flex-1 px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl text-sm">
                        Baca Panduan Sekarang
                    </button>
                    <button onclick="closeWelcomeModal()" class="flex-1 px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all text-sm">
                        Nanti Saja
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Panduan Penggunaan -->
    <div id="panduanModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 transition-opacity duration-500" style="display: none;" onclick="if(event.target === this) closePanduanModal()">
        <div id="panduanModalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden transform scale-90 opacity-0 transition-all duration-500" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-violet-500 to-purple-500 px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Panduan Penggunaan Sistem</h3>
                        <p class="text-sm text-violet-100">Langkah-langkah menggunakan E-Supervisi</p>
                    </div>
                </div>
                <button onclick="closePanduanModal()" class="w-10 h-10 rounded-lg hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
                <!-- Introduction -->
                <div class="mb-6 p-4 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 rounded-xl border border-violet-200 dark:border-violet-800">
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        Dashboard Kepala Sekolah adalah pusat kendali evaluasi supervisi guru. Di sini Anda dapat mereview supervisi, memberikan feedback konstruktif, dan memonitor progress pembelajaran secara real-time.
                    </p>
                </div>

                <!-- Journey Progress Bar Header -->
                <div class="mb-6 text-center">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Panduan Lengkap Fitur Dashboard</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pelajari semua fitur yang tersedia untuk Anda</p>
                </div>

                <!-- Timeline Journey with connecting lines -->
                <div class="relative">
                    <!-- Vertical Timeline Line -->
                    <div class="absolute left-[26px] top-10 bottom-10 w-0.5 bg-gradient-to-b from-violet-400 via-indigo-400 via-blue-400 to-emerald-400 dark:from-violet-500 dark:via-indigo-500 dark:via-blue-500 dark:to-emerald-500"></div>

                    <div class="space-y-4">
                    <!-- Step 1: Cek Supervisi Masuk -->
                    <div class="relative group">
                        <!-- Timeline Dot -->
                        <div class="absolute left-0 top-5 w-14 h-14 bg-gradient-to-br from-violet-500 to-violet-600 dark:from-violet-400 dark:to-violet-500 rounded-full flex items-center justify-center shadow-lg ring-4 ring-white dark:ring-gray-800 group-hover:scale-110 transition-transform z-10">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>

                        <!-- Content Card -->
                        <div class="ml-20 bg-gradient-to-br from-violet-50 to-violet-100/30 dark:from-violet-900/20 dark:to-violet-900/10 rounded-xl p-4 border-2 border-violet-200 dark:border-violet-800 hover:border-violet-400 dark:hover:border-violet-600 hover:shadow-lg transition-all">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 bg-violet-600 dark:bg-violet-500 text-white text-xs font-bold rounded-full mb-1.5">LANGKAH 1</span>
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">Cek Supervisi Masuk</h4>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mb-2.5">Lihat daftar supervisi yang diajukan guru pada card <strong>"Perlu Review"</strong>. Supervisi baru akan muncul dengan status "Submitted".</p>
                            <div class="flex items-center gap-2 p-2.5 bg-white/80 dark:bg-gray-800/50 rounded-lg">
                                <svg class="w-4 h-4 text-violet-600 dark:text-violet-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span class="text-xs text-gray-700 dark:text-gray-300 font-medium">Supervisi baru akan muncul di bagian atas list</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Mulai Review -->
                    <div class="relative group">
                        <!-- Timeline Dot -->
                        <div class="absolute left-0 top-5 w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-400 dark:to-indigo-500 rounded-full flex items-center justify-center shadow-lg ring-4 ring-white dark:ring-gray-800 group-hover:scale-110 transition-transform z-10">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <!-- Content Card -->
                        <div class="ml-20 bg-gradient-to-br from-indigo-50 to-indigo-100/30 dark:from-indigo-900/20 dark:to-indigo-900/10 rounded-xl p-4 border-2 border-indigo-200 dark:border-indigo-800 hover:border-indigo-400 dark:hover:border-indigo-600 hover:shadow-lg transition-all">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 bg-indigo-600 dark:bg-indigo-500 text-white text-xs font-bold rounded-full mb-1.5">LANGKAH 2</span>
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">Klik "Mulai Review"</h4>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mb-2.5">Tekan tombol <strong>"Mulai Review"</strong> untuk memulai proses evaluasi. Status supervisi akan berubah menjadi "Under Review".</p>
                            <div class="flex items-center gap-2 p-2.5 bg-white/80 dark:bg-gray-800/50 rounded-lg">
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs text-gray-700 dark:text-gray-300 font-medium">Supervisi akan pindah ke daftar "Sedang Ditinjau"</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Review Dokumen -->
                    <div class="relative group">
                        <!-- Timeline Dot -->
                        <div class="absolute left-0 top-5 w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-400 dark:to-blue-500 rounded-full flex items-center justify-center shadow-lg ring-4 ring-white dark:ring-gray-800 group-hover:scale-110 transition-transform z-10">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                        <!-- Content Card -->
                        <div class="ml-20 bg-gradient-to-br from-blue-50 to-blue-100/30 dark:from-blue-900/20 dark:to-blue-900/10 rounded-xl p-4 border-2 border-blue-200 dark:border-blue-800 hover:border-blue-400 dark:hover:border-blue-600 hover:shadow-lg transition-all">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 bg-blue-600 dark:bg-blue-500 text-white text-xs font-bold rounded-full mb-1.5">LANGKAH 3</span>
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">Review Dokumen Pembelajaran</h4>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mb-2.5">Periksa kelengkapan dan kualitas dokumen yang diupload guru:</p>
                            <div class="space-y-2">
                                <div class="flex items-start gap-2 p-2 bg-white/80 dark:bg-gray-800/50 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xs text-gray-700 dark:text-gray-300 font-medium"><strong>Dokumen Evaluasi</strong> - 7 dokumen wajib</span>
                                </div>
                                <div class="flex items-start gap-2 p-2 bg-white/80 dark:bg-gray-800/50 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xs text-gray-700 dark:text-gray-300 font-medium"><strong>Proses Pembelajaran</strong> - Video & refleksi</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Berikan Feedback -->
                    <div class="relative group">
                        <!-- Timeline Dot -->
                        <div class="absolute left-0 top-5 w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-400 dark:to-emerald-500 rounded-full flex items-center justify-center shadow-lg ring-4 ring-white dark:ring-gray-800 group-hover:scale-110 transition-transform z-10">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>

                        <!-- Content Card -->
                        <div class="ml-20 bg-gradient-to-br from-emerald-50 to-emerald-100/30 dark:from-emerald-900/20 dark:to-emerald-900/10 rounded-xl p-4 border-2 border-emerald-200 dark:border-emerald-800 hover:border-emerald-400 dark:hover:border-emerald-600 hover:shadow-lg transition-all">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 bg-emerald-600 dark:bg-emerald-500 text-white text-xs font-bold rounded-full mb-1.5">LANGKAH 4</span>
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">Berikan Feedback</h4>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mb-2.5">Tulis catatan evaluasi yang konstruktif dan jelas. Pilih salah satu opsi:</p>
                            <div class="space-y-2">
                                <div class="flex items-start gap-2 p-2.5 bg-green-50 dark:bg-green-900/30 rounded-lg border border-green-200 dark:border-green-800">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white">✅ Setujui & Selesai</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Jika sudah memenuhi standar</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2 p-2.5 bg-amber-50 dark:bg-amber-900/30 rounded-lg border border-amber-200 dark:border-amber-800">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white">🔄 Minta Revisi</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Dengan catatan spesifik untuk perbaikan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Separator -->
                <div class="my-8 border-t-2 border-gray-200 dark:border-gray-700"></div>

                <!-- Fitur Penting Heading -->
                <div class="mb-4">
                    <h4 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        Fitur-Fitur Penting
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Klik untuk memperluas dan melihat detail setiap fitur</p>
                </div>

                <!-- Accordion Container -->
                <div class="space-y-3 mb-6">
                    <!-- Accordion 1: Review Dokumen Pembelajaran -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                        <button onclick="toggleAccordion('kepsek-feature-1')" class="w-full flex items-center justify-between p-4 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-600 dark:bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-left">Review Dokumen Pembelajaran</h4>
                            </div>
                            <svg id="chevron-kepsek-feature-1" class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="kepsek-feature-1" class="overflow-hidden transition-all duration-300" style="max-height: 0;">
                            <div class="p-4 bg-white dark:bg-gray-800 border-t border-indigo-200 dark:border-indigo-800">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Tinjau dokumen pembelajaran yang diupload oleh guru.</p>
                                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 ml-4 list-disc">
                                    <li><strong>7 Dokumen Wajib:</strong> CP, ATP, Kalender, Prota, Prosem, Modul Ajar, Bahan Ajar</li>
                                    <li><strong>Preview Langsung:</strong> Buka dokumen di browser tanpa download</li>
                                    <li><strong>Video Pembelajaran:</strong> Tonton video yang diupload guru</li>
                                    <li><strong>Baca Refleksi:</strong> Tinjau jawaban 5 pertanyaan refleksi pembelajaran</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Accordion 2: Berikan Feedback & Evaluasi -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                        <button onclick="toggleAccordion('kepsek-feature-2')" class="w-full flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-600 dark:bg-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-left">Berikan Feedback & Evaluasi</h4>
                            </div>
                            <svg id="chevron-kepsek-feature-2" class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="kepsek-feature-2" class="overflow-hidden transition-all duration-300" style="max-height: 0;">
                            <div class="p-4 bg-white dark:bg-gray-800 border-t border-emerald-200 dark:border-emerald-800">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Berikan penilaian dan feedback untuk pengembangan guru.</p>
                                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 ml-4 list-disc">
                                    <li><strong>Tulis Catatan:</strong> Berikan feedback yang konstruktif dan membangun</li>
                                    <li><strong>Setujui & Selesai:</strong> Klik tombol hijau jika sudah memenuhi standar</li>
                                    <li><strong>Minta Revisi:</strong> Klik tombol kuning untuk meminta perbaikan dengan catatan</li>
                                    <li><strong>Badge "Kepsek":</strong> Komentar Anda ditandai khusus untuk guru</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Accordion 3: Dashboard Monitoring -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                        <button onclick="toggleAccordion('kepsek-feature-3')" class="w-full flex items-center justify-between p-4 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-600 dark:bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-left">Dashboard Monitoring</h4>
                            </div>
                            <svg id="chevron-kepsek-feature-3" class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="kepsek-feature-3" class="overflow-hidden transition-all duration-300" style="max-height: 0;">
                            <div class="p-4 bg-white dark:bg-gray-800 border-t border-amber-200 dark:border-amber-800">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Monitor status semua supervisi guru secara real-time.</p>
                                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 ml-4 list-disc">
                                    <li><strong>Perlu Review (Kuning):</strong> Supervisi baru menunggu review Anda</li>
                                    <li><strong>Sedang Ditinjau (Ungu):</strong> Supervisi yang sedang dalam proses review</li>
                                    <li><strong>Telah Selesai (Hijau):</strong> Supervisi yang sudah selesai direview</li>
                                    <li><strong>Counter Real-time:</strong> Angka menunjukkan jumlah per kategori</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Note -->
                <div class="mt-6 pt-5 border-t-2 border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center gap-2 p-4 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 rounded-xl border border-violet-200 dark:border-violet-800">
                        <svg class="w-5 h-5 text-violet-600 dark:text-violet-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Tip:</strong> Feedback konstruktif dan tepat waktu membantu guru berkembang lebih baik!</p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="border-t-2 border-gray-300 dark:border-gray-600 px-6 py-4 bg-gray-50 dark:bg-gray-900/50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                <button onclick="closePanduanModal()" class="w-full px-6 py-3.5 bg-gradient-to-r from-violet-500 to-purple-500 hover:from-violet-600 hover:to-purple-600 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl text-base">
                    Mengerti, Tutup Panduan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle Tips Accordion
function toggleTips() {
    const content = document.getElementById('tips-content');
    const chevron = document.getElementById('tips-chevron');

    if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
        // Expand
        content.style.maxHeight = content.scrollHeight + 'px';
        content.style.opacity = '1';
        chevron.style.transform = 'rotate(180deg)';
    } else {
        // Collapse
        content.style.maxHeight = '0px';
        content.style.opacity = '0';
        chevron.style.transform = 'rotate(0deg)';
    }
}

// Toggle Accordion in Modal Panduan
function toggleAccordion(id) {
    const content = document.getElementById(id);
    const chevron = document.getElementById('chevron-' + id);

    if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
        // Expand
        content.style.maxHeight = content.scrollHeight + 'px';
        chevron.style.transform = 'rotate(180deg)';
    } else {
        // Collapse
        content.style.maxHeight = '0px';
        chevron.style.transform = 'rotate(0deg)';
    }
}

// Welcome Modal Functions
function showWelcomeModal() {
    const modal = document.getElementById('welcomeModal');
    const modalContent = document.getElementById('welcomeModalContent');

    modal.style.display = 'flex';

    // Trigger animation after a brief delay
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');

        modalContent.classList.remove('scale-90', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 50);
}

function closeWelcomeModal() {
    const modal = document.getElementById('welcomeModal');
    const modalContent = document.getElementById('welcomeModalContent');

    // Save to localStorage that user has seen the welcome modal
    localStorage.setItem('hasSeenKepalaWelcome', 'true');

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

function openPanduanFromWelcome() {
    closeWelcomeModal();

    // Open panduan modal after welcome modal closes
    setTimeout(() => {
        openPanduanModal();
    }, 600);
}

// Show welcome modal on first visit
document.addEventListener('DOMContentLoaded', function() {
    // Check if user has seen the welcome modal before
    const hasSeenWelcome = localStorage.getItem('hasSeenKepalaWelcome');

    if (!hasSeenWelcome) {
        // Show welcome modal after a brief delay
        setTimeout(() => {
            showWelcomeModal();
        }, 800);
    }
});

// Panduan Modal Functions
function openPanduanModal() {
    const modal = document.getElementById('panduanModal');
    const modalContent = document.getElementById('panduanModalContent');

    modal.style.display = 'flex';

    // Trigger animation after a brief delay
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');

        modalContent.classList.remove('scale-90', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 50);
}

function closePanduanModal() {
    const modal = document.getElementById('panduanModal');
    const modalContent = document.getElementById('panduanModalContent');

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
</script>
@endsection
