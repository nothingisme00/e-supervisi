@extends('layouts.modern')

@section('page-title', 'Detail Supervisi')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="w-full lg:w-3/4 mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('guru.home') }}" 
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-all duration-200 group">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">Kembali ke Beranda</span>
            </a>
        </div>

        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden mb-6">
            <!-- Decorative Header Bar -->
            <div class="h-2 bg-linear-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
            
            <div class="p-6">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 rounded-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg ring-4 ring-indigo-100 dark:ring-indigo-900/50">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">Detail Supervisi Pembelajaran</h1>
                            <p class="text-slate-600 dark:text-gray-300 text-sm">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($supervisi->status == 'draft')
                            <span class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 text-gray-700 border-2 border-gray-200 shadow-sm">
                                <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                                Draft
                            </span>
                        @elseif($supervisi->status == 'submitted')
                            <span class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-amber-100 text-amber-700 border-2 border-amber-200 shadow-sm">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2.5 animate-pulse"></span>
                                Menunggu Peninjauan
                            </span>
                        @elseif($supervisi->status == 'under_review')
                            <span class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-indigo-100 text-indigo-700 border-2 border-indigo-200 shadow-sm">
                                <span class="w-2 h-2 bg-indigo-600 rounded-full mr-3.5 animate-pulse"></span>
                                Sedang Ditinjau
                            </span>
                        @elseif($supervisi->status == 'completed')
                            <span class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-emerald-100 text-emerald-700 border-2 border-emerald-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Telah Ditinjau
                            </span>
                        @elseif($supervisi->status == 'revision')
                            <span class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-rose-100 text-rose-700 border-2 border-rose-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Perlu Revisi
                            </span>
                        @endif
                    </div>
                </div>
                
                @if($supervisi->catatan)
                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 dark:border-blue-600 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-1">Catatan Supervisi</p>
                            <p class="text-sm text-blue-800 dark:text-blue-300">{{ $supervisi->catatan }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Vertical Card Layout -->
        <div class="space-y-6">
    
            <!-- Card 1: Dokumen Evaluasi Diri -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-blue-600 dark:bg-blue-700 px-6 py-4 border-b border-blue-700 dark:border-blue-800">
                    <h3 class="text-base font-semibold text-white">Dokumen Evaluasi Diri</h3>
                </div>
                <!-- Card Content -->
                <div class="p-6 pb-8">
                @if($supervisi->dokumenEvaluasi->count() > 0)
                    <div class="max-h-96 overflow-y-auto space-y-2">
                        @foreach($supervisi->dokumenEvaluasi as $dokumen)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-700 hover:bg-purple-50 dark:hover:bg-purple-900/10 transition-all duration-200">
                                <div class="w-10 h-10 @if($dokumen->tipe_file == 'pdf') bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 @else bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 @endif rounded-lg flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110">
                                    @if($dokumen->tipe_file == 'pdf')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ ucfirst(str_replace('_', ' ', $dokumen->jenis_dokumen)) }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $dokumen->nama_file }}</div>
                                </div>
                                <a href="{{ asset('storage/' . $dokumen->path_file) }}" target="_blank" class="p-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-all duration-200 flex-shrink-0 hover:scale-105 active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada dokumen yang diupload</p>
                    </div>
                @endif
                </div>
            </div>

    <!-- Card 2: Link Pembelajaran -->
    @if($supervisi->prosesPembelajaran)
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="bg-linear-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 px-6 py-4 border-b border-purple-600 dark:border-purple-800">
            <h3 class="text-base font-semibold text-white">Link Pembelajaran</h3>
        </div>
        <div class="p-6 pb-8 space-y-4">
                <!-- Link Video -->
                <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-r-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Link Video Pembelajaran</div>
                            <a href="{{ $supervisi->prosesPembelajaran->link_video }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400 hover:underline break-all">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                {{ $supervisi->prosesPembelajaran->link_video }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Link Meeting -->
                @if($supervisi->prosesPembelajaran->link_meeting)
                <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-r-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Link Meeting</div>
                            <a href="{{ $supervisi->prosesPembelajaran->link_meeting }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400 hover:underline break-all">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                {{ $supervisi->prosesPembelajaran->link_meeting }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @else
    <!-- Empty State untuk Link Pembelajaran -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="bg-linear-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 px-6 py-4 border-b border-purple-600 dark:border-purple-800">
            <h3 class="text-base font-semibold text-white">Link Pembelajaran</h3>
        </div>
        <div class="p-6 pb-8 text-center py-8">
            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada data link pembelajaran</p>
        </div>
    </div>
    @endif

    <!-- Card 3: Refleksi Pembelajaran -->
    @if($supervisi->prosesPembelajaran)
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="bg-linear-to-r from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 px-6 py-4 border-b border-emerald-600 dark:border-emerald-800">
            <h3 class="text-base font-semibold text-white">Refleksi Pembelajaran</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3 max-h-96 overflow-y-auto pb-2">
                @php
                    $refleksiQuestions = [
                        'refleksi_1' => 'Apa tujuan pembelajaran yang ingin dicapai dalam pembelajaran ini?',
                        'refleksi_2' => 'Bagaimana strategi atau metode pembelajaran yang digunakan?',
                        'refleksi_3' => 'Apa saja tantangan yang dihadapi selama proses pembelajaran?',
                        'refleksi_4' => 'Bagaimana respon dan partisipasi siswa selama pembelajaran?',
                        'refleksi_5' => 'Apa rencana tindak lanjut untuk meningkatkan kualitas pembelajaran?'
                    ];
                @endphp

                @foreach($refleksiQuestions as $key => $question)
                    <div class="border-l-4 @if($loop->iteration == 1) border-green-500 bg-green-50 dark:bg-green-900/20 @elseif($loop->iteration == 2) border-blue-500 bg-blue-50 dark:bg-blue-900/20 @elseif($loop->iteration == 3) border-purple-500 bg-purple-50 dark:bg-purple-900/20 @elseif($loop->iteration == 4) border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 @else border-teal-500 bg-teal-50 dark:bg-teal-900/20 @endif rounded-r-lg p-3">
                        <div class="flex items-start gap-2">
                            <span class="flex items-center justify-center w-7 h-7 @if($loop->iteration == 1) bg-green-600 text-white @elseif($loop->iteration == 2) bg-blue-600 text-white @elseif($loop->iteration == 3) bg-purple-600 text-white @elseif($loop->iteration == 4) bg-indigo-600 text-white @else bg-teal-600 text-white @endif rounded-lg text-xs font-bold flex-shrink-0">{{ $loop->iteration }}</span>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ $question }}</div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $supervisi->prosesPembelajaran->$key }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <!-- Empty State untuk Refleksi Pembelajaran -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="bg-linear-to-r from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 px-6 py-4 border-b border-emerald-600 dark:border-emerald-800">
            <h3 class="text-base font-semibold text-white">Refleksi Pembelajaran</h3>
        </div>
        <div class="p-6 pb-8 text-center py-8">
            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada data refleksi pembelajaran</p>
        </div>
    </div>
    @endif

    <!-- Card 4: Feedback dari Kepala Sekolah -->
    @if($supervisi->feedback && $supervisi->feedback->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="bg-orange-600 dark:bg-orange-700 px-6 py-4">
            <h3 class="text-base font-semibold text-white">Feedback dari Kepala Sekolah</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3 max-h-96 overflow-y-auto pb-2">
        @foreach($supervisi->feedback as $fb)
            <div class="border-l-4 {{ $fb->is_revision_request ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-amber-500 bg-amber-50 dark:bg-amber-900/20' }} rounded-r-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-md">
                        {{ strtoupper(substr($fb->user->name ?? 'KS', 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $fb->user->name ?? 'Kepala Sekolah' }}</span>
                                @if($fb->is_revision_request)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        Revisi Diminta
                                    </span>
                                @endif
                            </div>
                            <div class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $fb->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $fb->komentar }}</p>
                        
                        @if($fb->is_revision_request)
                            <div class="mt-3 p-3 bg-red-100 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-800/50">
                                <p class="text-xs font-semibold text-red-800 dark:text-red-300 mb-1">⚠️ Tindakan Diperlukan</p>
                                <p class="text-xs text-red-700 dark:text-red-400">Silakan lakukan revisi sesuai feedback di atas dan submit ulang supervisi Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
            </div>
        </div>
    </div>
@else
<!-- Empty State untuk Feedback -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
    <div class="bg-orange-600 dark:bg-orange-700 px-6 py-4">
        <h3 class="text-base font-semibold text-white">Feedback dari Kepala Sekolah</h3>
    </div>
    <div class="p-6 pb-8 text-center py-8">
        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada feedback dari kepala sekolah</p>
    </div>
</div>
@endif

</div> <!-- End Grid 2x2 -->

<!-- Action Buttons Section -->
@if(in_array($supervisi->status, ['draft', 'revision']))
<div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
    <div class="flex justify-center">
        <a href="{{ route('guru.supervisi.continue', $supervisi->id) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 @if($supervisi->status == 'revision') bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 @else bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 @endif text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
        @if($supervisi->status == 'revision')
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Lakukan Revisi
        @else
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
            Lanjutkan Supervisi
        @endif
    </a>
    </div>
</div>
@endif

    </div> <!-- End container -->
</div> <!-- End min-h-screen -->

@endsection
