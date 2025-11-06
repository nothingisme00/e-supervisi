@extends('layouts.modern')

@section('page-title', 'Dashboard Admin')

@section('content')

<x-breadcrumb :items="[['label' => 'Dashboard Admin', 'icon' => true]]" />

<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">Dashboard Admin</h1>
            <p class="text-base text-gray-600 dark:text-gray-400">Selamat datang, {{ Auth::user()->name }}</p>
        </div>
        <div class="text-right">
            <div class="text-base text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
            <div class="text-sm text-gray-400 dark:text-gray-500 mt-1">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</div>
        </div>
    </div>
</div>

<!-- Action Buttons - Side by Side -->
<div class="mb-6">
    <div class="flex flex-col sm:flex-row gap-3">
        <!-- Panduan Admin - Left -->
        <button onclick="openGuideModal()" class="flex-1 inline-flex items-center justify-between px-6 py-3.5 bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 border-2 border-indigo-300 dark:border-indigo-800/50 hover:border-indigo-400 dark:hover:border-indigo-700/50 hover:shadow-md rounded-xl transition-all relative overflow-hidden group">
            <!-- Subtle shine effect -->
            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
            
            <div class="flex items-center gap-3 relative z-10">
                <!-- Icon with gradient -->
                <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-gray-900 dark:text-white">Panduan Admin</span>
                        <span class="px-2 py-0.5 bg-indigo-200 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 rounded text-xs font-medium">Penting</span>
                    </div>
                    <p class="text-xs text-gray-700 dark:text-gray-400 mt-0.5">Pelajari cara mengelola sistem</p>
                </div>
            </div>
            
            <div class="relative z-10 flex items-center gap-2">
                <!-- Pulse indicator -->
                <span class="flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-600"></span>
                </span>
                <!-- Arrow icon -->
                <svg class="w-5 h-5 text-indigo-700 dark:text-indigo-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </button>
        
        <!-- Kelola Pengguna - Right -->
        <a href="{{ route('admin.users.index') }}" class="flex-1 group inline-flex items-center justify-between px-6 py-3.5 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 border-2 border-blue-300 dark:border-blue-800/50 hover:border-blue-400 dark:hover:border-blue-700/50 hover:shadow-md rounded-xl transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="font-semibold text-gray-900 dark:text-white">Kelola Pengguna</div>
                    <div class="text-xs text-gray-700 dark:text-gray-400">Atur data pengguna</div>
                </div>
            </div>
            <svg class="w-5 h-5 text-blue-700 dark:text-blue-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
</div>

