@extends('layouts.modern')

@section('page-title', 'Supervisi Saya')

@section('content')
<div class="w-full lg:w-11/12 xl:w-5/6 mx-auto px-0 sm:px-4 md:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Beranda', 'url' => route('guru.home')],
        ['label' => 'Supervisi Saya', 'icon' => true]
    ]" />

    <!-- Main Container -->
    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg md:rounded-2xl p-1.5 sm:p-3 md:p-5 lg:p-6 mb-2 sm:mb-4 md:mb-6">
        <!-- Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-xl shadow mb-2 sm:mb-4">
            <div class="px-2 py-1.5 sm:px-4 sm:py-3 md:px-6 md:py-4 lg:px-7 lg:py-5">
                <div class="flex flex-row items-center justify-between gap-1.5 sm:gap-3 md:gap-5 w-full">
                    <!-- LEFT SECTION: Logo + Title -->
                    <div class="flex items-center gap-1.5 sm:gap-2 md:gap-4 flex-shrink min-w-0">
                        <div class="w-7 h-7 sm:w-9 sm:h-9 md:w-14 md:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-500 dark:to-teal-500 rounded-md sm:rounded-lg md:rounded-xl flex items-center justify-center shadow-md sm:shadow-lg flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 md:w-7 md:h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-xs sm:text-base md:text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate">Supervisi Saya</h3>
                            <p class="text-[9px] sm:text-xs md:text-sm text-gray-500 dark:text-gray-400 hidden sm:block">{{ $mySupervisi->count() }} supervisi</p>
                        </div>
                    </div>

                    <!-- RIGHT SECTION: Buttons -->
                    <div class="flex flex-nowrap items-center gap-0.5 sm:gap-2 md:gap-2.5 lg:gap-2.5 flex-shrink-0">
                        <!-- Button: Buat Supervisi Baru -->
                        <button onclick="openSupervisiModal()" class="inline-flex items-center justify-center gap-1 lg:gap-2 p-1 sm:px-2.5 sm:py-2 md:px-3 md:py-2.5 lg:px-4 lg:py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded sm:rounded-lg transition-all shadow-md hover:shadow-lg text-[9px] sm:text-[11px] lg:text-sm whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 md:w-5 md:h-5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="hidden sm:inline">Buat Baru</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        @if($mySupervisi->count() > 0)
            <div class="grid grid-cols-1 gap-2 sm:gap-4">
                @foreach($mySupervisi as $item)
                    <!-- Supervisi Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-xl shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-200 overflow-hidden">
                        <!-- Card Header -->
                        <div class="px-2.5 py-2 sm:px-4 sm:py-3 bg-gradient-to-r from-gray-50 to-white dark:from-gray-700/50 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between gap-2 sm:gap-3">
                                <!-- Left: Info -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-1.5 sm:gap-2 mb-0.5 sm:mb-1">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $item->tanggal_supervisi ? \Carbon\Carbon::parse($item->tanggal_supervisi)->format('d M Y') : 'Belum disubmit' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 sm:gap-2 text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ $item->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- Right: Status Badge -->
                                <div class="flex items-center gap-1.5 sm:gap-2">
                                    @if($item->status == 'draft')
                                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 bg-gray-100 text-gray-700 text-[10px] sm:text-xs font-semibold rounded-full">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Draft
                                        </span>
                                    @elseif($item->status == 'submitted')
                                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 bg-blue-100 text-blue-700 text-[10px] sm:text-xs font-semibold rounded-full">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                            Disubmit
                                        </span>
                                    @elseif($item->status == 'under_review')
                                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 bg-amber-100 text-amber-700 text-[10px] sm:text-xs font-semibold rounded-full">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ditinjau
                                        </span>
                                    @elseif($item->status == 'completed')
                                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 bg-emerald-100 text-emerald-700 text-[10px] sm:text-xs font-semibold rounded-full">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Selesai
                                        </span>
                                    @elseif($item->status == 'revision')
                                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 bg-rose-100 text-rose-700 text-[10px] sm:text-xs font-semibold rounded-full">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Revisi
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="px-2.5 py-2.5 sm:px-4 sm:py-4">
                            <!-- Progress Bar -->
                            @php
                                $docCount = $item->dokumenEvaluasi->count();
                                $hasProses = $item->prosesPembelajaran != null;
                                $feedbackCount = $item->feedback->count();

                                // Calculate progress percentage
                                $docProgress = ($docCount / 7) * 50; // 50% for documents
                                $prosesProgress = $hasProses ? 50 : 0; // 50% for process
                                $totalProgress = $docProgress + $prosesProgress;
                            @endphp

                            <div class="mb-3 sm:mb-4">
                                <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                    <span class="text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300">Progress</span>
                                    <span class="text-[10px] sm:text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ round($totalProgress) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 sm:h-2.5 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-300 {{ $totalProgress == 100 ? 'bg-gradient-to-r from-emerald-500 to-green-600' : 'bg-gradient-to-r from-indigo-500 to-purple-600' }}" style="width: {{ $totalProgress }}%"></div>
                                </div>
                            </div>

                            <!-- Stats Grid -->
                            <div class="grid grid-cols-3 gap-1.5 sm:gap-3 mb-3 sm:mb-4">
                                <!-- Dokumen -->
                                <div class="flex flex-col items-center p-1.5 sm:p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-md sm:rounded-lg border border-indigo-100 dark:border-indigo-800">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600 dark:text-indigo-400 mb-0.5 sm:mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div class="text-sm sm:text-lg font-bold text-indigo-700 dark:text-indigo-300">{{ $docCount }}/7</div>
                                    <div class="text-[9px] sm:text-xs text-indigo-600 dark:text-indigo-400">Dokumen</div>
                                </div>

                                <!-- Proses -->
                                <div class="flex flex-col items-center p-1.5 sm:p-3 {{ $hasProses ? 'bg-green-50 dark:bg-green-900/20 border-green-100 dark:border-green-800' : 'bg-gray-50 dark:bg-gray-700/20 border-gray-200 dark:border-gray-700' }} rounded-md sm:rounded-lg border">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $hasProses ? 'text-green-600 dark:text-green-400' : 'text-gray-400 dark:text-gray-500' }} mb-0.5 sm:mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="text-sm sm:text-lg font-bold {{ $hasProses ? 'text-green-700 dark:text-green-300' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ $hasProses ? '✓' : '✗' }}
                                    </div>
                                    <div class="text-[9px] sm:text-xs {{ $hasProses ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">Proses</div>
                                </div>

                                <!-- Feedback -->
                                <div class="flex flex-col items-center p-1.5 sm:p-3 {{ $feedbackCount > 0 ? 'bg-purple-50 dark:bg-purple-900/20 border-purple-100 dark:border-purple-800' : 'bg-gray-50 dark:bg-gray-700/20 border-gray-200 dark:border-gray-700' }} rounded-md sm:rounded-lg border">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $feedbackCount > 0 ? 'text-purple-600 dark:text-purple-400' : 'text-gray-400 dark:text-gray-500' }} mb-0.5 sm:mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <div class="text-sm sm:text-lg font-bold {{ $feedbackCount > 0 ? 'text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400' }}">{{ $feedbackCount }}</div>
                                    <div class="text-[9px] sm:text-xs {{ $feedbackCount > 0 ? 'text-purple-600 dark:text-purple-400' : 'text-gray-500 dark:text-gray-400' }}">Feedback</div>
                                </div>
                            </div>

                            <!-- Revision Notes (if any) -->
                            @if($item->status == 'revision' && $item->revision_notes)
                            <div class="mb-3 p-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-rose-800 dark:text-rose-300 mb-1">Catatan Revisi:</p>
                                        <p class="text-xs text-rose-700 dark:text-rose-400">{{ $item->revision_notes }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Card Footer with Actions -->
                        <div class="px-2.5 py-2 sm:px-4 sm:py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-1.5 sm:gap-2">
                            @if($item->status == 'draft')
                                <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 py-1 sm:px-4 sm:py-1.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-[10px] sm:text-xs font-semibold rounded-md sm:rounded-lg transition-all shadow-sm">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                    Lanjutkan
                                </a>
                                <form action="{{ route('guru.supervisi.delete', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supervisi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 py-1 sm:px-4 sm:py-1.5 bg-red-600 hover:bg-red-700 text-white text-[10px] sm:text-xs font-semibold rounded-md sm:rounded-lg transition-all shadow-sm">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Hapus</span>
                                    </button>
                                </form>
                            @elseif($item->status == 'revision')
                                {{-- Button to edit/revise supervisi --}}
                                <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 py-1 sm:px-4 sm:py-1.5 bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white text-[10px] sm:text-xs font-semibold rounded-md sm:rounded-lg transition-all shadow-sm">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Revisi
                                </a>
                            @else
                                <a href="{{ route('guru.supervisi.detail', $item->id) }}" class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 py-1 sm:px-4 sm:py-1.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-[10px] sm:text-xs font-semibold rounded-md sm:rounded-lg transition-all shadow-sm">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-xl shadow p-4 sm:p-8 text-center">
                <div class="w-12 h-12 sm:w-20 sm:h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-4">
                    <svg class="w-6 h-6 sm:w-10 sm:h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2">Belum Ada Supervisi</h3>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3 sm:mb-4">Mulai dengan membuat supervisi baru.</p>
                <button onclick="openSupervisiModal()" class="inline-flex items-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-5 sm:py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs sm:text-sm font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Supervisi Baru
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Modal: Buat Supervisi Baru -->
<div id="supervisiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 px-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Buat Supervisi Baru</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Mulai proses supervisi pembelajaran</p>
                    </div>
                </div>
                <button onclick="closeSupervisiModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800 dark:text-blue-300">
                        <p class="font-medium mb-1">Proses supervisi terdiri dari:</p>
                        <ol class="list-decimal list-inside space-y-1 text-xs">
                            <li>Upload 7 dokumen evaluasi diri</li>
                            <li>Isi data proses pembelajaran</li>
                            <li>Submit untuk ditinjau</li>
                        </ol>
                    </div>
                </div>
            </div>

            <form action="{{ route('guru.supervisi.store') }}" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closeSupervisiModal()" class="flex-1 px-5 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-5 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                        Mulai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openSupervisiModal() {
        const modal = document.getElementById('supervisiModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeSupervisiModal() {
        const modal = document.getElementById('supervisiModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal on outside click
    document.getElementById('supervisiModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeSupervisiModal();
        }
    });
</script>
@endsection
