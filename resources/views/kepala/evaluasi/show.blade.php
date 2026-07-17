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
    
    {{-- Notifikasi sukses ditangani toast global di layouts.modern --}}

    <x-evaluasi-guru-header :supervisi="$supervisi" />

    <x-evaluasi-stepper :supervisi="$supervisi" :aktif="1" />

        <!-- Vertical Card Layout -->
        <div class="space-y-4 sm:space-y-6">

            <!-- Card 1: Dokumen Evaluasi Diri -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <x-card-header title="Dokumen Evaluasi Diri" />
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
                            <div class="flex items-center justify-between gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                                <div class="flex items-center space-x-4 flex-1 min-w-0">
                                    <!-- Nomor Urut -->
                                    <div class="shrink-0 w-8 text-center">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ $loop->iteration }}.</span>
                                    </div>
                                    @if(str_ends_with($dokumen->path_file, '.pdf'))
                                        <svg class="w-8 h-8 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-0.5">
                                            {{ $jenisLabels[$dokumen->jenis_dokumen] ?? ucwords(str_replace('_', ' ', $dokumen->jenis_dokumen)) }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                            {{ $dokumen->nama_file ?? 'Dokumen ' . ($index + 1) }}
                                        </p>
                                        @if($dokumen->deskripsi)
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $dokumen->deskripsi }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($dokumen->path_file)
                                    <a href="{{ route('kepala.evaluasi.preview', $dokumen->id) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Preview</span>
                                    </a>
                                    @endif

                                    <a href="{{ route('kepala.evaluasi.download', $dokumen->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 dark:bg-blue-500 rounded hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Download</span>
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
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <x-card-header title="Link Pembelajaran" />
                <!-- Card Content -->
                <div class="p-3 sm:p-4 md:p-6">
                    <div class="max-h-80 sm:max-h-96 overflow-y-auto space-y-3">
                        @if($supervisi->prosesPembelajaran)
                            @if($supervisi->prosesPembelajaran->link_video)
                                <x-video-praktik :url="$supervisi->prosesPembelajaran->link_video" />
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
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <x-card-header title="Refleksi Pembelajaran" />
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
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border-b border-gray-200 dark:border-gray-600 last:border-b-0">
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

        </div>
        <!-- End Vertical Card Layout -->

        <x-evaluasi-action-bar :langkah="1" judul="Tinjau Materi">
            @if ($supervisi->status === 'submitted')
                <form action="{{ route('kepala.evaluasi.startReview', $supervisi->id) }}" method="POST">
                    @csrf
                    <x-button type="submit">
                        Mulai Review & Lanjut
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </x-button>
                </form>
            @elseif ($supervisi->status === 'completed')
                <x-button href="{{ route('kepala.evaluasi.feedback.show', $supervisi->id) }}">
                    Lihat Feedback
                    <x-icon name="eye" class="w-4 h-4" />
                </x-button>
            @else
                <x-button href="{{ route('kepala.evaluasi.rubrik', $supervisi->id) }}">
                    Lanjut: Isi Rubrik
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-button>
            @endif
        </x-evaluasi-action-bar>
    </div>

@endsection



