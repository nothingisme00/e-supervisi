@extends('layouts.modern')

@section('page-title', 'Detail Supervisi')

@section('content')
<!-- Back Button -->
<div class="mb-4">
    <a href="{{ route('guru.home') }}" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 text-sm font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Beranda
    </a>
</div>

<!-- Header Card -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden shadow-sm">
    <!-- Accent Line -->
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
    
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-5 bg-gradient-to-r from-indigo-50/50 to-blue-50/50 dark:from-indigo-900/20 dark:to-blue-900/20">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Supervisi</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                @if($supervisi->status == 'draft')
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-sm font-semibold rounded-lg border-l-4 border-gray-500 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Draft
                    </span>
                @elseif($supervisi->status == 'submitted')
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-sm font-semibold rounded-lg border-l-4 border-yellow-500 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Disubmit
                    </span>
                @elseif($supervisi->status == 'under_review')
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-sm font-semibold rounded-lg border-l-4 border-blue-500 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Sedang Direview
                    </span>
                @elseif($supervisi->status == 'completed')
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-sm font-semibold rounded-lg border-l-4 border-green-500 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Selesai
                    </span>
                @elseif($supervisi->status == 'revision')
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 text-sm font-semibold rounded-lg border-l-4 border-orange-500 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Perlu Revisi
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if($supervisi->catatan)
    <div class="p-6">
        <div class="relative bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5 shadow-sm overflow-hidden">
            <!-- Accent Line -->
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-indigo-500"></div>
            
            <div class="flex gap-4 ml-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-blue-900 dark:text-blue-100 mb-2">Catatan Supervisi</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $supervisi->catatan }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Dokumen Evaluasi Card -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden shadow-sm">
    <!-- Accent Line -->
    <div class="h-1 bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500"></div>
    
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-purple-50/50 to-pink-50/50 dark:from-purple-900/20 dark:to-pink-900/20">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Dokumen Evaluasi Diri</h2>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    <span class="text-purple-600 dark:text-purple-400 font-semibold">{{ $supervisi->dokumenEvaluasi->count() }}</span> dari <span class="font-semibold">7</span> dokumen terupload
                </p>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($supervisi->dokumenEvaluasi->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($supervisi->dokumenEvaluasi as $dokumen)
                    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl p-4 hover:shadow-md transition-all duration-200 overflow-hidden group">
                        <!-- Accent Line -->
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br @if($dokumen->tipe_file == 'pdf') from-red-500 to-rose-600 @else from-blue-500 to-indigo-600 @endif rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                @if($dokumen->tipe_file == 'pdf')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold text-gray-900 dark:text-white mb-1.5">{{ ucfirst(str_replace('_', ' ', $dokumen->jenis_dokumen)) }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-2 truncate">{{ $dokumen->nama_file }}</div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-1 bg-white dark:bg-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-300 rounded border border-gray-200 dark:border-gray-600">{{ strtoupper($dokumen->tipe_file) }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($dokumen->ukuran_file / 1024, 2) }} KB</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $dokumen->path_file) }}" target="_blank" class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl transition-all shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Belum ada dokumen yang diupload</p>
            </div>
        @endif
    </div>
</div>

<!-- Proses Pembelajaran Card -->
@if($supervisi->prosesPembelajaran)
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden shadow-sm">
    <!-- Accent Line -->
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-500"></div>
    
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-indigo-50/50 to-blue-50/50 dark:from-indigo-900/20 dark:to-blue-900/20">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Link Pembelajaran</h2>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Link video dan meeting pembelajaran</p>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-4">
        <!-- Link Video -->
        <div class="relative bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border border-red-200 dark:border-red-800 rounded-xl p-5 hover:shadow-md transition-all duration-200 overflow-hidden group">
            <!-- Accent Line -->
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-red-500 to-rose-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="flex items-start gap-4 ml-3">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-bold text-gray-900 dark:text-white mb-2">Link Video Pembelajaran</div>
                    <a href="{{ $supervisi->prosesPembelajaran->link_video }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium hover:underline break-all">
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
        <div class="relative bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5 hover:shadow-md transition-all duration-200 overflow-hidden group">
            <!-- Accent Line -->
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="flex items-start gap-4 ml-3">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-bold text-gray-900 dark:text-white mb-2">Link Meeting</div>
                    <a href="{{ $supervisi->prosesPembelajaran->link_meeting }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium hover:underline break-all">
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

<!-- Refleksi Pembelajaran Card -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden shadow-sm">
    <!-- Accent Line -->
    <div class="h-1 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500"></div>
    
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-green-50/50 to-teal-50/50 dark:from-green-900/20 dark:to-teal-900/20">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Refleksi Pembelajaran</h2>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hasil refleksi dari proses pembelajaran</p>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-4">
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
            <div class="relative bg-gradient-to-br from-gray-50 to-green-50/30 dark:from-gray-700/50 dark:to-green-900/10 border border-gray-200 dark:border-gray-600 rounded-xl p-5 hover:shadow-md transition-all duration-200 overflow-hidden group">
                <!-- Accent Line -->
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-green-500 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="flex items-start gap-4 ml-3">
                    <span class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 text-white rounded-xl text-sm font-bold flex-shrink-0 shadow-md">{{ $loop->iteration }}</span>
                    <div class="flex-1">
                        <div class="text-sm font-bold text-gray-900 dark:text-white mb-2">{{ $question }}</div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $supervisi->prosesPembelajaran->$key }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@else
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden shadow-sm">
    <!-- Accent Line -->
    <div class="h-1 bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600"></div>
    
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-gray-50/50 to-gray-100/50 dark:from-gray-900/20 dark:to-gray-800/20">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Proses Pembelajaran</h2>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Data proses pembelajaran belum tersedia</p>
            </div>
        </div>
    </div>
    <div class="p-6 text-center py-12">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
            </svg>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Belum ada data proses pembelajaran</p>
    </div>
</div>
@endif

<!-- Feedback Card -->
@if($supervisi->feedback && $supervisi->feedback->count() > 0)
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden shadow-sm">
    <!-- Accent Line -->
    <div class="h-1 bg-gradient-to-r from-yellow-500 via-orange-500 to-amber-500"></div>
    
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-yellow-50/50 to-orange-50/50 dark:from-yellow-900/20 dark:to-orange-900/20">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Feedback dari Kepala Sekolah</h2>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    <span class="text-yellow-600 dark:text-yellow-400 font-semibold">{{ $supervisi->feedback->count() }}</span> feedback diterima
                </p>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-4">
        @foreach($supervisi->feedback as $fb)
            <div class="relative bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-5 hover:shadow-md transition-all duration-200 overflow-hidden group">
                <!-- Accent Line -->
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-yellow-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="flex items-start gap-4 ml-3">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center text-white text-base font-bold shadow-md">
                            {{ substr($fb->admin->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $fb->admin->name ?? 'Kepala Sekolah' }}</div>
                            <div class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 dark:text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($fb->created_at)->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $fb->feedback }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Action Buttons -->
<div class="flex items-center justify-between">
    <a href="{{ route('guru.home') }}" style="background-color: #eab308; color: white;" class="inline-flex items-center justify-center gap-2 px-5 py-3 font-semibold rounded-lg cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Beranda
    </a>

    @if(in_array($supervisi->status, ['draft', 'revision']))
        <a href="{{ route('guru.supervisi.continue', $supervisi->id) }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-semibold rounded-lg cursor-pointer transition-colors">
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
    @endif
</div>

@endsection
