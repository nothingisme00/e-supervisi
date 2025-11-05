@extends('layouts.modern')

@section('page-title', 'Dashboard Admin')

@section('content')

<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Dashboard Admin</h1>
            <p class="text-gray-600 dark:text-gray-400">Selamat datang, {{ Auth::user()->name }}</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</div>
        </div>
    </div>
</div>

<!-- Statistics Cards - Enhanced with Accent Colors & Lines -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Total Pengguna -->
    <div class="relative bg-white dark:bg-gray-800 rounded-xl p-5 border border-slate-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all shadow-sm hover:shadow-md overflow-hidden group">
        <!-- Top accent line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
        <!-- Side accent line (visible on hover) -->
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengguna</span>
            <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-lg flex items-center justify-center ring-2 ring-blue-100 dark:ring-blue-900/50 group-hover:ring-blue-200 dark:group-hover:ring-blue-800 transition-all">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ $totalUsers }}</div>
        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-100 dark:border-gray-700">
            <div class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></div>
            <span class="font-medium text-blue-600 dark:text-blue-400">{{ $totalGuru }}</span>
            <span class="ml-1">guru terdaftar</span>
        </div>
    </div>

    <!-- Perlu Review -->
    <a href="{{ route('admin.supervisi.index', ['status' => 'submitted']) }}" class="relative bg-white dark:bg-gray-800 rounded-xl p-5 border border-slate-200 dark:border-gray-700 hover:border-amber-300 dark:hover:border-amber-600 transition-all shadow-sm hover:shadow-md overflow-hidden group cursor-pointer">
        <!-- Top accent line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
        <!-- Side accent line (visible on hover) -->
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-amber-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Perlu Review</span>
            <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 rounded-lg flex items-center justify-center ring-2 ring-amber-100 dark:ring-amber-900/50 group-hover:ring-amber-200 dark:group-hover:ring-amber-800 transition-all">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ $supervisiSubmitted }}</div>
        <div class="flex items-center text-xs pt-2 border-t border-gray-100 dark:border-gray-700">
            <div class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></div>
            <span class="font-medium text-amber-600 dark:text-amber-400">
                @if($supervisiSubmitted > 0)
                Segera tindak lanjuti
                @else
                Semua tertangani
                @endif
            </span>
        </div>
    </a>

    <!-- Sedang Review -->
    <a href="{{ route('admin.supervisi.index', ['status' => 'under_review']) }}" class="relative bg-white dark:bg-gray-800 rounded-xl p-5 border border-slate-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all shadow-sm hover:shadow-md overflow-hidden group cursor-pointer">
        <!-- Top accent line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
        <!-- Side accent line (visible on hover) -->
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-indigo-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Sedang Review</span>
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-lg flex items-center justify-center ring-2 ring-indigo-100 dark:ring-indigo-900/50 group-hover:ring-indigo-200 dark:group-hover:ring-indigo-800 transition-all">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ $supervisiUnderReview }}</div>
        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-100 dark:border-gray-700">
            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-2"></div>
            <span>Dalam proses review</span>
        </div>
    </a>

    <!-- Selesai -->
    <a href="{{ route('admin.supervisi.index', ['status' => 'completed']) }}" class="relative bg-white dark:bg-gray-800 rounded-xl p-5 border border-slate-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-600 transition-all shadow-sm hover:shadow-md overflow-hidden group cursor-pointer">
        <!-- Top accent line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-green-500"></div>
        <!-- Side accent line (visible on hover) -->
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-emerald-500 to-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</span>
            <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-green-100 dark:from-emerald-900/30 dark:to-green-900/30 rounded-lg flex items-center justify-center ring-2 ring-emerald-100 dark:ring-emerald-900/50 group-hover:ring-emerald-200 dark:group-hover:ring-emerald-800 transition-all">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ $supervisiCompleted }}</div>
        <div class="flex items-center text-xs pt-2 border-t border-gray-100 dark:border-gray-700">
            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></div>
            <span class="font-medium text-emerald-600 dark:text-emerald-400">
                @if($totalSupervisi > 0)
                {{ number_format(($supervisiCompleted / $totalSupervisi) * 100, 0) }}% dari total
                @else
                Belum ada data
                @endif
            </span>
        </div>
    </a>
</div>

<!-- Quick Actions -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('admin.users.index') }}" class="flex-1 inline-flex items-center justify-center px-5 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-xl border border-gray-300 dark:border-gray-600 transition-all shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Kelola Pengguna
        </a>
        <a href="{{ route('admin.users.create') }}" class="flex-1 inline-flex items-center justify-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-all shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah User Baru
        </a>
        <button onclick="openGuideModal()" class="flex-1 inline-flex items-center justify-center px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Panduan Admin
        </button>
    </div>
</div>

