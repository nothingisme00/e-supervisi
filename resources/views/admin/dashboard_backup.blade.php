@extends('layouts.modern')

@section('page-title', 'Dashboard Admin')

@section('content')

<!-- Header Section -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Dashboard Admin</h1>
    <p class="text-gray-600 dark:text-gray-400">Selamat datang di panel administrator</p>
</div>

<!-- Statistics Cards - Clean & Minimalist -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Total Pengguna -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Pengguna</span>
            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</div>
    </div>

    <!-- Perlu Review -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-amber-300 dark:hover:border-amber-600 transition-all">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Perlu Review</span>
            <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supervisiSubmitted }}</div>
    </div>

    <!-- Sedang Review -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Sedang Review</span>
            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supervisiUnderReview }}</div>
    </div>

    <!-- Selesai -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-600 transition-all">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Selesai</span>
            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supervisiCompleted }}</div>
    </div>
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
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Data Guru</h2>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">{{ $totalGuru }} guru</span>
        </div>
        
        <div class="space-y-3 max-h-[600px] overflow-y-auto">
            @forelse($guruList as $guru)
                <div class="mb-3 p-3 sm:p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <!-- Guru Info -->
                    <div class="flex items-start mb-2 sm:mb-3">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-bold shrink-0 mr-2 sm:mr-3">
                            {{ substr($guru->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $guru->name }}</h4>
                            <p class="text-[10px] sm:text-xs text-gray-600 dark:text-gray-400 truncate">{{ $guru->nik }}</p>
                            @if($guru->tingkat)
                            <span class="inline-block mt-1 text-[10px] sm:text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 px-1.5 sm:px-2 py-0.5 rounded">
                                {{ $guru->tingkat }} - {{ $guru->mata_pelajaran }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Supervisi Stats -->
                    <div class="grid grid-cols-5 gap-1 text-center">
                        <div class="bg-white dark:bg-gray-800 rounded p-1 sm:p-1.5 border border-gray-200 dark:border-gray-700">
                            <div class="text-[10px] sm:text-xs font-bold text-gray-900 dark:text-white">{{ $guru->total_supervisi }}</div>
                            <div class="text-[8px] sm:text-[10px] text-gray-500 dark:text-gray-400">Total</div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded p-1 sm:p-1.5 border border-gray-200 dark:border-gray-700">
                            <div class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400">{{ $guru->supervisi_draft }}</div>
                            <div class="text-[8px] sm:text-[10px] text-gray-500 dark:text-gray-400">Draft</div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded p-1 sm:p-1.5 border border-amber-200 dark:border-amber-700">
                            <div class="text-[10px] sm:text-xs font-bold text-amber-600 dark:text-amber-400">{{ $guru->supervisi_submitted }}</div>
                            <div class="text-[8px] sm:text-[10px] text-amber-600 dark:text-amber-400">Submit</div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded p-1 sm:p-1.5 border border-indigo-200 dark:border-indigo-700">
                            <div class="text-[10px] sm:text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ $guru->supervisi_under_review }}</div>
                            <div class="text-[8px] sm:text-[10px] text-indigo-600 dark:text-indigo-400">Review</div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded p-1 sm:p-1.5 border border-emerald-200 dark:border-emerald-700">
                            <div class="text-[10px] sm:text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $guru->supervisi_completed }}</div>
                            <div class="text-[8px] sm:text-[10px] text-emerald-600 dark:text-emerald-400">Selesai</div>
                        </div>
                    </div>

                    <!-- Last Login & Activity -->
                    <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700 space-y-1">
                        @if($guru->last_login_at)
                        <div class="flex items-center gap-1.5 text-[10px] text-gray-500 dark:text-gray-400">
                            <svg class="w-3 h-3 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="font-medium">Login terakhir:</span>
                            <span>{{ $guru->last_login_at->format('d M Y, H:i') }}</span>
                        </div>
                        @else
                        <div class="flex items-center gap-1.5 text-[10px] text-gray-400 dark:text-gray-500 italic">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Belum pernah login</span>
                        </div>
                        @endif
                        @if($guru->supervisi->isNotEmpty())
                        <div class="flex items-center gap-1.5 text-[10px] text-gray-500 dark:text-gray-400">
                            <svg class="w-3 h-3 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium">Aktivitas supervisi:</span>
                            <span>{{ $guru->supervisi->first()->updated_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-6 sm:py-8">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto text-gray-400 dark:text-gray-600 mb-2 sm:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Belum ada data guru</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Column 2: Supervisi Perlu/Sedang Review -->
    <div class="xl:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Perlu/Sedang Review
                    </h3>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">
                        {{ $supervisiUnderReviewList->count() }} Data
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[600px] overflow-y-auto">
                @forelse($supervisiUnderReviewList as $supervisi)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                    <!-- Status Badge -->
                    <div class="flex items-start justify-between mb-3">
                        @if($supervisi->status === 'submitted')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span>
                            Perlu Review
                        </span>
                        @elseif($supervisi->status === 'under_review')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                            <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-1.5"></span>
                            Sedang Review
                        </span>
                        @endif
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            #{{ $supervisi->id }}
                        </span>
                    </div>

                    <!-- Guru Info -->
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0 mr-2">
                            {{ substr($supervisi->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $supervisi->user->nik }}</p>
                        </div>
                    </div>

                    <!-- Supervisi Info -->
                    <div class="space-y-1.5 text-xs">
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->format('d M Y') }}</span>
                        </div>
                        @if($supervisi->user->tingkat)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span>{{ $supervisi->user->tingkat }} - {{ $supervisi->user->mata_pelajaran }}</span>
                        </div>
                        @endif
                        @if($supervisi->reviewer)
                        <div class="flex items-center text-indigo-600 dark:text-indigo-400">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Reviewer: {{ $supervisi->reviewer->name }}</span>
                        </div>
                        @endif
                        <div class="flex items-center text-gray-500 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $supervisi->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada supervisi yang perlu direview</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Column 3: Supervisi Selesai -->
    <div class="xl:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Supervisi Selesai
                    </h3>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">
                        {{ $supervisiCompletedList->count() }} Data
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[600px] overflow-y-auto">
                @forelse($supervisiCompletedList as $supervisi)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                    <!-- Status Badge -->
                    <div class="flex items-start justify-between mb-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                            Selesai
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            #{{ $supervisi->id }}
                        </span>
                    </div>

                    <!-- Guru Info -->
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0 mr-2">
                            {{ substr($supervisi->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $supervisi->user->nik }}</p>
                        </div>
                    </div>

                    <!-- Supervisi Info -->
                    <div class="space-y-1.5 text-xs">
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->format('d M Y') }}</span>
                        </div>
                        @if($supervisi->user->tingkat)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span>{{ $supervisi->user->tingkat }} - {{ $supervisi->user->mata_pelajaran }}</span>
                        </div>
                        @endif
                        @if($supervisi->reviewer)
                        <div class="flex items-center text-emerald-600 dark:text-emerald-400">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Reviewer: {{ $supervisi->reviewer->name }}</span>
                        </div>
                        @endif
                        @if($supervisi->reviewed_at)
                        <div class="flex items-center text-gray-500 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Direview: {{ $supervisi->reviewed_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada supervisi yang selesai</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

<!-- Admin Guide Modal -->
<div id="guideModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-70 items-center justify-center p-4" style="display: none;">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-linear-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 dark:bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Panduan Administrator</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Informasi penting untuk mengelola sistem E-Supervisi</p>
                </div>
            </div>
            <button onclick="closeGuideModal()" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Item 1 -->
                    <div class="flex gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                        <div class="shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Kelola Pengguna</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Anda dapat menambah, mengedit, dan menonaktifkan pengguna. Pastikan data NIK dan role sudah benar sebelum menyimpan.</p>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                        <div class="shrink-0">
                            <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Password Default</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">User baru akan mendapat password default <code class="px-1.5 py-0.5 bg-gray-200 dark:bg-gray-700 rounded text-xs font-mono">pass123456</code> dan wajib mengubahnya saat login pertama.</p>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                        <div class="shrink-0">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Batasan Edit Data</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Anda tidak dapat mengubah role atau tingkat akun Anda sendiri untuk menjaga keamanan sistem.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Item 4 -->
                    <div class="flex gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                        <div class="shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Monitoring Supervisi</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Dashboard menampilkan status supervisi secara real-time. Supervisi yang perlu direview akan ditangani oleh Kepala Sekolah.</p>
                        </div>
                    </div>

                    <!-- Item 5 -->
                    <div class="flex gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                        <div class="shrink-0">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Hapus User</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Hati-hati saat menghapus user. Data yang dihapus tidak dapat dikembalikan. Pastikan untuk menonaktifkan terlebih dahulu jika masih ragu.</p>
                        </div>
                    </div>

                    <!-- Item 6 -->
                    <div class="flex gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                        <div class="shrink-0">
                            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Reset Password</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Fitur reset password akan mengubah password user menjadi default dan user wajib menggantinya saat login berikutnya.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Note -->
            <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-gray-900/50 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">Butuh Bantuan?</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Jika mengalami kendala atau membutuhkan bantuan teknis, silakan hubungi tim IT support atau gunakan fitur bantuan di menu navigasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openGuideModal() {
    const modal = document.getElementById('guideModal');
    const content = modal.querySelector('div');
    modal.style.display = 'flex';
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeGuideModal() {
    const modal = document.getElementById('guideModal');
    const content = modal.querySelector('div');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Close modal on backdrop click
document.getElementById('guideModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeGuideModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('guideModal').style.display === 'flex') {
        closeGuideModal();
    }
});
</script>
@endsection