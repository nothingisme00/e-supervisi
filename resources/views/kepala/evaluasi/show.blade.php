@extends('layouts.modern')

@section('page-title', 'Detail Evaluasi Supervisi - ' . $supervisi->user->name)

@section('content')
<div class="w-full lg:w-3/4 mx-auto px-0 sm:px-4 pb-24 md:pb-0">
    
    <!-- Back Button -->
    <div class="mb-3 sm:mb-4">
        <a href="{{ route('kepala.dashboard') }}"
           class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-all duration-200 group text-sm">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">Kembali ke Dashboard</span>
        </a>
    </div>
    
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
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 truncate">{{ $supervisi->user->email }}</p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5 sm:mt-1 flex items-center">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            NIK: {{ $supervisi->user->nik }}
                        </p>
                    </div>
                </div>
                <!-- Status Badge -->
                <div class="self-start sm:self-auto mt-1 sm:mt-0">
                    @if($supervisi->status === 'submitted')
                        <span class="inline-flex items-center px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold bg-amber-100 text-amber-700 border border-amber-200 shadow-sm mb-2">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-amber-500 rounded-full mr-1.5 sm:mr-2 animate-pulse"></span>
                            Menunggu Peninjauan
                        </span>
                        <form action="{{ route('kepala.evaluasi.startReview', $supervisi->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 sm:px-5 sm:py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-xs sm:text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 active:scale-95">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Mulai Review
                            </button>
                        </form>
                    @elseif($supervisi->status === 'under_review')
                        <span class="inline-flex items-center px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200 shadow-sm">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-indigo-600 rounded-full mr-1.5 sm:mr-2 animate-pulse"></span>
                            Sedang Ditinjau
                        </span>
                    @elseif($supervisi->status === 'completed')
                        <span class="inline-flex items-center px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Telah Ditinjau
                        </span>
                    @elseif($supervisi->status === 'revision')
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
                    <div class="max-h-80 sm:max-h-96 overflow-y-auto space-y-2">
                        @php
                            $jenisLabels = [
                                'capaian_pembelajaran' => 'Capaian Pembelajaran (CP)',
                                'alur_tujuan_pembelajaran' => 'Alur Tujuan Pembelajaran (ATP)',
                                'kalender' => 'Kalender',
                                'program_tahunan' => 'Program Tahunan',
                                'program_semester' => 'Program Semester',
                                'modul_ajar' => 'Modul Ajar (1x pertemuan)',
                                'bahan_ajar' => 'Bahan Ajar'
                            ];
                        @endphp
                        
                        @forelse($supervisi->dokumenEvaluasi as $index => $dokumen)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                                <div class="flex items-center space-x-4">
                                    <!-- Nomor Urut -->
                                    <div class="shrink-0 w-8 text-center">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ $loop->iteration }}.</span>
                                    </div>
                                    @if(str_ends_with($dokumen->file_path, '.pdf'))
                                        <svg class="w-8 h-8 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                    <div>
                                        <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-0.5">
                                            {{ $jenisLabels[$dokumen->jenis_dokumen] ?? ucwords(str_replace('_', ' ', $dokumen->jenis_dokumen)) }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $dokumen->nama_file ?? 'Dokumen ' . ($index + 1) }}
                                        </p>
                                        @if($dokumen->deskripsi)
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $dokumen->deskripsi }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php
                                        $previewPath = $dokumen->path_file ?? $dokumen->file_path ?? null;
                                    @endphp

                                    @if($previewPath)
                                    <a href="{{ asset('storage/' . $previewPath) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Preview
                                    </a>
                                    @endif

                                    <a href="{{ route('kepala.evaluasi.download', $dokumen->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 dark:bg-blue-500 rounded hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Tidak ada dokumen</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Card 2: Link Pembelajaran -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 px-4 py-3 sm:px-6 sm:py-4">
                    <h3 class="text-sm sm:text-base font-semibold text-white">Link Pembelajaran</h3>
                </div>
                <!-- Card Content -->
                <div class="p-3 sm:p-4 md:p-6">
                    <div class="max-h-80 sm:max-h-96 overflow-y-auto space-y-3">
                        @if($supervisi->prosesPembelajaran)
                            @if($supervisi->prosesPembelajaran->link_video)
                                <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-r-lg p-3 sm:p-4">
                                    <div class="flex items-start gap-2 sm:gap-3">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="flex-1 min-w-0 overflow-hidden">
                                            <div class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mb-1 sm:mb-2">Link Video Pembelajaran</div>
                                            <a href="{{ $supervisi->prosesPembelajaran->link_video }}"
                                               target="_blank"
                                               class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:underline max-w-full">
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
                                <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-r-lg p-3 sm:p-4">
                                    <div class="flex items-start gap-2 sm:gap-3">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="flex-1 min-w-0 overflow-hidden">
                                            <div class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mb-1 sm:mb-2">Link Meeting/Zoom</div>
                                            <a href="{{ $supervisi->prosesPembelajaran->link_meeting }}"
                                               target="_blank"
                                               class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:underline max-w-full">
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
                                    <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Belum ada link pembelajaran</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-6 sm:py-8">
                                <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Tidak ada data pembelajaran</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>            <!-- Card 3: Refleksi Pembelajaran -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 px-4 py-3 sm:px-6 sm:py-4">
                    <h3 class="text-sm sm:text-base font-semibold text-white">Refleksi Pembelajaran</h3>
                </div>
                <!-- Card Content -->
                <div class="p-3 sm:p-4 md:p-6">
                    <div class="max-h-80 sm:max-h-96 overflow-y-auto space-y-2 sm:space-y-3">
                        @if($supervisi->prosesPembelajaran)
                            @php
                                $reflections = [
                                    ['label' => 'Apakah hal terbaik yang dapat saya lakukan?', 'value' => $supervisi->prosesPembelajaran->refleksi_1],
                                    ['label' => 'Apa yang dapat saya tingkatkan?', 'value' => $supervisi->prosesPembelajaran->refleksi_2],
                                    ['label' => 'Apakah saya sudah menerapkan strategi terbaik?', 'value' => $supervisi->prosesPembelajaran->refleksi_3],
                                    ['label' => 'Bagaimana respons murid terhadap pembelajaran?', 'value' => $supervisi->prosesPembelajaran->refleksi_4],
                                    ['label' => 'Apa rencana perbaikan ke depan?', 'value' => $supervisi->prosesPembelajaran->refleksi_5],
                                ];
                            @endphp

                            @foreach($reflections as $index => $reflection)
                                @if($reflection['value'])
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-md border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                                        <div class="flex items-start space-x-3">
                                            <div class="shrink-0 w-6 text-center">
                                                <span class="text-sm font-semibold text-gray-600 dark:text-gray-300">{{ $index + 1 }}.</span>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ $reflection['label'] }}</p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $reflection['value'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if(!$supervisi->prosesPembelajaran->refleksi_1 && !$supervisi->prosesPembelajaran->refleksi_2 && !$supervisi->prosesPembelajaran->refleksi_3 && !$supervisi->prosesPembelajaran->refleksi_4 && !$supervisi->prosesPembelajaran->refleksi_5)
                                <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Tidak ada refleksi</p>
                            @endif
                        @else
                            <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Tidak ada data refleksi</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($supervisi->status !== 'submitted')
            <!-- Card 4: Riwayat Feedback & Diskusi -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-700 dark:to-indigo-700 px-4 py-3 sm:px-6 sm:py-4">
                    <h3 class="text-sm sm:text-base font-semibold text-white">Diskusi & Feedback</h3>
                </div>
                <!-- Card Content -->
                <div class="p-3 sm:p-4 md:p-6">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 dark:border-emerald-600 rounded-r-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-emerald-700 dark:text-emerald-300 text-sm font-medium">{{ session('success') }}</span>
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

                                            @if($fb->is_revision_request)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                    Revisi Diminta
                                                </span>
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

                                    @if($fb->is_revision_request)
                                        <div class="mt-3 p-3 bg-red-100 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-800/50">
                                            <p class="text-xs font-semibold text-red-800 dark:text-red-300 mb-1">⚠️ Revisi Diminta</p>
                                            <p class="text-xs text-red-700 dark:text-red-400">Guru akan melakukan revisi sesuai feedback di atas.</p>
                                        </div>
                                    @endif

                                    <!-- Reply Button -->
                                    @if($supervisi->status !== 'completed')
                                    <div class="mt-3">
                                        <button onclick="toggleReplyForm({{ $fb->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                            </svg>
                                            Balas
                                        </button>
                                    </div>

                                    <!-- Reply Form -->
                                    <div id="reply-form-{{ $fb->id }}" class="hidden mt-3 pl-4 border-l-2 border-indigo-200 dark:border-indigo-800">
                                        <form action="{{ route('kepala.evaluasi.feedback', $supervisi->id) }}" method="POST" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $fb->id }}">
                                            <textarea
                                                name="komentar"
                                                rows="2"
                                                required
                                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                                                placeholder="Tulis balasan Anda..."></textarea>
                                            <div class="flex gap-2">
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                    </svg>
                                                    Kirim
                                                </button>
                                                <button type="button" onclick="toggleReplyForm({{ $fb->id }})" class="px-3 py-1.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 text-xs font-semibold rounded-lg transition-all">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif

                                    <!-- Nested Replies -->
                                    @if($fb->replies && $fb->replies->count() > 0)
                                        <div class="mt-4 ml-6 space-y-3 pl-4 border-l-2 border-gray-200 dark:border-gray-700">
                                            @foreach($fb->replies as $reply)
                                            <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3">
                                                <div class="flex items-start gap-2">
                                                    <div class="w-8 h-8 bg-gradient-to-br {{ $reply->user_id == auth()->id() ? 'from-blue-400 to-indigo-500' : 'from-gray-400 to-gray-600' }} rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                                        {{ strtoupper(substr($reply->user->name ?? 'U', 0, 2)) }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $reply->user->name ?? 'User' }}</span>

                                                            @if($reply->user->role === 'kepala_sekolah')
                                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                                                                    <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                                    </svg>
                                                                    Kepsek
                                                                </span>
                                                            @elseif($reply->user_id == auth()->id())
                                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                                    Anda
                                                                </span>
                                                            @endif

                                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-gray-700 dark:text-gray-300">{{ $reply->komentar }}</p>

                                                        <!-- Reply to reply button -->
                                                        @if($supervisi->status !== 'completed')
                                                        <div class="mt-2">
                                                            <button onclick="toggleReplyForm({{ $reply->id }})" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                                </svg>
                                                                Balas
                                                            </button>
                                                        </div>

                                                        <!-- Reply to reply form -->
                                                        <div id="reply-form-{{ $reply->id }}" class="hidden mt-2">
                                                            <form action="{{ route('kepala.evaluasi.feedback', $supervisi->id) }}" method="POST" class="space-y-2">
                                                                @csrf
                                                                <input type="hidden" name="parent_id" value="{{ $fb->id }}">
                                                                <textarea
                                                                    name="komentar"
                                                                    rows="2"
                                                                    required
                                                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                                                                    placeholder="Tulis balasan Anda..."></textarea>
                                                                <div class="flex gap-2">
                                                                    <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded transition-all">
                                                                        Kirim
                                                                    </button>
                                                                    <button type="button" onclick="toggleReplyForm({{ $reply->id }})" class="px-2 py-1 text-gray-600 dark:text-gray-400 text-xs font-semibold rounded transition-all">
                                                                        Batal
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        @endif
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
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada komentar</p>
                        </div>
                    @endif
                    </div>
                </div>
            </div>

            <!-- Card 5: Berikan Feedback -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 px-4 py-3 sm:px-6 sm:py-4">
                    <h3 class="text-sm sm:text-base font-semibold text-white">Berikan Feedback</h3>
                </div>
                <!-- Card Content -->
                <div class="p-3 sm:p-4 md:p-6">
                    @if($supervisi->status === 'completed')
                        <!-- Status Completed Message -->
                        <div class="text-center py-6 sm:py-8">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2">Supervisi Telah Selesai Ditinjau</h4>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-3 sm:mb-4">
                                Supervisi ini telah ditandai sebagai selesai. Anda masih dapat melihat riwayat feedback di atas.
                            </p>
                            <a href="{{ route('kepala.evaluasi.index') }}"
                               class="inline-flex items-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-5 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Daftar Evaluasi
                            </a>
                        </div>
                    @else
                    <form action="{{ route('kepala.evaluasi.feedback', $supervisi->id) }}" method="POST" class="space-y-4 sm:space-y-5">
                @csrf
                
                <div>
                    <label for="komentar" class="block text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100 mb-1.5 sm:mb-2">
                        Komentar dan Saran
                    </label>
                    <textarea
                        name="komentar"
                        id="komentar"
                        rows="4"
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-xs sm:text-sm resize-none"
                        placeholder="Berikan feedback, komentar, atau saran untuk guru..."
                        required></textarea>
                </div>

                <div class="flex items-start gap-2 p-3 sm:p-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg border border-amber-300 dark:border-amber-700">
                    <input
                        type="checkbox"
                        name="is_revision_request"
                        id="is_revision_request"
                        value="1"
                        class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 bg-white dark:bg-gray-700 border-amber-400 dark:border-amber-600 rounded focus:ring-0 mt-0.5">
                    <label for="is_revision_request" class="text-xs sm:text-sm font-semibold text-amber-900 dark:text-amber-200 cursor-pointer">
                        Minta revisi untuk supervisi ini
                    </label>
                </div>

                <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-2 sm:gap-3 pt-3 sm:pt-4">
                    <a href="{{ route('kepala.evaluasi.index') }}"
                       class="px-4 py-2 sm:px-5 sm:py-2.5 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100 text-xs sm:text-sm font-medium rounded-lg transition-colors border border-gray-300 dark:border-gray-600 text-center">
                        Kembali
                    </a>
                    
                    <button type="submit"
                            class="px-4 py-2 sm:px-5 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors">
                        Kirim Feedback
                    </button>
                    
                    @if($supervisi->status === 'under_review')
                    <button type="button"
                            id="completeButton"
                            onclick="confirmComplete()"
                            class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-5 sm:py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-xs sm:text-sm font-bold rounded-lg transition-all duration-200 cursor-pointer active:scale-95">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="hidden sm:inline">Tandai Selesai Ditinjau</span>
                        <span class="sm:hidden">Selesai</span>
                    </button>
                    @endif
                </div>
                    </form>
                    @endif
                </div>
            </div>
            @endif

        </div>
        <!-- End Vertical Card Layout -->
    </div>
