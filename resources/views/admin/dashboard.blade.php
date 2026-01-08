@extends('layouts.modern')

@section('page-title', 'Dashboard Admin')

@section('content')
<!-- Main Container -->
<div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-xl p-2.5 sm:p-5 shadow-sm">

    <!-- Header Section with Border Bottom - Hidden on mobile -->
    <div class="hidden sm:block pb-6 mb-6 border-b-2 border-gray-200 dark:border-gray-600">
        <!-- Header Row: Title + Buttons -->
        <div class="flex flex-row items-center justify-between gap-2 sm:gap-3 md:gap-5 w-full">
            <!-- LEFT: Icon + Title - Hidden on mobile -->
            <div class="hidden sm:flex items-center gap-2 md:gap-4 flex-shrink min-w-0">
                <div class="w-10 h-10 md:w-14 md:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-500 dark:to-purple-500 rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 md:w-7 md:h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-base md:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white truncate">Manajemen E-Supervisi</h3>
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Kelola pengguna dan monitor supervisi</p>
                </div>
            </div>

            <!-- RIGHT: Buttons (Hidden on mobile, shown on md+) -->
            <div class="hidden md:flex flex-nowrap items-center gap-1 sm:gap-2 md:gap-2.5 lg:gap-2.5 flex-shrink-0">
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

    <!-- Quick Actions - Hidden on mobile, users access via bottom nav -->
    <div class="hidden sm:grid grid-cols-2 gap-4 mb-6">
        <a href="{{ route('admin.users.create') }}" class="group flex items-center gap-2.5 sm:gap-4 p-2.5 sm:p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 hover:from-green-100 hover:to-emerald-100 dark:hover:from-green-900/30 dark:hover:to-emerald-900/30 rounded-lg sm:rounded-xl border-2 border-green-200 dark:border-green-800 hover:border-green-300 dark:hover:border-green-700 transition-all shadow-sm hover:shadow-md">
            <div class="w-9 h-9 sm:w-12 sm:h-12 bg-green-600 dark:bg-green-500 rounded-lg sm:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-sm sm:text-base text-gray-900 dark:text-white">Tambah User</h3>
                <p class="text-[10px] sm:text-sm text-gray-600 dark:text-gray-400">Buat akun guru atau kepala sekolah</p>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-2.5 sm:gap-4 p-2.5 sm:p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 hover:from-blue-100 hover:to-indigo-100 dark:hover:from-blue-900/30 dark:hover:to-indigo-900/30 rounded-lg sm:rounded-xl border-2 border-blue-200 dark:border-blue-800 hover:border-blue-300 dark:hover:border-blue-700 transition-all shadow-sm hover:shadow-md">
            <div class="w-9 h-9 sm:w-12 sm:h-12 bg-blue-600 dark:bg-blue-500 rounded-lg sm:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-sm sm:text-base text-gray-900 dark:text-white">Kelola Pengguna</h3>
                <p class="text-[10px] sm:text-sm text-gray-600 dark:text-gray-400">Lihat dan edit data pengguna</p>
            </div>
        </a>
    </div>

    <!-- Grid Cards: 3 Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 sm:gap-4">

        <!-- Card 1: Data Guru -->
        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-md sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-blue-400 to-indigo-500"></div>
            <div class="p-3 sm:p-6 border-b-2 border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 sm:gap-4">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white">Data Guru</h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Guru terdaftar</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalGuru }}</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">Guru</div>
                    </div>
                </div>
            </div>
            <div class="p-2.5 sm:p-4 max-h-96 overflow-y-auto">
                @forelse($guruList as $guru)
                <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-white dark:bg-gray-800 rounded-md sm:rounded-lg mb-2 sm:mb-3 last:mb-0 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-md sm:rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm shrink-0">
                        {{ strtoupper(substr($guru->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white truncate">{{ $guru->name }}</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 truncate">{{ $guru->nik }}</div>
                        @if($guru->tingkat)
                        <div class="text-[10px] sm:text-xs text-blue-600 dark:text-blue-400 font-medium mt-0.5">{{ $guru->tingkat }}</div>
                        @endif
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white">{{ $guru->total_supervisi }}</div>
                        <div class="text-[9px] sm:text-[10px] text-gray-500 dark:text-gray-400">Supervisi</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 sm:py-12 text-gray-400 dark:text-gray-500">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-xs sm:text-sm font-medium">Belum ada guru terdaftar</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Card 2: Dalam Proses -->
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
                            <h3 class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white">Dalam Proses</h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Sedang berjalan</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl sm:text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $supervisiPending + $supervisiInProgress }}</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">Supervisi</div>
                    </div>
                </div>
            </div>
            <div class="p-2.5 sm:p-4 max-h-96 overflow-y-auto">
                @forelse($supervisiUnderReviewList as $supervisi)
                <div class="flex items-start gap-2 sm:gap-3 p-2 sm:p-3 bg-white dark:bg-gray-800 rounded-md sm:rounded-lg mb-2 sm:mb-3 last:mb-0 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-md sm:rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm shrink-0">
                        {{ strtoupper(substr($supervisi->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 truncate">{{ $supervisi->user->nik }}</div>
                        <div class="flex items-center gap-2 mt-1">
                            @if($supervisi->status === 'submitted')
                            <span class="px-1.5 py-0.5 sm:px-2 sm:py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded text-[8px] sm:text-[10px] font-medium">Submitted</span>
                            @else
                            <span class="px-1.5 py-0.5 sm:px-2 sm:py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded text-[8px] sm:text-[10px] font-medium">Review</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-[10px] sm:text-xs text-gray-400 dark:text-gray-500 shrink-0">
                        {{ $supervisi->updated_at->diffForHumans() }}
                    </div>
                </div>
                @empty
                <div class="text-center py-8 sm:py-12 text-gray-400 dark:text-gray-500">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-xs sm:text-sm font-medium">Tidak ada supervisi dalam proses</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Card 3: Selesai -->
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
                            <h3 class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white">Selesai</h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Review selesai</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $supervisiCompleted }}</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">Supervisi</div>
                    </div>
                </div>
            </div>
            <div class="p-2.5 sm:p-4 max-h-96 overflow-y-auto">
                @forelse($supervisiCompletedList as $supervisi)
                <div class="flex items-start gap-2 sm:gap-3 p-2 sm:p-3 bg-white dark:bg-gray-800 rounded-md sm:rounded-lg mb-2 sm:mb-3 last:mb-0 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-700">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-emerald-600 to-green-600 rounded-md sm:rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm shrink-0">
                        {{ strtoupper(substr($supervisi->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 truncate">{{ $supervisi->user->nik }}</div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-1.5 py-0.5 sm:px-2 sm:py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded text-[8px] sm:text-[10px] font-medium">Completed</span>
                        </div>
                    </div>
                    <div class="text-[10px] sm:text-xs text-gray-400 dark:text-gray-500 shrink-0">
                        {{ $supervisi->reviewed_at ? $supervisi->reviewed_at->diffForHumans() : '-' }}
                    </div>
                </div>
                @empty
                <div class="text-center py-8 sm:py-12 text-gray-400 dark:text-gray-500">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs sm:text-sm font-medium">Belum ada supervisi yang selesai</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection
