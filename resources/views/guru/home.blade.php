@extends('layouts.modern')

@section('page-title', 'Beranda')

@section('content')
<!-- Header Card -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700 text-white rounded-lg p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p class="text-sm text-indigo-100 dark:text-indigo-200">{{ auth()->user()->mata_pelajaran }} • {{ auth()->user()->tingkat }}</p>
        </div>
        <div>
            <a href="{{ route('guru.supervisi.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-gray-100 text-indigo-600 font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Supervisi Baru
            </a>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Timeline Supervisi</h3>

    @if($supervisiList->count() > 0)
        <div class="grid grid-cols-1 gap-6 mb-6">
            @foreach($supervisiList as $item)
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 relative overflow-hidden group">
                <!-- Badge di pojok kanan atas - hanya untuk card milik sendiri -->
                @if($item->user_id == auth()->id())
                    <div class="absolute top-0 right-0">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-500 dark:to-purple-500 text-white text-xs font-semibold px-3 py-1 rounded-bl-lg shadow-lg">
                            <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Milik Saya
                        </div>
                    </div>
                @endif

                <div class="p-4 pt-10">
                    <div class="flex items-center gap-3 mb-3">
                        @php
                            // Generate color based on first letter
                            $firstLetter = strtoupper(substr($item->user->name, 0, 1));
                            $colors = [
                                'A' => '#ef4444', // red-500
                                'B' => '#f97316', // orange-500
                                'C' => '#f59e0b', // amber-500
                                'D' => '#eab308', // yellow-500
                                'E' => '#84cc16', // lime-500
                                'F' => '#22c55e', // green-500
                                'G' => '#10b981', // emerald-500
                                'H' => '#14b8a6', // teal-500
                                'I' => '#06b6d4', // cyan-500
                                'J' => '#0ea5e9', // sky-500
                                'K' => '#3b82f6', // blue-500
                                'L' => '#6366f1', // indigo-500
                                'M' => '#8b5cf6', // violet-500
                                'N' => '#a855f7', // purple-500
                                'O' => '#d946ef', // fuchsia-500
                                'P' => '#ec4899', // pink-500
                                'Q' => '#f43f5e', // rose-500
                                'R' => '#dc2626', // red-600
                                'S' => '#ea580c', // orange-600
                                'T' => '#d97706', // amber-600
                                'U' => '#ca8a04', // yellow-600
                                'V' => '#65a30d', // lime-600
                                'W' => '#16a34a', // green-600
                                'X' => '#059669', // emerald-600
                                'Y' => '#0d9488', // teal-600
                                'Z' => '#0891b2', // cyan-600
                            ];
                            $avatarBgColor = $colors[$firstLetter] ?? '#6b7280'; // gray-600 as default
                        @endphp

                        <div class="flex-shrink-0">
                            <div class="rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md" style="width: 50px; height: 50px; background-color: {{ $avatarBgColor }};">
                                {{ $firstLetter }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 dark:text-gray-100 text-base mb-0.5 truncate">
                                {{ $item->user->name }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-300 text-sm">{{ $item->user->mata_pelajaran }} • {{ $item->user->tingkat }}</div>
                        </div>
                    </div>

                    <hr class="border-gray-200 dark:border-gray-600 mb-4">

                    <div class="flex flex-wrap gap-2 mb-4">
                        @if($item->status == 'draft')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 dark:bg-yellow-500 text-yellow-800 dark:text-yellow-100 text-xs font-semibold rounded-full shadow-sm">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                Draft
                            </span>
                        @elseif($item->status == 'submitted')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                Disubmit
                            </span>
                        @elseif($item->status == 'under_review')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                Sedang Direview
                            </span>
                        @elseif($item->status == 'completed')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Selesai
                            </span>
                        @elseif($item->status == 'revision')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                Perlu Revisi
                            </span>
                        @endif

                        @php
                            $docCount = $item->dokumenEvaluasi->count();
                            $hasProses = $item->prosesPembelajaran != null;
                        @endphp

                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 text-xs font-semibold rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ $docCount }}/7
                        </span>

                        @if($hasProses)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Proses
                            </span>
                        @endif

                        @if($item->feedback->count() > 0)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                {{ $item->feedback->count() }}
                            </span>
                        @endif
                    </div>

                    <div class="pt-3 border-t-2 border-gray-200 dark:border-gray-600 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300 text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($item->tanggal_supervisi)->format('d M Y') }}
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            @if($item->user_id == auth()->id())
                                @if(in_array($item->status, ['draft', 'revision']))
                                    <form method="POST" action="{{ route('guru.supervisi.delete', $item->id) }}" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supervisi ini? Semua data termasuk dokumen yang telah diupload akan dihapus secara permanen.')">
                                        @csrf
                                        @method('POST')
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg transition-all shadow-sm text-white cursor-pointer"
                                            style="background-color: #e63946;"
                                            onmouseover="this.style.backgroundColor='#d62828'"
                                            onmouseout="this.style.backgroundColor='#e63946'"
                                            title="Hapus supervisi"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                @endif

                                @if($item->status == 'draft')
                                    <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                        Lanjutkan
                                    </a>
                                @elseif($item->status == 'revision')
                                    <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Revisi
                                    </a>
                                @else
                                    <a href="{{ route('guru.supervisi.detail', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('guru.supervisi.detail', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Belum ada supervisi</p>
            <a href="{{ route('guru.supervisi.create') }}" class="inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors">
                Mulai Supervisi Baru
            </a>
        </div>
    @endif