<!-- Content Grid - Clean Layout with Soft Colors -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    
    <!-- Data Guru - Simplified -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border-2 border-blue-200 dark:border-blue-900/30 overflow-hidden hover:shadow-xl hover:border-blue-300 dark:hover:border-blue-800/50 transition-all shadow-md">
        <!-- Header -->
        <div class="px-5 py-4 bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-blue-200 dark:border-blue-800/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Data Guru</h2>
                        <p class="text-sm text-blue-700 dark:text-blue-400 font-medium">{{ $totalGuru }} guru terdaftar</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-medium rounded-lg transition-all shadow hover:shadow-md">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah
                </a>
            </div>
        </div>
        
        <!-- Body -->
        <div class="p-4">
            <div class="space-y-3 max-h-[500px] overflow-y-auto">
            @forelse($guruList as $guru)
            <div class="relative flex items-start gap-3 p-3 bg-gray-100 dark:bg-gray-700/70 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-800/30 group shadow-sm hover:shadow-md">
                <!-- Left accent line -->
                <div class="absolute left-0 top-2 bottom-2 w-1.5 bg-gradient-to-b from-blue-500 to-indigo-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <!-- Avatar with initial -->
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-base shrink-0 shadow-md ring-2 ring-white dark:ring-gray-800 group-hover:scale-105 transition-transform">
                    {{ strtoupper(substr($guru->name, 0, 1)) }}
                </div>
                
                <div class="flex-1 min-w-0">
                    <!-- Name & Role -->
                    <div class="flex items-center gap-2 mb-1">
                        <div class="font-semibold text-sm text-gray-900 dark:text-white truncate">{{ $guru->name }}</div>
                        <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-[10px] font-medium">GURU</span>
                    </div>
                    
                    <!-- NIK -->
                    <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400 mb-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                        <span class="truncate">{{ $guru->nik }}</span>
                    </div>
                    
                    @if($guru->mata_pelajaran)
                    <!-- Mata Pelajaran -->
                    <div class="flex items-center gap-1.5 text-xs text-blue-700 dark:text-blue-400 font-medium mb-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>{{ $guru->mata_pelajaran }}</span>
                        @if($guru->tingkat)
                        <span class="text-gray-400">•</span>
                        <span>{{ $guru->tingkat }}</span>
                        @endif
                    </div>
                    @elseif($guru->tingkat)
                    <!-- Tingkat (if no mata_pelajaran) -->
                    <div class="flex items-center gap-1.5 text-xs text-blue-700 dark:text-blue-400 font-medium mb-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>{{ $guru->tingkat }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Supervisi Count -->
                <div class="text-right shrink-0">
                    <div class="flex items-center justify-end gap-1 mb-0.5">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $guru->total_supervisi }}</div>
                    </div>
                    <div class="text-[10px] text-gray-600 dark:text-gray-400 font-medium">Supervisi</div>
                </div>
            </div>
            @empty
            <x-empty-state 
                icon="users" 
                title="Belum ada guru" 
                description="Klik tombol 'Tambah' untuk menambahkan guru baru" 
                :compact="true" 
            />
            @endforelse
            </div>
        </div>
    </div>

    <!-- Perlu Review & Sedang Review - Combined -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border-2 border-orange-200 dark:border-orange-900/30 overflow-hidden hover:shadow-xl hover:border-orange-300 dark:hover:border-orange-800/50 transition-all shadow-md">
        <!-- Header -->
        <div class="px-5 py-4 bg-gradient-to-r from-orange-100 to-amber-100 dark:from-orange-900/20 dark:to-amber-900/20 border-b border-orange-200 dark:border-orange-800/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-orange-600 to-amber-600 rounded-lg flex items-center justify-center shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Dalam Proses</h2>
                        <p class="text-sm text-orange-700 dark:text-orange-400 font-medium">
                            <span id="proses-count">{{ $supervisiUnderReviewList->count() }}</span> item
                        </p>
                    </div>
                </div>
                
                <!-- Filter Buttons -->
                <div class="flex gap-2">
                    <button onclick="filterProses('all')" class="filter-btn-proses active px-4 py-2 bg-orange-600 text-white text-xs font-semibold rounded-lg transition-all hover:bg-orange-700 shadow-sm hover:shadow-md">
                        Semua
                    </button>
                    <button onclick="filterProses('submitted')" class="filter-btn-proses px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg transition-all hover:bg-orange-200 dark:hover:bg-orange-900/30">
                        Submitted
                    </button>
                    <button onclick="filterProses('under_review')" class="filter-btn-proses px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg transition-all hover:bg-purple-200 dark:hover:bg-purple-900/30">
                        Review
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Body -->
        <div class="p-4">
            <div class="space-y-3 h-[500px] overflow-y-auto" id="proses-list">
            @forelse($supervisiUnderReviewList->sortByDesc('created_at') as $supervisi)
            <div class="proses-item relative p-3 bg-gray-100 dark:bg-gray-700/70 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-all border border-gray-200 dark:border-gray-600 hover:border-orange-300 dark:hover:border-orange-800/30 group shadow-sm hover:shadow-md" data-status="{{ $supervisi->status }}">
                <!-- Left accent line -->
                <div class="absolute left-0 top-2 bottom-2 w-1.5 @if($supervisi->status == 'submitted') bg-gradient-to-b from-orange-500 to-amber-500 @else bg-gradient-to-b from-purple-500 to-indigo-500 @endif rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="flex items-start gap-3 mb-2 ml-2">
                    <!-- User Avatar -->
                    <div class="w-10 h-10 @if($supervisi->status == 'submitted') bg-gradient-to-br from-orange-600 to-amber-600 @else bg-gradient-to-br from-purple-600 to-indigo-600 @endif rounded-lg flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-md ring-2 ring-white dark:ring-gray-800 group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr($supervisi->user->name ?? 'U', 0, 1)) }}
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <!-- Name & Status Badge -->
                        <div class="flex items-center gap-2 mb-1">
                            <div class="font-semibold text-sm text-gray-900 dark:text-white truncate">{{ $supervisi->user->name ?? 'User Tidak Ditemukan' }}</div>
                            @if($supervisi->status == 'submitted')
                            <span class="px-2 py-0.5 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded text-[10px] font-bold">PERLU REVIEW</span>
                            @else
                            <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded text-[10px] font-bold">SEDANG REVIEW</span>
                            @endif
                        </div>
                        
                        <!-- Mapel & Tingkat -->
                        <div class="flex items-center gap-1.5 text-xs @if($supervisi->status == 'submitted') text-orange-700 dark:text-orange-400 @else text-purple-700 dark:text-purple-400 @endif font-medium mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span>{{ $supervisi->mapel ?? '-' }}</span>
                            @if($supervisi->tingkat)
                            <span class="text-gray-400">•</span>
                            <span>{{ $supervisi->tingkat }}</span>
                            @endif
                        </div>
                        
                        <!-- Date Info -->
                        <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $supervisi->created_at->format('d M Y') }}</span>
                            <span class="text-gray-400">•</span>
                            <span class="@if($supervisi->status == 'submitted') text-orange-600 dark:text-orange-400 @else text-purple-600 dark:text-purple-400 @endif font-medium">{{ $supervisi->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <x-empty-state 
                icon="clock" 
                title="Tidak ada supervisi dalam proses" 
                description="Supervisi yang sedang ditinjau akan muncul di sini" 
                :compact="true" 
            />
            @endforelse
            </div>
        </div>
    </div>

    <!-- Supervisi Selesai - Simplified -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border-2 border-green-200 dark:border-green-900/30 overflow-hidden hover:shadow-xl hover:border-green-300 dark:hover:border-green-800/50 transition-all shadow-md">
        <!-- Header -->
        <div class="px-5 py-4 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/20 border-b border-green-200 dark:border-green-800/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-600 rounded-lg flex items-center justify-center shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Selesai</h2>
                        <p class="text-sm text-green-700 dark:text-green-400 font-medium">{{ $supervisiCompletedList->count() }} selesai</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Body -->
        <div class="p-4">
            <div class="space-y-3 max-h-[500px] overflow-y-auto">
            @forelse($supervisiCompletedList as $supervisi)
            <div class="relative p-3 bg-gray-100 dark:bg-gray-700/70 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-all border border-gray-200 dark:border-gray-600 hover:border-green-300 dark:hover:border-green-800/30 group shadow-sm hover:shadow-md">
                <!-- Left accent line -->
                <div class="absolute left-0 top-2 bottom-2 w-1.5 bg-gradient-to-b from-green-500 to-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="flex items-start gap-3 mb-2 ml-2">
                    <!-- User Avatar -->
                    <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-md ring-2 ring-white dark:ring-gray-800 group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr($supervisi->user->name ?? 'U', 0, 1)) }}
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <!-- Name & Status Badge -->
                        <div class="flex items-center gap-2 mb-1">
                            <div class="font-semibold text-sm text-gray-900 dark:text-white truncate">{{ $supervisi->user->name ?? 'User Tidak Ditemukan' }}</div>
                            <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded text-[10px] font-bold">SELESAI</span>
                        </div>
                        
                        <!-- Mapel & Tingkat -->
                        <div class="flex items-center gap-1.5 text-xs text-green-700 dark:text-green-400 font-medium mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span>{{ $supervisi->mapel ?? '-' }}</span>
                            @if($supervisi->tingkat)
                            <span class="text-gray-400">•</span>
                            <span>{{ $supervisi->tingkat }}</span>
                            @endif
                        </div>
                        
                        <!-- Date Info -->
                        <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $supervisi->created_at->format('d M Y') }}</span>
                            @if($supervisi->reviewed_at)
                            <span class="text-gray-400">•</span>
                            <span class="text-green-600 dark:text-green-400 font-medium">Selesai {{ $supervisi->reviewed_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <x-empty-state 
                icon="check" 
                title="Belum ada supervisi selesai" 
                description="Supervisi yang telah selesai akan ditampilkan di sini" 
                :compact="true" 
            />
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

// Filter functionality for "Dalam Proses"
function filterProses(status) {
    const items = document.querySelectorAll('.proses-item');
    const buttons = document.querySelectorAll('.filter-btn-proses');
    let visibleCount = 0;
    
    // Update button styles
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-orange-600', 'bg-purple-600', 'text-white');
        btn.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
    });
    
    // Set active button
    event.target.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
    event.target.classList.add('active');
    
    if (status === 'all') {
        event.target.classList.add('bg-orange-600', 'text-white');
    } else if (status === 'submitted') {
        event.target.classList.add('bg-orange-600', 'text-white');
    } else {
        event.target.classList.add('bg-purple-600', 'text-white');
    }
    
    // Filter items
    items.forEach(item => {
        if (status === 'all') {
            item.style.display = '';
            visibleCount++;
        } else {
            if (item.dataset.status === status) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        }
    });
    
    // Update count
    document.getElementById('proses-count').textContent = visibleCount;
}
</script>

@endsection
