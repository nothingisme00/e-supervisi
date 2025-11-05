@extends('layouts.modern')

@section('page-title', 'Detail Supervisi')

@section('content')
<div class="w-full lg:w-3/4 mx-auto">
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
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden">
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-1">Detail Supervisi</h2>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->format('d F Y') }}</p>
                </div>
            </div>
            <div>
                @if($supervisi->status == 'draft')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md border-l-4 border-gray-500">
                        <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                        Draft
                    </span>
                @elseif($supervisi->status == 'submitted')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-300 text-sm font-medium rounded-md border-l-4 border-yellow-500">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                        Disubmit
                    </span>
                @elseif($supervisi->status == 'under_review')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-md border-l-4 border-blue-500">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        Sedang Direview
                    </span>
                @elseif($supervisi->status == 'completed')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-sm font-medium rounded-md border-l-4 border-green-500">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        Selesai
                    </span>
                @elseif($supervisi->status == 'revision')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300 text-sm font-medium rounded-md border-l-4 border-orange-500">
                        <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                        Perlu Revisi
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if($supervisi->catatan)
    <div class="px-6 pb-6">
        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-r-lg p-4">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Catatan Supervisi</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $supervisi->catatan }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Grid 2x2 untuk 4 Section Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start mb-8">
    
    <!-- Card 1: Dokumen Evaluasi Diri -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="h-1 bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500"></div>
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Dokumen Evaluasi Diri</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <span class="text-purple-600 dark:text-purple-400 font-semibold">{{ $supervisi->dokumenEvaluasi->count() }}</span> dari <span class="font-semibold">7</span> dokumen terupload
            </p>
        </div>
        <div class="p-6">
                @if($supervisi->dokumenEvaluasi->count() > 0)
                    <div class="space-y-3 max-h-96 overflow-y-auto">
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
        <div class="h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-500"></div>
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Link Pembelajaran</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Link video dan meeting pembelajaran</p>
        </div>
        <div class="p-6 space-y-4">
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
        <div class="h-1 bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600"></div>
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Link Pembelajaran</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Data link pembelajaran belum tersedia</p>
        </div>
        <div class="p-6 text-center py-8">
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
        <div class="h-1 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500"></div>
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Refleksi Pembelajaran</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Hasil refleksi dari proses pembelajaran</p>
        </div>
        <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
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
    @else
    <!-- Empty State untuk Refleksi Pembelajaran -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="h-1 bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600"></div>
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Refleksi Pembelajaran</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Data refleksi pembelajaran belum tersedia</p>
        </div>
        <div class="p-6 text-center py-8">
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
        <div class="h-1 bg-gradient-to-r from-amber-500 via-orange-500 to-yellow-500"></div>
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Feedback dari Kepala Sekolah</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <span class="text-amber-600 dark:text-amber-400 font-semibold">{{ $supervisi->feedback->count() }}</span> feedback diterima
            </p>
        </div>
        <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
        @foreach($supervisi->feedback as $fb)
            <div class="border-l-4 border-amber-500 bg-amber-50 dark:bg-amber-900/20 rounded-r-lg p-3">
                <div class="flex items-start gap-2">
                    <div class="w-9 h-9 bg-amber-600 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                        {{ substr($fb->admin->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $fb->admin->name ?? 'Kepala Sekolah' }}</div>
                            <div class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($fb->created_at)->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $fb->feedback }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@else
<!-- Empty State untuk Feedback -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
    <div class="h-1 bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600"></div>
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Feedback dari Kepala Sekolah</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">Belum ada feedback</p>
    </div>
    <div class="p-6 text-center py-8">
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
<div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <a href="{{ route('guru.home') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-all duration-200 hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Beranda
        </a>

        @if(in_array($supervisi->status, ['draft', 'revision']))
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
    @endif
</div>
</div>

@endsection
