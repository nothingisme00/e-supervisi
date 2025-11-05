@extends('layouts.modern')

@section('page-title', 'Detail Evaluasi Supervisi - ' . $supervisi->user->name)

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
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
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden mb-6">
            <!-- Decorative Header Bar -->
            <div class="h-2 bg-linear-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
            
            <div class="p-6">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 rounded-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg ring-4 ring-indigo-100 dark:ring-indigo-900/50">
                            {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">{{ $supervisi->user->name }}</h1>
                            <p class="text-slate-600 dark:text-gray-300 text-sm">{{ $supervisi->user->email }}</p>
                            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                NIK: {{ $supervisi->user->nik }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($supervisi->status === 'submitted')
                            <span class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300 border-2 border-amber-200 dark:border-amber-500/40 shadow-sm mb-3">
                                <span class="w-2 h-2 bg-amber-500 dark:bg-amber-400 rounded-full mr-2 animate-pulse"></span>
                                Menunggu Peninjauan
                            </span>
                            <form action="{{ route('kepala.evaluasi.startReview', $supervisi->id) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-2.5 bg-linear-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Mulai Review
                                </button>
                            </form>
                        @elseif($supervisi->status === 'under_review')
                            <span class="inline-flex items-center px-5 py-3 rounded-lg text-sm font-bold bg-indigo-100 text-indigo-900 dark:bg-violet-900! dark:text-gray-100! shadow-lg border-2 border-indigo-200 dark:border-violet-700!">
                                <span class="w-2.5 h-2.5 bg-indigo-600 dark:bg-yellow-400! rounded-full mr-2.5 animate-pulse"></span>
                                Sedang Ditinjau
                            </span>
                        @elseif($supervisi->status === 'completed')
                            <span class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300 border-2 border-emerald-200 dark:border-emerald-500/40 shadow-sm">
                                <svg class="w-4 h-4 mr-2 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Telah Ditinjau
                            </span>
                        @elseif($supervisi->status === 'revision')
                            <span class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300 border-2 border-rose-200 dark:border-rose-500/40 shadow-sm">
                                <svg class="w-4 h-4 mr-2 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Perlu Revisi
                            </span>
                        @endif
                        <p class="text-xs text-slate-500 dark:text-gray-400 mt-3 flex items-center justify-end">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Disubmit: {{ $supervisi->updated_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
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
                <div class="bg-linear-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 px-6 py-4 border-b border-purple-600 dark:border-purple-800">
                    <h3 class="text-base font-semibold text-white">Link Pembelajaran</h3>
                </div>
                <!-- Card Content -->
                <div class="p-6">
                    <div class="max-h-96 overflow-y-auto space-y-3">
                        @if($supervisi->prosesPembelajaran)
                            @if($supervisi->prosesPembelajaran->video_link)
                                    <div class="p-4 bg-linear-to-r from-red-50 to-pink-50 dark:from-red-900/10 dark:to-pink-900/10 rounded-lg border border-red-100 dark:border-red-800/30">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0 pr-2">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">Link Video Pembelajaran</p>
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
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">Link Meeting/Zoom</p>
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
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-linear-to-r from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 px-6 py-4 border-b border-emerald-600 dark:border-emerald-800">
                    <h3 class="text-base font-semibold text-white">Refleksi Pembelajaran</h3>
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

            <!-- Card 4: Riwayat Feedback -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-orange-600 dark:bg-orange-700 px-6 py-4">
                    <h3 class="text-base font-semibold text-white">Riwayat Feedback</h3>
                </div>
                <!-- Card Content -->
                <div class="p-6">
                    <div class="max-h-96 overflow-y-auto space-y-3">
                        @forelse($supervisi->feedback as $fb)
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-md border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                                <div class="flex items-start justify-between mb-2">
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $fb->created_at->format('d M Y, H:i') }}</p>
                                    @if($fb->is_revision_request)
                                        <span class="text-xs px-2 py-0.5 bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 rounded font-medium">
                                            Revisi Diminta
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-900 dark:text-gray-100 leading-relaxed">{{ $fb->komentar }}</p>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Belum ada feedback</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Card 5: Berikan Feedback -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-linear-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 px-6 py-4 border-b border-indigo-600 dark:border-indigo-800">
                    <h3 class="text-base font-semibold text-white">Berikan Feedback</h3>
                </div>
                <!-- Card Content -->
                <div class="p-6">
                    <form action="{{ route('kepala.evaluasi.feedback', $supervisi->id) }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="komentar" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Komentar dan Saran
                    </label>
                    <textarea
                        name="komentar"
                        id="komentar"
                        rows="5"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm resize-none"
                        placeholder="Berikan feedback, komentar, atau saran untuk guru..."
                        required></textarea>
                </div>

                <div class="flex items-start space-x-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                    <input
                        type="checkbox"
                        name="is_revision_request"
                        id="is_revision_request"
                        value="1"
                        class="w-4 h-4 text-indigo-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-500 rounded focus:ring-indigo-500 focus:ring-2 mt-0.5">
                    <label for="is_revision_request" class="text-sm text-gray-900 dark:text-gray-100">
                        Minta revisi untuk supervisi ini
                    </label>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('kepala.evaluasi.index') }}"
                       class="px-5 py-2 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100 text-sm font-medium rounded-md transition-colors border border-gray-300 dark:border-gray-600">
                        Kembali
                    </a>
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-md transition-colors">
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