</div>

<!-- Complete Form (Hidden) -->
<form id="completeForm" action="{{ route('kepala.evaluasi.complete', $supervisi->id) }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Revision Request Modal -->
<div id="revisionModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-60 z-50 hidden" style="display: none;">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Minta Revisi</h3>
                    <button
                        type="button"
                        onclick="hideRevisionModal()"
                        class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('kepala.evaluasi.revision', $supervisi->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="revision_notes" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                            Catatan Revisi <span class="text-red-600 dark:text-red-400">*</span>
                        </label>
                        <textarea
                            name="revision_notes"
                            id="revision_notes"
                            rows="4"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-red-500 dark:focus:border-red-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm resize-none"
                            placeholder="Jelaskan apa yang perlu direvisi..."
                        ></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            onclick="hideRevisionModal()"
                            class="px-4 py-2 text-gray-900 dark:text-gray-100 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 text-sm font-medium rounded-md transition-colors border border-gray-300 dark:border-gray-600">
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white text-sm font-medium rounded-md transition-colors">
                            Kirim Permintaan Revisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Complete Confirmation Function
function confirmComplete() {
    const completeButton = document.getElementById('completeButton');

    // Prevent submission if button is disabled
    if (completeButton && completeButton.disabled) {
        return false;
    }

    showConfirmModal(
        'Apakah Anda yakin ingin menandai supervisi ini sebagai selesai ditinjau? Status akan berubah menjadi "Telah Selesai" dan supervisi akan dipindahkan ke tab Telah Selesai.',
        'Konfirmasi Selesaikan Tinjauan',
        function() {
            document.getElementById('completeForm').submit();
        },
        { type: 'info', confirmText: 'Ya, Selesaikan' }
    );
}

// Revision Modal Functions
function showRevisionModal() {
    const modal = document.getElementById('revisionModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideRevisionModal() {
    const modal = document.getElementById('revisionModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}

// Close revision modal when clicking outside
document.getElementById('revisionModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideRevisionModal();
    }
});

// Handle ESC key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreviewModal();
        hideRevisionModal();
    }
});

