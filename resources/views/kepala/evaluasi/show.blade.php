@extends('layouts.modern')

@section('page-title', 'Detail Evaluasi Supervisi - ' . $supervisi->user->name)

@section('content')

<div class="min-h-screen bg-linear-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
    <div class="w-full lg:w-3/4 mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 dark:border-emerald-600 rounded-r-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-emerald-700 dark:text-emerald-300 text-sm font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-slate-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <div class="w-16 h-16 rounded-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $supervisi->user->name }}</h1>
                        <p class="text-slate-600 dark:text-gray-300 mt-1">{{ $supervisi->user->email }}</p>
                        <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">NIK: {{ $supervisi->user->nik }}</p>
                    </div>
                </div>
                <div class="text-right">
                    @if($supervisi->status === 'submitted')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 mb-2">
                            <span class="w-2 h-2 bg-amber-500 dark:bg-amber-400 rounded-full mr-2 animate-pulse"></span>
                            Menunggu Peninjauan
                        </span>
                        <form action="{{ route('kepala.evaluasi.startReview', $supervisi->id) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-2.5 bg-linear-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Mulai Review
                            </button>
                        </form>
                    @elseif($supervisi->status === 'under_review')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            <span class="w-2 h-2 bg-indigo-500 dark:bg-indigo-400 rounded-full mr-2 animate-pulse"></span>
                            Sedang Ditinjau
                        </span>
                    @elseif($supervisi->status === 'completed')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                            <span class="w-2 h-2 bg-emerald-500 dark:bg-emerald-400 rounded-full mr-2"></span>
                            Telah Ditinjau
                        </span>
                    @elseif($supervisi->status === 'revision')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-200">
                            <span class="w-2 h-2 bg-rose-500 dark:bg-rose-400 rounded-full mr-2"></span>
                            Perlu Revisi
                        </span>
                    @endif
                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-2">Disubmit: {{ $supervisi->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Grid Layout: 2x2 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start mb-8">
            
            <!-- Dokumen Evaluasi Diri -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="h-1 bg-linear-to-r from-blue-500 to-indigo-500"></div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Dokumen Evaluasi Diri</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto space-y-2">
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
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-gray-700 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
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
                                        <p class="text-sm font-medium text-slate-700 dark:text-gray-200">
                                            {{ $dokumen->nama_file ?? 'Dokumen ' . ($index + 1) }}
                                        </p>
                                        @if($dokumen->deskripsi)
                                            <p class="text-xs text-slate-500 dark:text-gray-400">{{ $dokumen->deskripsi }}</p>
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
                                       class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-700 border border-slate-200 dark:border-gray-600 hover:bg-slate-50 dark:hover:bg-gray-600 text-slate-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Preview
                                    </a>
                                    @endif

                                    <a href="{{ route('kepala.evaluasi.download', $dokumen->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <!-- Link Pembelajaran -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="h-1 bg-linear-to-r from-purple-500 to-pink-500"></div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Link Pembelajaran</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto space-y-3">
                        @if($supervisi->prosesPembelajaran)
                            @if($supervisi->prosesPembelajaran->video_link)
                                <div class="p-4 bg-linear-to-r from-red-50 to-pink-50 dark:from-red-900/10 dark:to-pink-900/10 rounded-lg border border-red-100 dark:border-red-800/30">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0 pr-2">
                                            <p class="text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1">Link Video Pembelajaran</p>
                                            <a href="{{ $supervisi->prosesPembelajaran->video_link }}" 
                                               target="_blank"
                                               class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 underline break-all">
                                                {{ $supervisi->prosesPembelajaran->video_link }}
                                            </a>
                                        </div>
                                        <a href="{{ $supervisi->prosesPembelajaran->video_link }}" 
                                           target="_blank"
                                           class="ml-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($supervisi->prosesPembelajaran->meeting_link)
                                <div class="p-4 bg-linear-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-lg border border-blue-100 dark:border-blue-800/30">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0 pr-2">
                                            <p class="text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1">Link Meeting/Zoom</p>
                                            <a href="{{ $supervisi->prosesPembelajaran->meeting_link }}" 
                                               target="_blank"
                                               class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 underline break-all">
                                                {{ $supervisi->prosesPembelajaran->meeting_link }}
                                            </a>
                                        </div>
                                        <a href="{{ $supervisi->prosesPembelajaran->meeting_link }}" 
                                           target="_blank"
                                           class="ml-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if(!$supervisi->prosesPembelajaran->video_link && !$supervisi->prosesPembelajaran->meeting_link)
                                <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Tidak ada link pembelajaran</p>
                            @endif
                        @else
                            <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Tidak ada data pembelajaran</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Refleksi Pembelajaran -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="h-1 bg-linear-to-r from-emerald-500 to-teal-500"></div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Refleksi Pembelajaran</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto space-y-4">
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
                                    <div class="p-3 bg-slate-50 dark:bg-gray-700 rounded-lg">
                                        <p class="text-xs font-semibold text-slate-600 dark:text-gray-300 mb-2">{{ $index + 1 }}. {{ $reflection['label'] }}</p>
                                        <p class="text-sm text-slate-700 dark:text-gray-200 leading-relaxed">{{ $reflection['value'] }}</p>
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

            <!-- Feedback yang Ada -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="h-1 bg-linear-to-r from-amber-500 to-orange-500"></div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Riwayat Feedback</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto space-y-3">
                        @forelse($supervisi->feedback as $fb)
                            <div class="p-4 bg-linear-to-r from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10 rounded-lg border border-amber-100 dark:border-amber-800/30">
                                <div class="flex items-start justify-between mb-2">
                                    <p class="text-xs font-semibold text-slate-600 dark:text-gray-300">{{ $fb->created_at->format('d M Y, H:i') }}</p>
                                    @if($fb->is_revision_request)
                                        <span class="text-xs px-2 py-1 bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300 rounded-full font-medium">
                                            Revisi Diminta
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-700 dark:text-gray-200 leading-relaxed">{{ $fb->komentar }}</p>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Belum ada feedback</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <!-- Feedback Form Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-slate-200 dark:border-gray-700 p-6 mb-6">
            <div class="h-1 bg-linear-to-r from-rose-500 to-pink-500 -mt-6 -mx-6 mb-6"></div>
            
            <div class="flex items-center mb-6">
                <svg class="w-6 h-6 text-rose-600 dark:text-rose-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z"></path>
                </svg>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">Berikan Feedback</h3>
            </div>

            <form action="{{ route('kepala.evaluasi.feedback', $supervisi->id) }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="komentar" class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-3">
                        Komentar dan Saran
                    </label>
                    <textarea 
                        name="komentar" 
                        id="komentar" 
                        rows="6" 
                        class="w-full px-4 py-3 border border-slate-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-rose-500 dark:focus:ring-rose-400 focus:border-transparent bg-white dark:bg-gray-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-400 text-sm leading-relaxed resize-none transition-all duration-200"
                        placeholder="Berikan feedback, komentar, atau saran untuk guru..."
                        required></textarea>
                </div>

                <div class="flex items-center space-x-3">
                    <input 
                        type="checkbox" 
                        name="is_revision_request" 
                        id="is_revision_request"
                        value="1"
                        class="w-4 h-4 text-rose-600 bg-white dark:bg-gray-700 border-slate-300 dark:border-gray-600 rounded focus:ring-rose-500 dark:focus:ring-rose-400 focus:ring-2">
                    <label for="is_revision_request" class="text-sm font-medium text-slate-700 dark:text-gray-300">
                        Minta revisi untuk supervisi ini
                    </label>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('kepala.evaluasi.index') }}" 
                       class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200">
                        Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Kirim Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
                        @if($supervisi->prosesPembelajaran)
                            @if($supervisi->prosesPembelajaran->video_link)
                                <div class="p-4 bg-linear-to-r from-red-50 to-pink-50 dark:from-red-900/10 dark:to-pink-900/10 rounded-lg border border-red-200 dark:border-red-800/30">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0 pr-3">
                                            <p class="text-sm font-bold text-red-700 dark:text-red-300 mb-2">Link Video Pembelajaran</p>
                                            <a href="{{ $supervisi->prosesPembelajaran->video_link }}" 
                                               target="_blank"
                                               class="text-base text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 underline break-all">
                                                {{ $supervisi->prosesPembelajaran->video_link }}
                                            </a>
                                        </div>
                                        <a href="{{ $supervisi->prosesPembelajaran->video_link }}" 
                                           target="_blank"
                                           class="ml-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($supervisi->prosesPembelajaran->meeting_link)
                                <div class="p-4 bg-linear-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-lg border border-blue-200 dark:border-blue-800/30">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0 pr-3">
                                            <p class="text-sm font-bold text-blue-700 dark:text-blue-300 mb-2">Link Meeting/Zoom</p>
                                            <a href="{{ $supervisi->prosesPembelajaran->meeting_link }}" 
                                               target="_blank"
                                               class="text-base text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 underline break-all">
                                                {{ $supervisi->prosesPembelajaran->meeting_link }}
                                            </a>
                                        </div>
                                        <a href="{{ $supervisi->prosesPembelajaran->meeting_link }}" 
                                           target="_blank"
                                           class="ml-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if(!$supervisi->prosesPembelajaran->video_link && !$supervisi->prosesPembelajaran->meeting_link)
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-purple-400 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 dark:text-gray-400 text-sm font-medium">Tidak ada link pembelajaran</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-slate-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-500 dark:text-gray-400 text-sm font-medium">Tidak ada data pembelajaran</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            

            <!-- Refleksi Pembelajaran -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="h-1.5 bg-linear-to-r from-emerald-500 via-teal-500 to-green-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-linear-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-800 dark:text-white">Refleksi Pembelajaran</h3>
                                <p class="text-sm text-slate-600 dark:text-gray-400">Refleksi dan evaluasi diri guru</p>
                            </div>
                        </div>
                        @if($supervisi->prosesPembelajaran)
                            @php
                                $reflectionCount = collect([
                                    $supervisi->prosesPembelajaran->refleksi_1,
                                    $supervisi->prosesPembelajaran->refleksi_2, 
                                    $supervisi->prosesPembelajaran->refleksi_3,
                                    $supervisi->prosesPembelajaran->refleksi_4,
                                    $supervisi->prosesPembelajaran->refleksi_5
                                ])->filter()->count();
                            @endphp
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-full">
                                <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">{{ $reflectionCount }}/5 jawaban</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Area Refleksi -->
                    <div class="max-h-[500px] overflow-y-auto space-y-5 pr-2 scrollbar-thin scrollbar-thumb-emerald-200 dark:scrollbar-thumb-emerald-800 scrollbar-track-transparent">
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
                                    <div class="group relative">
                                        <div class="p-5 bg-linear-to-r from-slate-50 to-emerald-50/30 dark:from-gray-700 dark:to-emerald-900/10 rounded-xl border border-slate-200 dark:border-gray-600 hover:border-emerald-300 dark:hover:border-emerald-600 transition-all hover:shadow-md">
                                            <!-- Nomor lingkaran -->
                                            <div class="absolute -left-2 -top-2 w-8 h-8 bg-linear-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                {{ $index + 1 }}
                                            </div>
                                            
                                            <div class="ml-4">
                                                <p class="text-sm font-bold text-emerald-700 dark:text-emerald-300 mb-3 leading-relaxed">{{ $reflection['label'] }}</p>
                                                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-slate-100 dark:border-gray-700">
                                                    <p class="text-base text-slate-700 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">{{ $reflection['value'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if(!$supervisi->prosesPembelajaran->refleksi_1 && !$supervisi->prosesPembelajaran->refleksi_2 && !$supervisi->prosesPembelajaran->refleksi_3 && !$supervisi->prosesPembelajaran->refleksi_4 && !$supervisi->prosesPembelajaran->refleksi_5)
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-emerald-400 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 dark:text-gray-400 text-lg font-medium">Tidak ada refleksi</p>
                                    <p class="text-slate-400 dark:text-gray-500 text-sm mt-1">Guru belum mengisi refleksi pembelajaran</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-slate-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-slate-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-500 dark:text-gray-400 text-lg font-medium">Tidak ada data refleksi</p>
                                <p class="text-slate-400 dark:text-gray-500 text-sm mt-1">Data proses pembelajaran belum tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Riwayat Feedback -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="h-1.5 bg-linear-to-r from-amber-500 via-orange-500 to-red-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-linear-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-800 dark:text-white">Riwayat Feedback</h3>
                                <p class="text-sm text-slate-600 dark:text-gray-400">Feedback yang telah diberikan</p>
                            </div>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-900/20 px-3 py-1.5 rounded-full">
                            <span class="text-sm font-medium text-amber-700 dark:text-amber-300">{{ $supervisi->feedback->count() }} feedback</span>
                        </div>
                    </div>
                    
                    <!-- Area Feedback -->
                    <div class="max-h-[400px] overflow-y-auto space-y-4 pr-2 scrollbar-thin scrollbar-thumb-amber-200 dark:scrollbar-thumb-amber-800 scrollbar-track-transparent">
                        @forelse($supervisi->feedback->sortByDesc('created_at') as $fb)
                            <div class="group relative p-4 bg-linear-to-r from-amber-50/50 to-orange-50/30 dark:from-amber-900/10 dark:to-orange-900/10 rounded-lg border border-amber-100 dark:border-amber-800/30 hover:border-amber-200 dark:hover:border-amber-700 transition-all hover:shadow-sm">
                                <!-- Timeline indicator -->
                                <div class="absolute -left-2 top-6 w-3 h-3 bg-amber-400 rounded-full border-2 border-white dark:border-gray-800 shadow-sm"></div>
                                
                                <div class="ml-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-medium text-amber-700 dark:text-amber-300">
                                                {{ $fb->created_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                        @if($fb->is_revision_request)
                                            <span class="text-xs px-2 py-1 bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300 rounded-md font-bold">
                                                REVISI DIMINTA
                                            </span>
                                        @else
                                            <span class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 rounded-md font-bold">
                                                FEEDBACK
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-base text-slate-700 dark:text-gray-200 leading-relaxed">{{ $fb->komentar }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-amber-400 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-500 dark:text-gray-400 text-lg font-medium">Belum ada feedback</p>
                                <p class="text-slate-400 dark:text-gray-500 text-sm mt-1">Feedback akan muncul setelah Anda memberikan komentar</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

            <!-- Berikan Feedback -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="h-1.5 bg-linear-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                <div class="p-6">
                    <div class="flex items-center mb-5">
                        <div class="w-10 h-10 bg-linear-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">Berikan Feedback</h3>
                            <p class="text-sm text-slate-600 dark:text-gray-400">Berikan komentar dan evaluasi untuk guru</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('kepala.evaluasi.feedback', $supervisi->id) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="komentar" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-3">
                                Komentar / Feedback <span class="text-red-500 dark:text-red-400">*</span>
                            </label>
                            <textarea 
                                name="komentar" 
                                id="komentar" 
                                rows="6" 
                                required
                                class="w-full px-4 py-3 border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-slate-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none transition-all duration-200 placeholder:text-slate-400 dark:placeholder:text-gray-500"
                                placeholder="Tuliskan feedback Anda untuk guru..."
                            >{{ old('komentar') }}</textarea>
                            @error('komentar')
                                <p class="text-red-500 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-start p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <input 
                                type="checkbox" 
                                name="mark_completed" 
                                id="mark_completed" 
                                value="1"
                                class="w-4 h-4 text-indigo-600 dark:text-indigo-400 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 focus:ring-2 mt-0.5"
                            >
                            <label for="mark_completed" class="ml-3 text-sm font-medium text-slate-700 dark:text-gray-200">
                                Tandai sebagai "Telah Ditinjau" setelah memberikan feedback
                            </label>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-slate-200 dark:border-gray-700">
                            <a href="{{ route('kepala.evaluasi.index') }}" 
                               class="px-6 py-2.5 text-slate-700 dark:text-gray-200 bg-slate-100 hover:bg-slate-200 dark:bg-gray-700 dark:hover:bg-gray-600 font-medium rounded-lg transition-colors duration-200">
                                Kembali
                            </a>
                            <div class="flex space-x-3">
                                <button 
                                    type="button"
                                    onclick="showRevisionModal()"
                                    class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 dark:bg-rose-700 dark:hover:bg-rose-800 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                    Minta Revisi
                                </button>
                                <button 
                                    type="submit"
                                    class="px-6 py-2.5 bg-linear-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                    Kirim Feedback
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Revision Request Modal -->
<div id="revisionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" style="display: none;">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Minta Revisi</h3>
                    <button 
                        type="button"
                        onclick="hideRevisionModal()"
                        class="text-slate-400 hover:text-slate-600 dark:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('kepala.evaluasi.revision', $supervisi->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="revision_notes" class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-2">
                            Catatan Revisi <span class="text-red-500 dark:text-red-400">*</span>
                        </label>
                        <textarea 
                            name="revision_notes" 
                            id="revision_notes" 
                            rows="5" 
                            required
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent resize-none"
                            placeholder="Jelaskan apa yang perlu direvisi..."
                        ></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button 
                            type="button"
                            onclick="hideRevisionModal()"
                            class="px-5 py-2.5 text-slate-700 dark:text-gray-200 bg-slate-100 hover:bg-slate-200 dark:bg-gray-700 dark:hover:bg-gray-600 font-medium rounded-lg transition-colors duration-200">
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 dark:bg-rose-700 dark:hover:bg-rose-800 text-white font-medium rounded-lg transition-colors duration-200">
                            Kirim Permintaan Revisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>

@endsection



