@extends('layouts.modern')

@section('page-title', 'Lihat Supervisi - ' . $supervisi->user->name)

@section('content')
<div class="w-full lg:w-3/4 mx-auto px-0 sm:px-4 pb-24 md:pb-0">
    
    <!-- Back Button -->
    <div class="mb-3 sm:mb-4">
        <a href="{{ route('guru.home') }}"
           class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-all duration-200 group text-sm">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">Kembali ke Beranda</span>
        </a>
    </div>

    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm sm:shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-4 sm:mb-6">
        <!-- Decorative Header Bar -->
        <div class="h-1.5 sm:h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        
        <div class="p-4 sm:p-6">
            <!-- Mobile: Stack vertically, Desktop: Side by side -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 sm:gap-4">
                <div class="flex items-center sm:items-start gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-base sm:text-lg shadow-md ring-2 sm:ring-4 ring-indigo-100 dark:ring-indigo-900/50 flex-shrink-0">
                        {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-base sm:text-xl lg:text-2xl font-bold text-gray-800 dark:text-white leading-tight">{{ $supervisi->user->name }}</h1>
                        <div class="flex flex-wrap gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1 sm:mt-2">
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="font-medium">{{ $supervisi->mata_pelajaran }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span class="font-medium">Kelas {{ $supervisi->kelas }}</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1 sm:mt-2 flex items-center">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->format('d F Y') }}
                        </p>
                    </div>
                </div>
                <!-- Status Badge -->
                <div class="self-start sm:self-auto mt-1 sm:mt-0">
                    @if($supervisi->status == 'draft')
                        <span class="inline-flex items-center px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold bg-gray-100 text-gray-700 border border-gray-200 shadow-sm">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-gray-500 rounded-full mr-1.5 sm:mr-2"></span>
                            Draft
                        </span>
                    @elseif($supervisi->status == 'submitted')
                        <span class="inline-flex items-center px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold bg-amber-100 text-amber-700 border border-amber-200 shadow-sm">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-amber-500 rounded-full mr-1.5 sm:mr-2 animate-pulse"></span>
                            Menunggu Peninjauan
                        </span>
                    @elseif($supervisi->status == 'under_review')
                        <span class="inline-flex items-center px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200 shadow-sm">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-indigo-600 rounded-full mr-1.5 sm:mr-2 animate-pulse"></span>
                            Sedang Ditinjau
                        </span>
                    @elseif($supervisi->status == 'completed')
                        <span class="inline-flex items-center px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Telah Ditinjau
                        </span>
                    @elseif($supervisi->status == 'revision')
                        <span class="inline-flex items-center px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold bg-rose-100 text-rose-700 border border-rose-200 shadow-sm">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Perlu Revisi
                        </span>
                    @endif
                    <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-end">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Disubmit: {{ $supervisi->updated_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vertical Card Layout -->
    <div class="space-y-4 sm:space-y-6">

        <!-- Card 1: Dokumen Evaluasi Diri -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-blue-600 dark:bg-blue-700 px-4 py-3 sm:px-6 sm:py-4">
                <h3 class="text-sm sm:text-base font-semibold text-white">Dokumen Evaluasi Diri</h3>
            </div>
            <!-- Card Content -->
            <div class="p-3 sm:p-4 md:p-6">
                @if($supervisi->dokumenEvaluasi && count($supervisi->dokumenEvaluasi) > 0)
                    <div class="max-h-80 sm:max-h-96 overflow-y-auto space-y-2">
                        @foreach($supervisi->dokumenEvaluasi as $dokumen)
                        <div class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 @if($dokumen->tipe_file == 'pdf') bg-red-100 text-red-600 @else bg-blue-100 text-blue-600 @endif rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">{{ $dokumen->nama_file }}</p>
                                <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">{{ strtoupper($dokumen->tipe_file) }} • {{ number_format($dokumen->ukuran_file / 1024, 2) }} KB</p>
                            </div>
                            <a href="{{ asset('storage/' . $dokumen->path) }}" 
                               target="_blank"
                               class="inline-flex items-center gap-1 px-2 py-1 sm:px-3 sm:py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span class="hidden sm:inline">Preview</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 sm:py-12">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Tidak ada dokumen evaluasi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Card 2: Link Pembelajaran -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 px-4 py-3 sm:px-6 sm:py-4">
                <h3 class="text-sm sm:text-base font-semibold text-white">Link Pembelajaran</h3>
            </div>
            <div class="p-3 sm:p-4 md:p-6 space-y-3 sm:space-y-4">
                @if($supervisi->prosesPembelajaran)
                    @if($supervisi->prosesPembelajaran->link_video)
                    <!-- Link Video -->
                    <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-r-lg p-3 sm:p-4">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0 overflow-hidden">
                                <div class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mb-1 sm:mb-2">Video Pembelajaran</div>
                                <a href="{{ $supervisi->prosesPembelajaran->link_video }}" target="_blank" class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:underline max-w-full group">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    <span class="truncate">{{ $supervisi->prosesPembelajaran->link_video }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($supervisi->prosesPembelajaran->link_meeting)
                    <!-- Link Meeting -->
                    <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-r-lg p-3 sm:p-4">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <div class="flex-1 min-w-0 overflow-hidden">
                                <div class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mb-1 sm:mb-2">Link Meeting</div>
                                <a href="{{ $supervisi->prosesPembelajaran->link_meeting }}" target="_blank" class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:underline max-w-full group">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    <span class="truncate">{{ $supervisi->prosesPembelajaran->link_meeting }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!$supervisi->prosesPembelajaran->link_video && !$supervisi->prosesPembelajaran->link_meeting)
                    <div class="text-center py-6 sm:py-8">
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Tidak ada link pembelajaran</p>
                    </div>
                    @endif
                @else
                    <div class="text-center py-6 sm:py-8">
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Tidak ada data proses pembelajaran</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Card 3: Refleksi Pembelajaran -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 px-4 py-3 sm:px-6 sm:py-4">
                <h3 class="text-sm sm:text-base font-semibold text-white">Refleksi Pembelajaran</h3>
            </div>
            <div class="p-3 sm:p-4 md:p-6">
                @if($supervisi->prosesPembelajaran)
                    <div class="space-y-2 sm:space-y-3 max-h-80 sm:max-h-96 overflow-y-auto">
                        @php
                            $refleksiItems = [
                                ['key' => 'refleksi_1', 'title' => '1. Apa yang sudah berjalan dengan baik?', 'color' => 'purple'],
                                ['key' => 'refleksi_2', 'title' => '2. Apa yang masih menjadi tantangan?', 'color' => 'blue'],
                                ['key' => 'refleksi_3', 'title' => '3. Apa yang akan saya lakukan untuk meningkatkan pembelajaran?', 'color' => 'green'],
                                ['key' => 'refleksi_4', 'title' => '4. Apa dukungan yang saya butuhkan?', 'color' => 'amber'],
                                ['key' => 'refleksi_5', 'title' => '5. Refleksi tambahan', 'color' => 'rose'],
                            ];
                            $hasAnyRefleksi = false;
                        @endphp

                        @foreach($refleksiItems as $index => $item)
                            @if($supervisi->prosesPembelajaran->{$item['key']})
                                @php $hasAnyRefleksi = true; @endphp
                                <div class="border-l-4 border-{{ $item['color'] }}-500 bg-{{ $item['color'] }}-50 dark:bg-{{ $item['color'] }}-900/20 rounded-r-lg p-2.5 sm:p-3">
                                    <div class="flex items-start gap-2">
                                        <span class="flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 bg-{{ $item['color'] }}-600 text-white rounded text-[10px] sm:text-xs font-bold flex-shrink-0">{{ $index + 1 }}</span>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs sm:text-sm font-semibold text-{{ $item['color'] }}-900 dark:text-{{ $item['color'] }}-300 mb-0.5 sm:mb-1 leading-tight">{{ $item['title'] }}</div>
                                            <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $supervisi->prosesPembelajaran->{$item['key']} }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if(!$hasAnyRefleksi)
                        <div class="text-center py-6 sm:py-8">
                            <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Tidak ada data refleksi</p>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-6 sm:py-8">
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Tidak ada data refleksi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Card 4: Diskusi & Feedback -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-700 dark:to-indigo-700 px-4 py-3 sm:px-6 sm:py-4">
                <h3 class="text-sm sm:text-base font-semibold text-white">Diskusi & Feedback</h3>
            </div>
            <div class="p-3 sm:p-4 md:p-6">
                <!-- Success/Error Messages -->
                @if(session('success'))
                <div class="mb-3 sm:mb-4 p-3 sm:p-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 dark:border-emerald-600 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-emerald-700 dark:text-emerald-300 text-xs sm:text-sm font-medium">{{ session('success') }}</span>
                    </div>
                </div>
                @endif

                <!-- Feedback List -->
                <div class="space-y-2 sm:space-y-3 max-h-80 sm:max-h-96 overflow-y-auto mb-3 sm:mb-4">
                @if($supervisi->feedback && $supervisi->feedback->count() > 0)
                    @foreach($supervisi->feedback->whereNull('parent_id')->sortByDesc('created_at') as $fb)
                    <div class="border-l-4 {{ $fb->is_revision_request ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : ($fb->user_id == auth()->id() ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-amber-500 bg-amber-50 dark:bg-amber-900/20') }} rounded-r-lg p-3 sm:p-4">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br {{ $fb->user_id == auth()->id() ? 'from-blue-500 to-indigo-600' : 'from-amber-500 to-orange-600' }} rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-bold flex-shrink-0 shadow-md">
                                {{ strtoupper(substr($fb->user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-2 mb-1 sm:mb-2">
                                    <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                        <span class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white">{{ $fb->user->name ?? 'User' }}</span>
                                        @if($fb->user->role === 'kepala_sekolah')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] sm:text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">Kepala Sekolah</span>
                                        @elseif($fb->user_id == auth()->id())
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] sm:text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Anda</span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] sm:text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">Guru</span>
                                        @endif
                                    </div>
                                    <div class="inline-flex items-center gap-1 text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $fb->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $fb->komentar }}</p>

                                <!-- Reply Button -->
                                <div class="mt-2 sm:mt-3">
                                    <button onclick="toggleReplyForm({{ $fb->id }})" class="inline-flex items-center gap-1 px-2 py-1 text-[10px] sm:text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                        </svg>
                                        Balas
                                    </button>
                                </div>

                                <!-- Reply Form -->
                                <div id="reply-form-{{ $fb->id }}" class="hidden mt-2 sm:mt-3 pl-3 sm:pl-4 border-l-2 border-indigo-200 dark:border-indigo-800">
                                    <form action="{{ route('guru.supervisi.comment', $supervisi->id) }}" method="POST" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $fb->id }}">
                                        <textarea name="komentar" rows="2" required class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 resize-none" placeholder="Tulis balasan Anda..."></textarea>
                                        <div class="flex gap-2">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 sm:px-3 sm:py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] sm:text-xs font-semibold rounded-lg transition-all">Kirim</button>
                                            <button type="button" onclick="toggleReplyForm({{ $fb->id }})" class="px-2 py-1 sm:px-3 sm:py-1.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 text-[10px] sm:text-xs font-semibold rounded-lg transition-all">Batal</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Nested Replies -->
                                @if($fb->replies && $fb->replies->count() > 0)
                                    <div class="mt-3 sm:mt-4 ml-4 sm:ml-6 space-y-2 sm:space-y-3 pl-3 sm:pl-4 border-l-2 border-gray-200 dark:border-gray-700">
                                        @foreach($fb->replies as $reply)
                                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-2 sm:p-3">
                                            <div class="flex items-start gap-2">
                                                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br {{ $reply->user_id == auth()->id() ? 'from-blue-400 to-indigo-500' : 'from-gray-400 to-gray-600' }} rounded-full flex items-center justify-center text-white text-[10px] sm:text-xs font-bold flex-shrink-0">
                                                    {{ strtoupper(substr($reply->user->name ?? 'U', 0, 2)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                                                        <span class="text-[10px] sm:text-xs font-semibold text-gray-900 dark:text-white">{{ $reply->user->name ?? 'User' }}</span>
                                                        @if($reply->user_id == auth()->id())
                                                            <span class="inline-flex items-center px-1 py-0.5 rounded text-[8px] sm:text-[10px] font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Anda</span>
                                                        @endif
                                                        <span class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-[10px] sm:text-xs text-gray-700 dark:text-gray-300">{{ $reply->komentar }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-6 sm:py-8">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-2 sm:mb-3">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Belum ada komentar</p>
                    </div>
                @endif
                </div>

                <!-- Add Comment Form -->
                @if($supervisi->status !== 'draft')
                <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-200 dark:border-gray-700">
                    <form action="{{ route('guru.supervisi.comment', $supervisi->id) }}" method="POST" class="space-y-2 sm:space-y-3">
                        @csrf
                        <div>
                            <label for="komentar" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">
                                Tambahkan Komentar atau Balasan
                            </label>
                            <textarea name="komentar" id="komentar" rows="3" required class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-xs sm:text-sm resize-none" placeholder="Tulis komentar, pertanyaan, atau balasan Anda di sini..."></textarea>
                            @error('komentar')
                                <p class="mt-1 text-[10px] sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-5 sm:py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-xs sm:text-sm font-semibold rounded-lg transition-all shadow-md hover:shadow-lg active:scale-95">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Kirim Komentar
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

    </div> <!-- End Vertical Card Layout -->

</div> <!-- End container -->

<script>
function toggleReplyForm(id) {
    const form = document.getElementById('reply-form-' + id);
    if (form) {
        form.classList.toggle('hidden');
    }
}
</script>

@endsection