<!-- Content Grid - Clean Layout -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    
    <!-- Data Guru - Simplified -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-lg transition-all">
        <!-- Header with subtle gradient and accent line -->
        <div class="relative bg-gradient-to-r from-indigo-500/90 to-purple-500/90 px-6 py-5">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-400 to-purple-400"></div>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Data Guru
                    </h2>
                    <p class="text-xs text-indigo-100 mt-1">Daftar guru dan aktivitas supervisi</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full border border-white/30">
                    <span class="text-sm font-bold text-white">{{ $totalGuru }}</span>
                    <span class="text-xs text-indigo-100 ml-1">guru</span>
                </div>
            </div>
            <!-- Decorative bottom wave -->
            <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
        </div>
        
        <!-- Body with subtle background and accent lines -->
        <div class="p-6 bg-gradient-to-b from-indigo-50/30 to-white dark:from-gray-800 dark:to-gray-800">
            <div class="space-y-3 max-h-[600px] overflow-y-auto">
            @forelse($guruList as $guru)
            <div class="relative flex items-center gap-3 p-3.5 bg-white dark:bg-gray-700 rounded-xl hover:bg-indigo-50/50 dark:hover:bg-gray-600 transition-all group border border-slate-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-600 shadow-sm hover:shadow-md">
                <!-- Left accent line -->
                <div class="absolute left-0 top-3 bottom-3 w-1 bg-gradient-to-b from-indigo-400 to-purple-400 rounded-r-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold shrink-0 shadow-md ring-2 ring-indigo-100 dark:ring-indigo-900 group-hover:ring-indigo-200 transition-all">
                    {{ strtoupper(substr($guru->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-gray-900 dark:text-white truncate">{{ $guru->name }}</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        <span class="truncate">{{ $guru->nik }}</span>
                        @if($guru->last_login_at)
                        <span>•</span>
                        <span class="truncate flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $guru->last_login_at->diffForHumans() }}
                        </span>
                        @else
                        <span class="text-gray-400 italic">Belum login</span>
                        @endif
                    </div>
                    @if($guru->tingkat || $guru->mata_pelajaran)
                    <div class="flex items-center gap-1.5 mt-1.5">
                        @if($guru->tingkat)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                            <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            {{ $guru->tingkat }}
                        </span>
                        @endif
                        @if($guru->mata_pelajaran)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                            <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ $guru->mata_pelajaran }}
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="shrink-0 text-right">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $guru->total_supervisi }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">supervisi</div>
                    @if($guru->total_supervisi > 0)
                    <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                        @if($guru->supervisi_completed > 0)
                        <span class="text-emerald-600 dark:text-emerald-400">✓ {{ $guru->supervisi_completed }} selesai</span>
                        @endif
                        @if($guru->supervisi_submitted > 0)
                        <span class="text-amber-600 dark:text-amber-400">⏰ {{ $guru->supervisi_submitted }} menunggu</span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-sm">Belum ada data guru</p>
            </div>
            @endforelse
            </div>
        </div>
    </div>

    <!-- Perlu Review & Sedang Review - Combined -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-lg transition-all">
        <!-- Header with subtle gradient and accent line -->
        <div class="relative bg-gradient-to-r from-amber-500/90 to-orange-500/90 px-6 py-5">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Dalam Proses
                    </h2>
                    <p class="text-xs text-amber-100 mt-1">Supervisi yang memerlukan tindakan</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full border border-white/30">
                    <span class="text-sm font-bold text-white">{{ $supervisiUnderReviewList->count() }}</span>
                    <span class="text-xs text-amber-100 ml-1">item</span>
                </div>
            </div>
            <!-- Decorative bottom wave -->
            <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
        </div>
        
        <!-- Body with subtle background and accent lines -->
        <div class="p-6 bg-gradient-to-b from-amber-50/30 to-white dark:from-gray-800 dark:to-gray-800">
            <div class="space-y-3 max-h-[600px] overflow-y-auto">
            @forelse($supervisiUnderReviewList->sortByDesc('created_at') as $supervisi)
            <div class="relative p-3.5 bg-white dark:bg-gray-700 rounded-xl hover:bg-amber-50/50 dark:hover:bg-gray-600 transition-all border border-slate-200 dark:border-gray-600 hover:border-amber-300 dark:hover:border-amber-600 shadow-sm hover:shadow-md group">
                <!-- Left accent line with dynamic color -->
                <div class="absolute left-0 top-3 bottom-3 w-1 @if($supervisi->status == 'submitted') bg-gradient-to-b from-amber-400 to-orange-400 @else bg-gradient-to-b from-indigo-400 to-purple-400 @endif rounded-r-full"></div>
                
                <div class="flex items-start justify-between gap-3 mb-2 ml-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="font-medium text-gray-900 dark:text-white truncate">{{ $supervisi->user->name ?? 'User Tidak Ditemukan' }}</div>
                            @if($supervisi->status == 'submitted')
                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-md">PERLU REVIEW</span>
                            @else
                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-md">SEDANG REVIEW</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            <span class="font-medium">{{ $supervisi->mapel ?? '-' }}</span>
                            @if($supervisi->tingkat)
                            <span class="mx-1">•</span>
                            <span>{{ $supervisi->tingkat }}</span>
                            @endif
                        </div>
                        @if($supervisi->user && ($supervisi->user->tingkat || $supervisi->user->mata_pelajaran))
                        <div class="flex items-center gap-1.5 mt-1.5">
                            @if($supervisi->user->tingkat)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                {{ $supervisi->user->tingkat }}
                            </span>
                            @endif
                            @if($supervisi->user->mata_pelajaran)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300">
                                <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ $supervisi->user->mata_pelajaran }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $supervisi->created_at->format('d M Y') }}</span>
                        <span class="text-gray-400 mx-1">•</span>
                        <span class="font-medium">{{ $supervisi->created_at->diffForHumans() }}</span>
                    </div>
                    @if($supervisi->reviewed_by)
                    <div class="flex items-center gap-1 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="truncate">{{ $supervisi->reviewer->name ?? '-' }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm">Tidak ada supervisi dalam proses</p>
            </div>
            @endforelse
            </div>
        </div>
    </div>

    <!-- Supervisi Selesai - Simplified -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-lg transition-all">
        <!-- Header with subtle gradient and accent line -->
        <div class="relative bg-gradient-to-r from-emerald-500/90 to-green-500/90 px-6 py-5">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 to-green-400"></div>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Selesai
                    </h2>
                    <p class="text-xs text-emerald-100 mt-1">Supervisi yang telah diselesaikan</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full border border-white/30">
                    <span class="text-sm font-bold text-white">{{ $supervisiCompletedList->count() }}</span>
                    <span class="text-xs text-emerald-100 ml-1">selesai</span>
                </div>
            </div>
            <!-- Decorative bottom wave -->
            <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
        </div>
        
        <!-- Body with subtle background and accent lines -->
        <div class="p-6 bg-gradient-to-b from-emerald-50/30 to-white dark:from-gray-800 dark:to-gray-800">
            <div class="space-y-3 max-h-[600px] overflow-y-auto">
            @forelse($supervisiCompletedList as $supervisi)
            <div class="relative p-3.5 bg-white dark:bg-gray-700 rounded-xl hover:bg-emerald-50/50 dark:hover:bg-gray-600 transition-all border border-slate-200 dark:border-gray-600 hover:border-emerald-300 dark:hover:border-emerald-600 shadow-sm hover:shadow-md group">
                <!-- Left accent line -->
                <div class="absolute left-0 top-3 bottom-3 w-1 bg-gradient-to-b from-emerald-400 to-green-400 rounded-r-full"></div>
                
                <div class="flex items-start justify-between gap-3 mb-2 ml-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="font-medium text-gray-900 dark:text-white truncate">{{ $supervisi->user->name ?? 'User Tidak Ditemukan' }}</div>
                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-md">SELESAI</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            <span class="font-medium">{{ $supervisi->mapel ?? '-' }}</span>
                            @if($supervisi->tingkat)
                            <span class="mx-1">•</span>
                            <span>{{ $supervisi->tingkat }}</span>
                            @endif
                        </div>
                        @if($supervisi->user && ($supervisi->user->tingkat || $supervisi->user->mata_pelajaran))
                        <div class="flex items-center gap-1.5 mt-1.5">
                            @if($supervisi->user->tingkat)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                {{ $supervisi->user->tingkat }}
                            </span>
                            @endif
                            @if($supervisi->user->mata_pelajaran)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ $supervisi->user->mata_pelajaran }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $supervisi->created_at->format('d M Y') }}</span>
                    </div>
                    @if($supervisi->reviewed_at)
                    <div class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $supervisi->reviewed_at->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm">Belum ada supervisi selesai</p>
            </div>
            @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Admin Guide Modal -->
