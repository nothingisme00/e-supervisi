@extends('layouts.modern')

@section('title', 'Lihat Supervisi - ' . $supervisi->user->name)

@section('content')
<!-- Breadcrumb -->
<div class="mb-4">
    <x-breadcrumb :items="[
        ['label' => 'Beranda', 'url' => route('guru.home')],
        ['label' => 'Supervisi'],
        ['label' => 'Lihat Detail', 'icon' => true]
    ]" />
</div>

{{-- Debug: Uncomment to see data structure --}}
{{-- @dd($supervisi->toArray()) --}}

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="w-full lg:w-3/4 mx-auto px-4 sm:px-6 lg:px-8 pb-24 md:pb-0">
        
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
                            {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">{{ $supervisi->user->name }}</h1>
                            <div class="flex flex-wrap gap-3 text-sm text-slate-600 dark:text-gray-300 mt-2">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span class="font-medium">{{ $supervisi->mata_pelajaran }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span class="font-medium">Kelas {{ $supervisi->kelas }}</span>
                                </div>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-gray-400 mt-2 flex items-center">
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
                    @if($supervisi->dokumenEvaluasi && count($supervisi->dokumenEvaluasi) > 0)
                        <div class="space-y-3">
                            @foreach($supervisi->dokumenEvaluasi as $dokumen)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-colors">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-10 h-10 @if($dokumen->tipe_file == 'pdf') bg-red-100 text-red-600 @else bg-blue-100 text-blue-600 @endif rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $dokumen->nama_file }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ strtoupper($dokumen->tipe_file) }} • {{ number_format($dokumen->ukuran_file / 1024, 2) }} KB</p>
                                        @if($dokumen->deskripsi)
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 italic">{{ $dokumen->deskripsi }}</p>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $dokumen->path) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Preview
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Tidak ada dokumen evaluasi</p>
                    @endif
                </div>
            </div>

            <!-- Card 2: Link Pembelajaran -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-green-600 dark:bg-green-700 px-6 py-4 border-b border-green-700 dark:border-green-800">
                    <h3 class="text-base font-semibold text-white">Link Pembelajaran</h3>
                </div>
                <!-- Card Content -->
                <div class="p-6">
                    @if($supervisi->prosesPembelajaran)
                        <div class="space-y-4">
                            @if($supervisi->prosesPembelajaran->link_video)
                            <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-r-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Video Pembelajaran</p>
                                        <a href="{{ $supervisi->prosesPembelajaran->link_video }}" 
                                           target="_blank"
                                           class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 break-all inline-flex items-center gap-1">
                                            {{ $supervisi->prosesPembelajaran->link_video }}
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($supervisi->prosesPembelajaran->link_meeting)
                            <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-r-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Link Meeting</p>
                                        <a href="{{ $supervisi->prosesPembelajaran->link_meeting }}" 
                                           target="_blank"
                                           class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 break-all inline-flex items-center gap-1">
                                            {{ $supervisi->prosesPembelajaran->link_meeting }}
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(!$supervisi->prosesPembelajaran->link_video && !$supervisi->prosesPembelajaran->link_meeting)
                            <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Tidak ada link pembelajaran</p>
                            @endif
                        </div>
                    @else
                        <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Tidak ada data proses pembelajaran</p>
                    @endif
                </div>
            </div>

            <!-- Card 3: Refleksi -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-purple-600 dark:bg-purple-700 px-6 py-4 border-b border-purple-700 dark:border-purple-800">
                    <h3 class="text-base font-semibold text-white">Refleksi</h3>
                </div>
                <!-- Card Content -->
                <div class="p-6">
                    @if($supervisi->prosesPembelajaran)
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @if($supervisi->prosesPembelajaran->refleksi_1)
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border-l-4 border-purple-500">
                                <h4 class="text-sm font-semibold text-purple-900 dark:text-purple-300 mb-2">1. Apa yang sudah berjalan dengan baik?</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $supervisi->prosesPembelajaran->refleksi_1 }}</p>
                            </div>
                            @endif

                            @if($supervisi->prosesPembelajaran->refleksi_2)
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border-l-4 border-blue-500">
                                <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">2. Apa yang masih menjadi tantangan?</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $supervisi->prosesPembelajaran->refleksi_2 }}</p>
                            </div>
                            @endif

                            @if($supervisi->prosesPembelajaran->refleksi_3)
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border-l-4 border-green-500">
                                <h4 class="text-sm font-semibold text-green-900 dark:text-green-300 mb-2">3. Apa yang akan saya lakukan untuk meningkatkan pembelajaran?</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $supervisi->prosesPembelajaran->refleksi_3 }}</p>
                            </div>
                            @endif

                            @if($supervisi->prosesPembelajaran->refleksi_4)
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 border-l-4 border-amber-500">
                                <h4 class="text-sm font-semibold text-amber-900 dark:text-amber-300 mb-2">4. Apa dukungan yang saya butuhkan?</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $supervisi->prosesPembelajaran->refleksi_4 }}</p>
                            </div>
                            @endif

                            @if($supervisi->prosesPembelajaran->refleksi_5)
                            <div class="bg-rose-50 dark:bg-rose-900/20 rounded-lg p-4 border-l-4 border-rose-500">
                                <h4 class="text-sm font-semibold text-rose-900 dark:text-rose-300 mb-2">5. Refleksi tambahan</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $supervisi->prosesPembelajaran->refleksi_5 }}</p>
                            </div>
                            @endif

                            @if(!$supervisi->prosesPembelajaran->refleksi_1 && !$supervisi->prosesPembelajaran->refleksi_2 && !$supervisi->prosesPembelajaran->refleksi_3 && !$supervisi->prosesPembelajaran->refleksi_4 && !$supervisi->prosesPembelajaran->refleksi_5)
                            <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Tidak ada data refleksi</p>
                            @endif
                        </div>
                    @else
                        <p class="text-slate-500 dark:text-gray-400 text-sm text-center py-4">Tidak ada data refleksi</p>
                    @endif
                </div>
            </div>

            <!-- Card 4: Riwayat Feedback & Komentar -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-700 dark:to-indigo-700 px-6 py-4">
                    <h3 class="text-base font-semibold text-white">Diskusi & Feedback</h3>
                </div>
                <!-- Card Content -->
                <div class="p-6">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 dark:border-emerald-600 rounded-r-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-emerald-700 dark:text-emerald-300 text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Feedback List -->
                    <div class="space-y-3 max-h-96 overflow-y-auto pb-2 mb-4">
                    @if($supervisi->feedback && $supervisi->feedback->count() > 0)
                        @foreach($supervisi->feedback->whereNull('parent_id')->sortByDesc('created_at') as $fb)
                        <div class="border-l-4 {{ $fb->is_revision_request ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : ($fb->user_id == auth()->id() ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-amber-500 bg-amber-50 dark:bg-amber-900/20') }} rounded-r-lg p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br {{ $fb->user_id == auth()->id() ? 'from-blue-500 to-indigo-600' : 'from-amber-500 to-orange-600' }} rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-md">
                                    {{ strtoupper(substr($fb->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $fb->user->name ?? 'User' }}</span>

                                            @if($fb->user->role === 'kepala_sekolah')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                    </svg>
                                                    Kepala Sekolah
                                                </span>
                                            @elseif($fb->user_id == auth()->id())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    Anda
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                    Guru
                                                </span>
                                            @endif

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
                                            {{ $fb->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $fb->komentar }}</p>

                                    @if($fb->is_revision_request)
                                        <div class="mt-3 p-3 bg-red-100 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-800/50">
                                            <p class="text-xs font-semibold text-red-800 dark:text-red-300 mb-1">⚠️ Tindakan Diperlukan</p>
                                            <p class="text-xs text-red-700 dark:text-red-400">Silakan lakukan revisi sesuai feedback di atas dan submit ulang supervisi Anda.</p>
                                        </div>
                                    @endif

                                    <!-- Reply Button -->
                                    <div class="mt-3">
                                        <button onclick="toggleReplyForm({{ $fb->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                            </svg>
                                            Balas
                                        </button>
                                    </div>

                                    <!-- Reply Form -->
                                    <div id="reply-form-{{ $fb->id }}" class="hidden mt-3 pl-4 border-l-2 border-indigo-200 dark:border-indigo-800">
                                        <form action="{{ route('guru.supervisi.comment', $supervisi->id) }}" method="POST" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $fb->id }}">
                                            <textarea
                                                name="komentar"
                                                rows="2"
                                                required
                                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                                                placeholder="Tulis balasan Anda..."></textarea>
                                            <div class="flex gap-2">
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                    </svg>
                                                    Kirim
                                                </button>
                                                <button type="button" onclick="toggleReplyForm({{ $fb->id }})" class="px-3 py-1.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 text-xs font-semibold rounded-lg transition-all">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Nested Replies -->
                                    @if($fb->replies && $fb->replies->count() > 0)
                                        <div class="mt-4 ml-6 space-y-3 pl-4 border-l-2 border-gray-200 dark:border-gray-700">
                                            @foreach($fb->replies as $reply)
                                            <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3">
                                                <div class="flex items-start gap-2">
                                                    <div class="w-8 h-8 bg-gradient-to-br {{ $reply->user_id == auth()->id() ? 'from-blue-400 to-indigo-500' : 'from-gray-400 to-gray-600' }} rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                                        {{ strtoupper(substr($reply->user->name ?? 'U', 0, 2)) }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $reply->user->name ?? 'User' }}</span>

                                                            @if($reply->user->role === 'kepala_sekolah')
                                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                                                                    <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                                    </svg>
                                                                    Kepsek
                                                                </span>
                                                            @elseif($reply->user_id == auth()->id())
                                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                                    Anda
                                                                </span>
                                                            @endif

                                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-gray-700 dark:text-gray-300">{{ $reply->komentar }}</p>

                                                        <!-- Reply to reply button -->
                                                        <div class="mt-2">
                                                            <button onclick="toggleReplyForm({{ $reply->id }})" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                                </svg>
                                                                Balas
                                                            </button>
                                                        </div>

                                                        <!-- Reply to reply form -->
                                                        <div id="reply-form-{{ $reply->id }}" class="hidden mt-2">
                                                            <form action="{{ route('guru.supervisi.comment', $supervisi->id) }}" method="POST" class="space-y-2">
                                                                @csrf
                                                                <input type="hidden" name="parent_id" value="{{ $fb->id }}">
                                                                <textarea
                                                                    name="komentar"
                                                                    rows="2"
                                                                    required
                                                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                                                                    placeholder="Tulis balasan Anda..."></textarea>
                                                                <div class="flex gap-2">
                                                                    <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded transition-all">
                                                                        Kirim
                                                                    </button>
                                                                    <button type="button" onclick="toggleReplyForm({{ $reply->id }})" class="px-2 py-1 text-gray-600 dark:text-gray-400 text-xs font-semibold rounded transition-all">
                                                                        Batal
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada komentar</p>
                        </div>
                    @endif
                    </div>

                    <!-- Add Comment Form -->
                    @if($supervisi->status !== 'draft')
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <form action="{{ route('guru.supervisi.comment', $supervisi->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label for="komentar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tambahkan Komentar atau Balasan
                                </label>
                                <textarea
                                    name="komentar"
                                    id="komentar"
                                    rows="3"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm resize-none"
                                    placeholder="Tulis komentar, pertanyaan, atau balasan Anda di sini..."></textarea>
                                @error('komentar')
                                    <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Kirim Komentar
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

        </div>
        <!-- End Vertical Card Layout -->
    </div>
</div>

<script>
function toggleReplyForm(id) {
    const form = document.getElementById('reply-form-' + id);
    if (form) {
        form.classList.toggle('hidden');
    }
}
</script>

@endsection