</div>

<!-- Panduan Supervisi untuk Guru - Accordion -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm mb-6">
    <!-- Header - Clickable -->
    <button onclick="toggleGuideAccordion()" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-600 dark:bg-blue-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="text-left">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Panduan Supervisi Pembelajaran</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Ikuti langkah-langkah berikut untuk menyelesaikan supervisi</p>
            </div>
        </div>
        <svg id="guideAccordionIcon" class="w-5 h-5 text-gray-400 dark:text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Content - Collapsible -->
    <div id="guideAccordionContent" class="hidden border-t border-gray-200 dark:border-gray-700">
        <div class="p-6 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 dark:from-gray-800 dark:to-gray-800">
            <div class="space-y-4">
                <!-- Step 1 -->
                <div class="flex gap-3 p-3 bg-white dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                    <div class="shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-600 dark:text-blue-400">1</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Buat Supervisi Baru</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">Klik tombol <strong>"Buat Supervisi Baru"</strong> untuk memulai. Isi tanggal supervisi dan catatan (opsional).</p>
                        <div class="inline-flex items-center gap-1.5 text-xs text-blue-600 dark:text-blue-400 font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Data dapat disimpan sebagai draft kapan saja
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex gap-3 p-3 bg-white dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                    <div class="shrink-0">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-purple-600 dark:text-purple-400">2</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Upload Dokumen Evaluasi Diri (Opsional)</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">Upload 7 dokumen evaluasi (Capaian Pembelajaran, ATP, Kalender, Prota, Prosem, Modul Ajar, Bahan Ajar). Bisa dilewati jika belum siap.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-700 dark:text-gray-300 rounded">
                                Max 10MB
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-700 dark:text-gray-300 rounded">
                                PDF/JPG/PNG
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex gap-3 p-3 bg-white dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                    <div class="shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-green-600 dark:text-green-400">3</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Isi Proses Pembelajaran (Wajib)</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">Masukkan <strong>Link Video Pembelajaran</strong> (wajib), Link Meeting (opsional), dan jawab <strong>5 pertanyaan refleksi</strong> dengan detail minimal 10 karakter.</p>
                        <div class="inline-flex items-center gap-1.5 text-xs text-green-600 dark:text-green-400 font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Data otomatis tersimpan setiap 30 detik
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex gap-3 p-3 bg-white dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                    <div class="shrink-0">
                        <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-amber-600 dark:text-amber-400">4</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Submit Supervisi</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">Setelah semua data lengkap, tombol <strong>"Submit Supervisi"</strong> akan aktif (hijau). Klik untuk mengirim supervisi ke Kepala Sekolah untuk direview.</p>
                        <div class="inline-flex items-center gap-1.5 text-xs text-amber-600 dark:text-amber-400 font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Setelah submit, data tidak dapat diubah kecuali diminta revisi
                        </div>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="flex gap-3 p-3 bg-white dark:bg-gray-900/50 rounded-lg hover:shadow-md transition-shadow">
                    <div class="shrink-0">
                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">5</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Tunggu Review & Feedback</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">Kepala Sekolah akan mereview supervisi Anda. Jika ada yang perlu diperbaiki, status akan berubah menjadi <strong>"Perlu Revisi"</strong> dan Anda dapat melakukan perbaikan.</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-xs font-medium text-blue-600 dark:text-blue-400 rounded-full">
                                Sedang Direview
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 bg-orange-100 dark:bg-orange-900/30 text-xs font-medium text-orange-600 dark:text-orange-400 rounded-full">
                                Perlu Revisi
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-xs font-medium text-green-600 dark:text-green-400 rounded-full">
                                Selesai
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Note -->
            <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-900/50 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">Tips Penting</p>
                        <ul class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed space-y-1 list-disc list-inside">
                            <li>Pastikan link video pembelajaran dapat diakses dengan baik</li>
                            <li>Isi refleksi dengan jujur dan detail untuk hasil review yang lebih baik</li>
                            <li>Gunakan fitur "Simpan Draft" untuk menyimpan progress kapan saja</li>
                            <li>Periksa kembali semua data sebelum melakukan submit</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
    <ul class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed space-y-2 list-disc list-inside">
        <li>Pastikan semua dokumen evaluasi sudah lengkap sebelum submit</li>
        <li>Upload video pembelajaran dengan kualitas yang baik</li>
        <li>Isi refleksi pembelajaran dengan detail dan jujur</li>
        <li>Periksa kembali semua data sebelum mengirim supervisi</li>
    </ul>
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

    // Delete supervisi function
    async function deleteSupervisi(supervisiId) {
        if (!confirm('Apakah Anda yakin ingin menghapus supervisi ini? Semua data termasuk dokumen yang telah diupload akan dihapus secara permanen.')) {
            return;
        }

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
                alert(result.message);
                window.location.reload();
            } else {
                alert(result.message || 'Gagal menghapus supervisi');
            }
        } catch (error) {
            alert('Terjadi kesalahan: ' + error.message);
        }
    }

    // Toggle Accordion for Guide
    function toggleGuideAccordion() {
        const content = document.getElementById('guideAccordionContent');
        const icon = document.getElementById('guideAccordionIcon');
        
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }
</script>
@endsection