<div id="guideModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Panduan Administrator</h3>
            <button onclick="closeGuideModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-6 py-4 overflow-y-auto max-h-[calc(90vh-8rem)]">
            <div class="space-y-4">
                @php
                $guides = [
                    ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'title' => 'Kelola Pengguna', 'desc' => 'Tambah, edit, atau hapus data pengguna (admin, kepala sekolah, dan guru)'],
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Monitor Supervisi', 'desc' => 'Pantau status dan progress supervisi yang sedang berjalan'],
                    ['icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Review Dokumen', 'desc' => 'Periksa dan validasi dokumen supervisi yang diajukan guru'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Lihat Statistik', 'desc' => 'Analisis data dan laporan aktivitas supervisi'],
                    ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'title' => 'Pengaturan Sistem', 'desc' => 'Konfigurasi sistem dan preferensi aplikasi'],
                    ['icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Bantuan & Support', 'desc' => 'Hubungi tim support jika mengalami kendala']
                ];
                @endphp
                
                @foreach($guides as $index => $guide)
                <div class="flex gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $guide['icon'] }}"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $index + 1 }}. {{ $guide['title'] }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $guide['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <button onclick="closeGuideModal()" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
function openGuideModal() {
    document.getElementById('guideModal').classList.remove('hidden');
}

function closeGuideModal() {
    document.getElementById('guideModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('guideModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeGuideModal();
    }
});
</script>

@endsection