// Toggle Complete Button based on Revision Checkbox
document.addEventListener('DOMContentLoaded', function() {
    const revisionCheckbox = document.getElementById('is_revision_request');
    const completeButton = document.getElementById('completeButton');

    if (revisionCheckbox && completeButton) {
        revisionCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Disable button when revision is checked
                completeButton.disabled = true;
                completeButton.classList.remove('bg-gradient-to-r', 'from-emerald-500', 'to-green-600', 'hover:from-emerald-600', 'hover:to-green-700', 'cursor-pointer');
                completeButton.classList.add('bg-gray-300', 'dark:bg-gray-700', 'text-gray-500', 'dark:text-gray-400', 'cursor-not-allowed', 'shadow-none');
            } else {
                // Enable button when revision is unchecked
                completeButton.disabled = false;
                completeButton.classList.remove('bg-gray-300', 'dark:bg-gray-700', 'text-gray-500', 'dark:text-gray-400', 'cursor-not-allowed');
                completeButton.classList.add('bg-gradient-to-r', 'from-emerald-500', 'to-green-600', 'hover:from-emerald-600', 'hover:to-green-700', 'cursor-pointer', 'shadow-none');
            }
        });
    }
});

// Toggle reply form visibility
function toggleReplyForm(id) {
    const form = document.getElementById('reply-form-' + id);
    if (form) {
        form.classList.toggle('hidden');
    }
}
</script>

@endsection



