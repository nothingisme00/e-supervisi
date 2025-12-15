@extends('layouts.modern')

@section('page-title', 'Evaluasi Supervisi')

@section('content')
<!-- Breadcrumb -->
<div class="mb-2 sm:mb-4">
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('kepala.dashboard')],
        ['label' => 'Evaluasi Supervisi', 'icon' => true]
    ]" />
</div>

<!-- Header Section -->
<div class="mb-4 sm:mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-base sm:text-2xl font-bold text-gray-900 dark:text-white mb-1 sm:mb-2">Evaluasi Supervisi</h1>
            <p class="text-xs sm:text-base text-gray-600 dark:text-gray-400">Kelola dan evaluasi supervisi pembelajaran guru</p>
        </div>
        <a href="{{ route('kepala.dashboard') }}" class="px-2.5 py-1.5 sm:px-4 sm:py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-md sm:rounded-lg border border-gray-300 dark:border-gray-600 transition-all shadow-sm text-xs sm:text-sm">
            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 inline-block mr-0.5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>

<!-- Filter & Search Section -->
<div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl border border-slate-200 dark:border-gray-700 p-3 sm:p-5 mb-4 sm:mb-6 shadow-sm">
    <form method="GET" action="{{ route('kepala.evaluasi.index') }}" class="flex flex-col sm:flex-row gap-2 sm:gap-4">
        <!-- Search Input -->
        <div class="flex-1">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru..." class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2 sm:py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-gray-600 rounded-md sm:rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>
        </div>

        <!-- Status Filter -->
        <div class="w-full sm:w-48">
            <select name="status" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-gray-600 rounded-md sm:rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                <option value="">Semua Status</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Perlu Review</option>
                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Sedang Ditinjau</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Telah Ditinjau</option>
            </select>
        </div>

        <!-- Filter Button -->
        <button type="submit" class="px-4 sm:px-6 py-2 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors shadow-sm hover:shadow">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-0.5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Filter
        </button>

        @if(request('search') || request('status'))
        <a href="{{ route('kepala.evaluasi.index') }}" class="px-4 sm:px-6 py-2 sm:py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors text-center">
            Reset
        </a>
        @endif
    </form>
</div>

<!-- Supervisi List -->
<div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-2xl border border-slate-200 dark:border-gray-700 overflow-hidden shadow-sm">
    <!-- Header -->
    <div class="relative bg-gradient-to-r from-indigo-500/90 to-purple-500/90 px-3 py-3 sm:px-6 sm:py-5">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-400 to-purple-400"></div>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-sm sm:text-lg font-bold text-white flex items-center gap-1.5 sm:gap-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Daftar Supervisi
                </h2>
                <p class="text-[10px] sm:text-xs text-indigo-100 mt-0.5 sm:mt-1">Total {{ $supervisiList->total() }} supervisi ditemukan</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
    </div>

    <!-- Body -->
    <div class="p-3 sm:p-6 bg-gradient-to-b from-indigo-50/30 to-white dark:from-gray-800 dark:to-gray-800">
        @if($supervisiList->count() > 0)
        <div class="space-y-2 sm:space-y-3">
            @foreach($supervisiList as $supervisi)
            <div class="relative p-3 sm:p-4 bg-white dark:bg-gray-700 rounded-lg sm:rounded-xl hover:bg-indigo-50/50 dark:hover:bg-gray-600 transition-all border border-slate-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-600 shadow-sm hover:shadow-md group">
                <!-- Left accent line with dynamic color -->
                <div class="absolute left-0 top-2 sm:top-3 bottom-2 sm:bottom-3 w-1 @if($supervisi->status == 'submitted') bg-gradient-to-b from-amber-400 to-orange-400 @elseif($supervisi->status == 'under_review') bg-gradient-to-b from-indigo-400 to-purple-400 @else bg-gradient-to-b from-emerald-400 to-green-400 @endif rounded-r-full"></div>
                
                <div class="flex items-start justify-between gap-2 sm:gap-4 ml-2 sm:ml-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                            <div class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</div>
                            @if($supervisi->status == 'submitted')
                            <span class="px-1.5 py-0.5 sm:px-2 sm:py-0.5 text-[8px] sm:text-[10px] font-semibold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded">REVIEW</span>
                            @elseif($supervisi->status == 'under_review')
                            <span class="px-1.5 py-0.5 sm:px-2 sm:py-0.5 text-[8px] sm:text-[10px] font-semibold bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded">DITINJAU</span>
                            @elseif($supervisi->status == 'completed')
                            <span class="px-1.5 py-0.5 sm:px-2 sm:py-0.5 text-[8px] sm:text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded">SELESAI</span>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1.5 sm:gap-2 text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">
                            @if($supervisi->user->mata_pelajaran)
                            <div class="flex items-center">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span class="font-medium">{{ $supervisi->user->mata_pelajaran }}</span>
                            </div>
                            @endif
                            @if($supervisi->user->tingkat)
                            <div class="flex items-center">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>{{ $supervisi->user->tingkat }}</span>
                            </div>
                            @endif
                            <div class="flex items-center">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->format('d M Y') }}</span>
                            </div>
                        </div>

                        @if($supervisi->reviewed_at)
                        <div class="flex items-center text-[10px] sm:text-xs text-indigo-600 dark:text-indigo-400 mt-1.5 sm:mt-2 pt-1.5 sm:pt-2 border-t border-gray-100 dark:border-gray-600">
                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-0.5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Direview {{ $supervisi->reviewed_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" class="shrink-0 px-2.5 py-1.5 sm:px-4 sm:py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-[10px] sm:text-xs font-medium rounded-md sm:rounded-lg transition-colors shadow-sm hover:shadow">
                        Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($supervisiList->hasPages())
        <div class="mt-4 sm:mt-6">
            {{ $supervisiList->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-8 sm:py-12">
            <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400">Tidak ada supervisi yang ditemukan</p>
            @if(request('search') || request('status'))
            <a href="{{ route('kepala.evaluasi.index') }}" class="inline-block mt-3 sm:mt-4 px-3 py-1.5 sm:px-4 sm:py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors">
                Lihat Semua Supervisi
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

@endsection

