@extends('layouts.modern')

@section('page-title', 'Beranda')

@section('content')
<div class="w-full lg:w-3/4 mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700 text-white rounded-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
                <p class="text-sm text-indigo-100 dark:text-indigo-200">{{ auth()->user()->mata_pelajaran }} • {{ auth()->user()->tingkat }}</p>
            </div>
            <div class="flex flex-wrap gap-3 justify-end">
                <button onclick="openGuideModal()" class="inline-flex items-center gap-2 px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-lg transition-all border border-white/30 hover:border-white/50 shadow-lg hover:shadow-xl backdrop-blur-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Panduan
                </button>
                <a href="{{ route('guru.supervisi.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-gray-100 text-indigo-600 font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Supervisi Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Tips & Informasi -->
    <div class="bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-cyan-900/20 dark:to-blue-900/20 rounded-xl border border-cyan-200 dark:border-cyan-800 p-5 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-11 h-11 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-lg flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Tips & Informasi</h3>
                <p class="text-xs text-gray-600 dark:text-gray-400">Hal penting yang perlu diketahui</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-blue-100 dark:border-blue-900/30 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>
                        <div class="text-sm font-bold text-blue-900 dark:text-blue-300">Lengkapi Dokumen</div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Upload 7 dokumen evaluasi diri</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-emerald-100 dark:border-emerald-900/30 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <div class="text-sm font-bold text-emerald-900 dark:text-emerald-300">Link Pembelajaran</div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Tambahkan video & link meeting</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-amber-100 dark:border-amber-900/30 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <div>
                        <div class="text-sm font-bold text-amber-900 dark:text-amber-300">Isi Refleksi</div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">5 pertanyaan refleksi pembelajaran</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-purple-100 dark:border-purple-900/30 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <div>
                        <div class="text-sm font-bold text-purple-900 dark:text-purple-300">Kolaborasi</div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Lihat & beri komentar ke rekan guru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Timeline Supervisi dengan style sosial media -->
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-linear-to-r from-indigo-600 to-purple-600 dark:from-indigo-500 dark:to-purple-500 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Timeline Supervisi</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Aktivitas supervisi pembelajaran Anda</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-sm font-semibold rounded-full">
                    {{ $supervisiList->count() }} supervisi
                </span>
            </div>
        </div>

        @if($supervisiList->count() > 0)
            <div class="space-y-4">
                @foreach($supervisiList as $item)
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition-all duration-200 overflow-hidden">
                    <!-- Header Card -->
                    <div class="p-4 bg-gradient-to-r from-indigo-50/80 to-purple-50/80 dark:from-indigo-900/20 dark:to-purple-900/20">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <div class="shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md ring-2 ring-white dark:ring-gray-800">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base truncate">
                                            {{ $item->user->name }}
                                        </h4>
                                        @if($item->user_id == auth()->id())
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-600 dark:bg-indigo-500 text-white text-xs font-medium rounded-full">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Saya
                                            </span>
                                        @endif
                                    </div>
                                    @if($item->user && ($item->user->mata_pelajaran || $item->user->tingkat))
                                    <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1.5">
                                        @if($item->user->mata_pelajaran)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            {{ $item->user->mata_pelajaran }}
                                        </span>
                                        @endif
                                        @if($item->user->mata_pelajaran && $item->user->tingkat)
                                        <span class="text-gray-400">•</span>
                                        @endif
                                        @if($item->user->tingkat)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            {{ $item->user->tingkat }}
                                        </span>
                                        @endif
                                    </p>
                                    @endif
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-0.5">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($item->tanggal_supervisi)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="shrink-0">
                                @if($item->status == 'draft')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                        Draft
                                    </span>
                                @elseif($item->status == 'submitted')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                        Disubmit
                                    </span>
                                @elseif($item->status == 'under_review')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Direview
                                </span>
                            @elseif($item->status == 'completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Selesai
                                </span>
                            @elseif($item->status == 'revision')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-100 text-rose-700 text-xs font-semibold rounded-full">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Perlu Revisi
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="px-4 py-3 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                    <!-- Info Cards (Dokumen & Proses) -->
                    <div class="flex items-center gap-3 flex-wrap mb-3">
                        @php
                            $docCount = $item->dokumenEvaluasi->count();
                            $hasProses = $item->prosesPembelajaran != null;
                        @endphp

                        <div class="flex items-center gap-2 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-100 dark:border-indigo-800">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Dokumen: <span class="{{ $docCount == 7 ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">{{ $docCount }}/7</span></span>
                        </div>

                        @if($hasProses)
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-100 dark:border-green-800">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs font-semibold text-green-700 dark:text-green-300">Proses Selesai</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Proses Belum</span>
                            </div>
                        @endif

                        @if($item->feedback->count() > 0)
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-100 dark:border-purple-800">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <span class="text-xs font-semibold text-purple-700 dark:text-purple-300">{{ $item->feedback->count() }} Feedback</span>
                            </div>
                        @endif
                    </div>

                    <!-- Komentar Terbaru (untuk semua supervisi) - Accordion -->
                    <div class="mb-3">
                        <button type="button" 
                                onclick="toggleComments('{{ $item->id }}')"
                                class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-slate-50 dark:bg-gray-900/30 hover:bg-slate-100 dark:hover:bg-gray-900/50 rounded-lg border border-slate-200 dark:border-gray-700 transition-colors">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span class="text-xs font-semibold text-slate-600 dark:text-gray-400">
                                    {{ $item->feedback ? count($item->feedback) : 0 }} Komentar
                                </span>
                            </div>
                            <svg id="chevron-{{ $item->id }}" class="w-4 h-4 text-slate-500 dark:text-gray-400 transform transition-transform duration-300 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="comments-{{ $item->id }}" class="overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0; opacity: 0;">
                            <div class="mt-2 space-y-2">
                                @if($item->feedback && count($item->feedback) > 0)
                                    @php
                                        $recentComments = $item->feedback->sortByDesc('created_at')->take(3);
                                    @endphp
                                    @foreach($recentComments as $fb)
                                    <div class="bg-slate-50 dark:bg-gray-900/50 rounded-lg p-2.5 border border-slate-200 dark:border-gray-700">
                                        <div class="flex items-start gap-2">
                                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                                                {{ strtoupper(substr($fb->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                                                    <p class="text-xs font-semibold text-slate-700 dark:text-gray-300">{{ $fb->user->name ?? 'User' }}</p>
                                                    @if($fb->user && $fb->user->role === 'kepala_sekolah')
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                                                        <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                        </svg>
                                                        Kepsek
                                                    </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-slate-600 dark:text-gray-400 line-clamp-2">{{ $fb->komentar }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-3">
                                        <p class="text-xs text-slate-500 dark:text-gray-400">Belum ada komentar</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
                    <div class="flex items-center gap-2">
                        @if($item->user_id == auth()->id())
                            @if(in_array($item->status, ['draft', 'revision']))
                                <form id="delete-supervisi-{{ $item->id }}" method="POST" action="{{ route('guru.supervisi.delete', $item->id) }}" class="inline-block">
                                    @csrf
                                    @method('POST')
                                    <button
                                        type="button"
                                        onclick="confirmDeleteSupervisi({{ $item->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-semibold rounded-lg transition-all border border-red-200 dark:border-red-800"
                                        title="Hapus supervisi"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            @endif

                            @if($item->status == 'draft')
                                <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                    Lanjutkan
                                </a>
                            @elseif($item->status == 'revision')
                                <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Revisi Sekarang
                                </a>
                            @else
                                <a href="{{ route('guru.supervisi.detail', $item->id) }}" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                            @endif
                        @else
                            <a href="{{ route('guru.supervisi.view', $item->id) }}" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-12 text-center">
            <div class="w-20 h-20 bg-linear-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum Ada Supervisi</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">Mulai supervisi pertama Anda untuk melacak perkembangan dan mendokumentasikan proses pembelajaran</p>
            <a href="{{ route('guru.supervisi.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Mulai Supervisi Baru
            </a>
        </div>
    @endif
    </div>
</div>
</div>

<!-- Guru Guide Modal -->
<div id="guideModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-70 items-center justify-center p-4" style="display: none;">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-linear-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 dark:bg-indigo-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Panduan Supervisi Pembelajaran</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ikuti langkah-langkah berikut untuk menyelesaikan supervisi</p>
                </div>
            </div>
            <button onclick="closeGuideModal()" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
            <div class="space-y-4">
                <!-- Step 1 -->
                <div class="flex gap-3 p-4 bg-gradient-to-r from-blue-50 to-blue-100/50 dark:from-blue-900/20 dark:to-blue-900/10 rounded-lg hover:shadow-md transition-all border border-blue-200 dark:border-blue-800">
                    <div class="shrink-0">
                        <div class="w-10 h-10 bg-blue-600 dark:bg-blue-500 rounded-lg flex items-center justify-center shadow-sm">
                            <span class="text-lg font-bold text-white">1</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1.5">Buat Supervisi Baru</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">Klik tombol <strong>"Buat Supervisi Baru"</strong> untuk memulai. Isi tanggal supervisi dan catatan (opsional).</p>
                        <div class="inline-flex items-center gap-1.5 text-xs text-blue-600 dark:text-blue-400 font-medium bg-white dark:bg-gray-800 px-2.5 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Data dapat disimpan sebagai draft kapan saja
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex gap-3 p-4 bg-gradient-to-r from-purple-50 to-purple-100/50 dark:from-purple-900/20 dark:to-purple-900/10 rounded-lg hover:shadow-md transition-all border border-purple-200 dark:border-purple-800">
                    <div class="shrink-0">
                        <div class="w-10 h-10 bg-purple-600 dark:bg-purple-500 rounded-lg flex items-center justify-center shadow-sm">
                            <span class="text-lg font-bold text-white">2</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1.5">Upload Dokumen Evaluasi Diri (Wajib)</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">Upload 7 dokumen evaluasi (Capaian Pembelajaran, ATP, Kalender, Prota, Prosem, Modul Ajar, Bahan Ajar). Harus lengkap untuk bisa lanjut ke proses pembelajaran.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white dark:bg-gray-800 text-xs font-medium text-gray-700 dark:text-gray-300 rounded-full">
                                📎 Max 2MB/file
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white dark:bg-gray-800 text-xs font-medium text-gray-700 dark:text-gray-300 rounded-full">
                                📄 PDF/JPG/PNG
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex gap-3 p-4 bg-gradient-to-r from-green-50 to-green-100/50 dark:from-green-900/20 dark:to-green-900/10 rounded-lg hover:shadow-md transition-all border border-green-200 dark:border-green-800">
                    <div class="shrink-0">
                        <div class="w-10 h-10 bg-green-600 dark:bg-green-500 rounded-lg flex items-center justify-center shadow-sm">
                            <span class="text-lg font-bold text-white">3</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1.5">Isi Proses Pembelajaran (Wajib)</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">Masukkan <strong>Link Video Pembelajaran</strong> (wajib), Link Meeting (opsional), dan jawab <strong>5 pertanyaan refleksi</strong> dengan detail minimal 10 karakter.</p>
                        <div class="inline-flex items-center gap-1.5 text-xs text-green-600 dark:text-green-400 font-medium bg-white dark:bg-gray-800 px-2.5 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Data otomatis tersimpan setiap 30 detik
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex gap-3 p-4 bg-gradient-to-r from-amber-50 to-amber-100/50 dark:from-amber-900/20 dark:to-amber-900/10 rounded-lg hover:shadow-md transition-all border border-amber-200 dark:border-amber-800">
                    <div class="shrink-0">
                        <div class="w-10 h-10 bg-amber-600 dark:bg-amber-500 rounded-lg flex items-center justify-center shadow-sm">
                            <span class="text-lg font-bold text-white">4</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1.5">Submit Supervisi</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">Setelah semua data lengkap, tombol <strong>"Submit Supervisi"</strong> akan aktif (hijau). Klik untuk mengirim supervisi ke Kepala Sekolah untuk direview.</p>
                        <div class="inline-flex items-center gap-1.5 text-xs text-amber-600 dark:text-amber-400 font-medium bg-white dark:bg-gray-800 px-2.5 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Setelah submit, data tidak dapat diubah kecuali diminta revisi
                        </div>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="flex gap-3 p-4 bg-gradient-to-r from-indigo-50 to-indigo-100/50 dark:from-indigo-900/20 dark:to-indigo-900/10 rounded-lg hover:shadow-md transition-all border border-indigo-200 dark:border-indigo-800">
                    <div class="shrink-0">
                        <div class="w-10 h-10 bg-indigo-600 dark:bg-indigo-500 rounded-lg flex items-center justify-center shadow-sm">
                            <span class="text-lg font-bold text-white">5</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1.5">Tunggu Review & Feedback</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">Kepala Sekolah akan mereview supervisi Anda. Jika ada yang perlu diperbaiki, status akan berubah menjadi <strong>"Perlu Revisi"</strong> dan Anda dapat melakukan perbaikan.</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="inline-flex items-center px-2.5 py-1 bg-blue-100 dark:bg-blue-900/30 text-xs font-semibold text-blue-600 dark:text-blue-400 rounded-full">
                                Sedang Direview
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 bg-orange-100 dark:bg-orange-900/30 text-xs font-semibold text-orange-600 dark:text-orange-400 rounded-full">
                                Perlu Revisi
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-xs font-semibold text-green-600 dark:text-green-400 rounded-full">
                                Selesai
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Note -->
            <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-center gap-2 p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-700 dark:text-gray-300">Ikuti setiap langkah dengan lengkap untuk hasil terbaik</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown hover effect (border color only)
        const allSelects = document.querySelectorAll('select');
        allSelects.forEach(select => {
            select.addEventListener('mouseenter', function() {
                if (document.activeElement !== this) {
                    this.style.borderColor = '#818cf8';
                }
            });
            select.addEventListener('mouseleave', function() {
                if (document.activeElement !== this) {
                    this.style.borderColor = '';
                }
            });
        });
    });

    // Delete supervisi function with modal confirmation
    function confirmDeleteSupervisi(supervisiId) {
        showConfirmModal(
            'Apakah Anda yakin ingin menghapus supervisi ini? Semua data termasuk dokumen yang telah diupload akan dihapus secara permanen.',
            'Konfirmasi Hapus Supervisi',
            function() {
                document.getElementById('delete-supervisi-' + supervisiId).submit();
            }
        );
    }

    // Delete supervisi function (async version - if still used)
    async function deleteSupervisi(supervisiId) {
        showConfirmModal(
            'Apakah Anda yakin ingin menghapus supervisi ini? Semua data termasuk dokumen yang telah diupload akan dihapus secara permanen.',
            'Konfirmasi Hapus Supervisi',
            async function() {
                try {
                    const response = await fetch(`/guru/supervisi/${supervisiId}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        showToast(result.message, 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showToast(result.message || 'Gagal menghapus supervisi', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan: ' + error.message, 'error');
                }
            }
        );
    }

    function openGuideModal() {
        const modal = document.getElementById('guideModal');
        const content = modal.querySelector('div');
        modal.style.display = 'flex';
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeGuideModal() {
        const modal = document.getElementById('guideModal');
        const content = modal.querySelector('div');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Toggle Comments Accordion with smooth animation
    function toggleComments(id) {
        const commentsDiv = document.getElementById('comments-' + id);
        const chevron = document.getElementById('chevron-' + id);
        
        if (commentsDiv.style.maxHeight === '0px' || commentsDiv.style.maxHeight === '') {
            // Open
            commentsDiv.style.maxHeight = commentsDiv.scrollHeight + 'px';
            commentsDiv.style.opacity = '1';
            chevron.style.transform = 'rotate(180deg)';
        } else {
            // Close
            commentsDiv.style.maxHeight = '0px';
            commentsDiv.style.opacity = '0';
            chevron.style.transform = 'rotate(0deg)';
        }
    }

    // Close modal on backdrop click
    document.getElementById('guideModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeGuideModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('guideModal').style.display === 'flex') {
            closeGuideModal();
        }
    });
</script>
@endsection
