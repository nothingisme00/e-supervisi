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
                        <button onclick="openTipsModal()" class="inline-flex items-center justify-center gap-0 lg:gap-2 p-1.5 sm:px-2.5 sm:py-2.5 md:px-3 md:py-2.5 lg:px-4 lg:py-2.5 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 hover:from-blue-100 hover:to-cyan-100 dark:hover:from-blue-900/40 dark:hover:to-cyan-900/40 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 font-semibold rounded sm:rounded-lg transition-all shadow-sm hover:shadow-md text-[9px] sm:text-[11px] lg:text-sm whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 md:w-5 md:h-5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden lg:inline">Tips & Info</span>
                        </button>

                        <!-- Button 2: Panduan -->
                        <button onclick="openGuideModal()" class="inline-flex items-center justify-center gap-0 lg:gap-2 p-1.5 sm:px-2.5 sm:py-2.5 md:px-3 md:py-2.5 lg:px-4 lg:py-2.5 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/30 dark:to-orange-900/30 hover:from-amber-100 hover:to-orange-100 dark:hover:from-amber-900/40 dark:hover:to-orange-900/40 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 font-semibold rounded sm:rounded-lg transition-all shadow-sm hover:shadow-md text-[9px] sm:text-[11px] lg:text-sm whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 md:w-5 md:h-5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="hidden lg:inline">Panduan</span>
                        </button>
                    </div>
                </div>
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

@endsection
