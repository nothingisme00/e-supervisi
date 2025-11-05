@extends('layouts.modern')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Kepala Sekolah</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span> | 
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
        </p>
    </div>

    <!-- Tips & Informasi (Compact & Horizontal) -->
    <div class="bg-gradient-to-r from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 rounded-xl border border-rose-200 dark:border-rose-800 p-5 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-11 h-11 bg-gradient-to-br from-rose-500 to-pink-500 rounded-lg flex items-center justify-center shrink-0 shadow-md">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-sm font-bold text-blue-900 dark:text-blue-300">Status Supervisi</div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Submitted → Under Review → Completed</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-amber-100 dark:border-amber-900/30 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <div class="text-sm font-bold text-amber-900 dark:text-amber-300">Prioritas Review</div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Review supervisi paling lama terlebih dahulu</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-emerald-100 dark:border-emerald-900/30 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-sm font-bold text-emerald-900 dark:text-emerald-300">Feedback Konstruktif</div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Berikan masukan jelas dan membangun</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-purple-100 dark:border-purple-900/30 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-sm font-bold text-purple-900 dark:text-purple-300">Waktu Review</div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Target maksimal 3 hari kerja</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panduan Penggunaan Button -->
    <div class="mb-6">
        <button onclick="document.getElementById('panduanModal').classList.remove('hidden')" class="w-full flex items-center justify-between p-5 bg-gradient-to-r from-violet-500 to-purple-500 hover:from-violet-600 hover:to-purple-600 rounded-xl text-white transition-all shadow-md hover:shadow-xl group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="text-lg font-bold mb-1">Panduan Penggunaan</div>
                    <div class="text-sm opacity-90">Langkah-langkah menggunakan sistem E-Supervisi</div>
                </div>
            </div>
            <svg class="w-7 h-7 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @php
            $pendingList = \App\Models\Supervisi::with('user')->where('status', 'submitted')->latest()->get();
            $inProgressList = \App\Models\Supervisi::with('user')->where('status', 'under_review')->latest()->get();
            $completedList = \App\Models\Supervisi::with('user')->where('status', 'completed')->latest()->get();
        @endphp

        <!-- Card 1: Perlu Review -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Perlu Review</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu peninjauan</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $supervisiPending }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Supervisi</div>
                    </div>
                </div>
            </div>
            <div class="p-5 max-h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900/50">
                @if($pendingList->count() > 0)
                <div class="space-y-3">
                    @foreach($pendingList as $supervisi)
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-amber-100 dark:border-amber-900/30 hover:border-amber-300 dark:hover:border-amber-700 hover:shadow-lg transition-all">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-11 h-11 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center text-white font-bold text-base shrink-0 shadow-sm">
                                {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-base text-gray-900 dark:text-white truncate mb-1">{{ $supervisi->user->name }}</div>
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg font-medium">{{ $supervisi->user->mata_pelajaran ?? '-' }}</span>
                                    <span class="px-3 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-lg font-medium">{{ $supervisi->user->tingkat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $supervisi->updated_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <a href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            </svg>
                            Mulai Review
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-amber-50 dark:bg-amber-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-400 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <p class="text-base text-gray-500 dark:text-gray-400 font-medium">Tidak ada data</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Card 2: Sedang Ditinjau -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-indigo-400 to-purple-500"></div>
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Sedang Ditinjau</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Dalam proses review</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $supervisiInProgress }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Supervisi</div>
                    </div>
                </div>
            </div>
            <div class="p-5 max-h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900/50">
                @if($inProgressList->count() > 0)
                <div class="space-y-3">
                    @foreach($inProgressList as $supervisi)
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-indigo-100 dark:border-indigo-900/30 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-lg transition-all">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold text-base shrink-0 shadow-sm">
                                {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-base text-gray-900 dark:text-white truncate mb-1">{{ $supervisi->user->name }}</div>
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg font-medium">{{ $supervisi->user->mata_pelajaran ?? '-' }}</span>
                                    <span class="px-3 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-lg font-medium">{{ $supervisi->user->tingkat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $supervisi->reviewed_at ? $supervisi->reviewed_at->format('d M Y, H:i') : $supervisi->updated_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <a href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Lanjutkan Review
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-400 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <p class="text-base text-gray-500 dark:text-gray-400 font-medium">Tidak ada data</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Card 3: Telah Selesai -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-emerald-400 to-green-500"></div>
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-green-100 dark:from-emerald-900/30 dark:to-green-900/30 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Telah Selesai</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Review selesai</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $supervisiReviewed }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Supervisi</div>
                    </div>
                </div>
            </div>
            <div class="p-5 max-h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900/50">
                @if($completedList->count() > 0)
                <div class="space-y-3">
                    @foreach($completedList as $supervisi)
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-emerald-100 dark:border-emerald-900/30 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-lg transition-all">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-green-500 rounded-lg flex items-center justify-center text-white font-bold text-base shrink-0 shadow-sm">
                                {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-base text-gray-900 dark:text-white truncate mb-1">{{ $supervisi->user->name }}</div>
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg font-medium">{{ $supervisi->user->mata_pelajaran ?? '-' }}</span>
                                    <span class="px-3 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-lg font-medium">{{ $supervisi->user->tingkat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $supervisi->reviewed_at ? $supervisi->reviewed_at->format('d M Y, H:i') : $supervisi->updated_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <a href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Lihat Detail
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-400 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <p class="text-base text-gray-500 dark:text-gray-400 font-medium">Tidak ada data</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Panduan Penggunaan -->
    <div id="panduanModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-violet-500 to-purple-500 px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Panduan Penggunaan Sistem</h3>
                        <p class="text-sm text-violet-100">Langkah-langkah menggunakan E-Supervisi</p>
                    </div>
                </div>
                <button onclick="document.getElementById('panduanModal').classList.add('hidden')" class="w-10 h-10 rounded-lg hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                <div class="space-y-5">
                    <div class="flex items-start gap-4 p-5 bg-violet-50 dark:bg-violet-900/20 rounded-xl border border-violet-200 dark:border-violet-800">
                        <div class="w-12 h-12 bg-violet-500 rounded-xl flex items-center justify-center shrink-0 shadow-lg">
                            <span class="text-xl font-bold text-white">1</span>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-900 dark:text-white mb-2">Cek Supervisi Masuk</div>
                            <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">Lihat daftar supervisi yang diajukan guru pada card <span class="font-semibold text-amber-600 dark:text-amber-400">"Perlu Review"</span>. Supervisi baru akan muncul dengan status "Submitted" dan menunggu untuk ditinjau.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-5 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-200 dark:border-indigo-800">
                        <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center shrink-0 shadow-lg">
                            <span class="text-xl font-bold text-white">2</span>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-900 dark:text-white mb-2">Klik "Mulai Review"</div>
                            <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">Tekan tombol <span class="font-semibold text-amber-600 dark:text-amber-400">"Mulai Review"</span> untuk memulai proses evaluasi. Status supervisi akan berubah menjadi "Under Review" dan masuk ke daftar <span class="font-semibold text-indigo-600 dark:text-indigo-400">"Sedang Ditinjau"</span>.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-5 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shrink-0 shadow-lg">
                            <span class="text-xl font-bold text-white">3</span>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-900 dark:text-white mb-2">Review Dokumen Pembelajaran</div>
                            <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed mb-3">Periksa kelengkapan dan kualitas dokumen yang diupload guru:</p>
                            <ul class="list-disc list-inside space-y-1 text-base text-gray-700 dark:text-gray-300 ml-2">
                                <li><span class="font-semibold">Dokumen Evaluasi</span> - RPP dan administrasi pembelajaran</li>
                                <li><span class="font-semibold">Media Pembelajaran</span> - Materi, slide, atau media pendukung</li>
                                <li><span class="font-semibold">Proses Pembelajaran</span> - Video atau dokumentasi kegiatan</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-5 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800">
                        <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center shrink-0 shadow-lg">
                            <span class="text-xl font-bold text-white">4</span>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-900 dark:text-white mb-2">Berikan Feedback</div>
                            <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed mb-3">Tulis catatan evaluasi yang konstruktif dan jelas. Anda memiliki 2 opsi:</p>
                            <div class="space-y-2 ml-2">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <p class="text-base text-gray-700 dark:text-gray-300"><span class="font-semibold">Approve</span> - Tandai sebagai selesai jika sudah memenuhi standar</p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <p class="text-base text-gray-700 dark:text-gray-300"><span class="font-semibold">Request Revision</span> - Minta perbaikan dengan catatan spesifik</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
                <button onclick="document.getElementById('panduanModal').classList.add('hidden')" class="w-full px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-500 hover:from-violet-600 hover:to-purple-600 text-white font-semibold rounded-xl transition-all shadow-md hover:shadow-lg">
                    Mengerti, Tutup Panduan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
