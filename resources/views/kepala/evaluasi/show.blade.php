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

        <!-- Vertical Card Layout -->
        <div class="space-y-6">

            <!-- Card 1: Dokumen Evaluasi Diri -->
            <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl dark:shadow-gray-900/50 dark:hover:shadow-gray-900/70 transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Card Header with Accent -->
                <div class="relative bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-indigo-500"></div>
                    <div class="flex items-center space-x-3 ml-2">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Dokumen Evaluasi Diri</h3>
                    </div>
                </div>
                <!-- Card Content -->
                <div class="p-6">
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
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-slate-50 dark:from-gray-700/50 dark:to-gray-700/30 rounded-lg hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 transition-all duration-200 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-sm">
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
                                       class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Preview
                                    </a>
                                    @endif

                                    <a href="{{ route('kepala.evaluasi.download', $dokumen->id) }}"
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl dark:shadow-gray-900/50 dark:hover:shadow-gray-900/70 transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Card Header with Accent -->
                <div class="relative bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-pink-500"></div>
                    <div class="flex items-center space-x-3 ml-2">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/40 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Link Pembelajaran</h3>
                    </div>
                </div>
                <!-- Card Content -->
                <div class="p-6">
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

            <!-- Card 3: Refleksi Pembelajaran -->
            <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl dark:shadow-gray-900/50 dark:hover:shadow-gray-900/70 transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Card Header with Accent -->
                <div class="relative bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-emerald-500 to-teal-500"></div>
                    <div class="flex items-center space-x-3 ml-2">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Refleksi Pembelajaran</h3>
                    </div>
                </div>
                <!-- Card Content -->
                <div class="p-6">
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
                                    <div class="group/item p-4 bg-gradient-to-r from-emerald-50/50 to-teal-50/50 dark:from-emerald-900/10 dark:to-teal-900/10 rounded-lg border border-emerald-200 dark:border-emerald-800/30 hover:border-emerald-300 dark:hover:border-emerald-700/50 transition-all duration-200 hover:shadow-sm">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-500 dark:bg-emerald-600 flex items-center justify-center">
                                                <span class="text-xs font-bold text-white">{{ $index + 1 }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300 mb-2">{{ $reflection['label'] }}</p>
                                                <p class="text-sm text-slate-700 dark:text-gray-300 leading-relaxed">{{ $reflection['value'] }}</p>
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

            <!-- Card 4: Riwayat Feedback -->
            <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl dark:shadow-gray-900/50 dark:hover:shadow-gray-900/70 transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Card Header with Accent -->
                <div class="relative bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-amber-500 to-orange-500"></div>
                    <div class="flex items-center space-x-3 ml-2">
                        <div class="p-2 bg-amber-100 dark:bg-amber-900/40 rounded-lg">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Riwayat Feedback</h3>
                    </div>
                </div>
                <!-- Card Content -->
                <div class="p-6">
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

            <!-- Card 5: Berikan Feedback -->
            <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl dark:shadow-gray-900/50 dark:hover:shadow-gray-900/70 transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Card Header with Accent -->
                <div class="relative bg-gradient-to-r from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-rose-500 to-pink-500"></div>
                    <div class="flex items-center space-x-3 ml-2">
                        <div class="p-2 bg-rose-100 dark:bg-rose-900/40 rounded-lg">
                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Berikan Feedback</h3>
                    </div>
                </div>
                <!-- Card Content -->
                <div class="p-6">
                    <form action="{{ route('kepala.evaluasi.feedback', $supervisi->id) }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="komentar" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">
                        Komentar dan Saran
                    </label>
                    <textarea
                        name="komentar"
                        id="komentar"
                        rows="6"
                        class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-rose-500 dark:focus:ring-rose-400 focus:border-rose-500 dark:focus:border-rose-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm resize-none transition-all duration-200"
                        placeholder="Berikan feedback, komentar, atau saran untuk guru..."
                        required></textarea>
                </div>

                <div class="flex items-start space-x-3 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800/30">
                    <input
                        type="checkbox"
                        name="is_revision_request"
                        id="is_revision_request"
                        value="1"
                        class="w-5 h-5 text-rose-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-500 rounded focus:ring-rose-500 focus:ring-2 mt-0.5">
                    <label for="is_revision_request" class="text-sm font-medium text-gray-700 dark:text-gray-300 leading-relaxed">
                        Minta revisi untuk supervisi ini
                    </label>
                </div>

                <div class="flex justify-end space-x-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('kepala.evaluasi.index') }}"
                       class="px-6 py-2.5 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg transition-all duration-200 border-2 border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 shadow-sm hover:shadow">
                        Kembali
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 dark:from-rose-500 dark:to-pink-500 dark:hover:from-rose-600 dark:hover:to-pink-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Kirim Feedback
                    </button>
                </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- End Vertical Card Layout -->
    </div>
</div>

<!-- Revision Request Modal -->
<div id="revisionModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-70 z-50 hidden backdrop-blur-sm" style="display: none;">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full border border-gray-200 dark:border-gray-700 transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Minta Revisi</h3>
                    <button
                        type="button"
                        onclick="hideRevisionModal()"
                        class="text-slate-400 hover:text-slate-600 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
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
                            class="w-full px-4 py-3 border border-slate-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                            placeholder="Jelaskan apa yang perlu direvisi..."
                        ></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            onclick="hideRevisionModal()"
                            class="px-5 py-2.5 text-gray-700 dark:text-gray-200 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 font-semibold rounded-lg transition-all duration-200 border-2 border-gray-300 dark:border-gray-600">
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 dark:from-rose-500 dark:to-pink-500 dark:hover:from-rose-600 dark:hover:to-pink-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
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



