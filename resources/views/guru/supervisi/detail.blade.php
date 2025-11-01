@extends('layouts.modern')

@section('page-title', 'Detail Supervisi')

@section('content')
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
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-indigo-50/30 to-blue-50/30 dark:from-indigo-900/10 dark:to-blue-900/10">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Detail Supervisi</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->format('d F Y') }}</p>
            </div>
            <div>
                @if($supervisi->status == 'draft')
                    <span class="inline-block px-4 py-2 bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300 text-sm font-semibold rounded-lg">Draft</span>
                @elseif($supervisi->status == 'submitted')
                    <span class="inline-block px-4 py-2 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-sm font-semibold rounded-lg">Disubmit</span>
                @elseif($supervisi->status == 'under_review')
                    <span class="inline-block px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-sm font-semibold rounded-lg">Sedang Direview</span>
                @elseif($supervisi->status == 'completed')
                    <span class="inline-block px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-sm font-semibold rounded-lg">Selesai</span>
                @elseif($supervisi->status == 'revision')
                    <span class="inline-block px-4 py-2 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 text-sm font-semibold rounded-lg">Perlu Revisi</span>
                @endif
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($supervisi->catatan)
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-1">Catatan</h4>
                        <p class="text-sm text-blue-800 dark:text-blue-200">{{ $supervisi->catatan }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Dokumen Evaluasi Card -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-purple-50/30 to-pink-50/30 dark:from-purple-900/10 dark:to-pink-900/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-600 dark:bg-purple-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Dokumen Evaluasi Diri</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $supervisi->dokumenEvaluasi->count() }} dari 7 dokumen terupload</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($supervisi->dokumenEvaluasi->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($supervisi->dokumenEvaluasi as $dokumen)
                    <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                @if($dokumen->tipe_file == 'pdf')
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ ucfirst(str_replace('_', ' ', $dokumen->jenis_dokumen)) }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-2 truncate">{{ $dokumen->nama_file }}</div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ strtoupper($dokumen->tipe_file) }}</span>
                                    <span class="text-gray-300 dark:text-gray-600">•</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($dokumen->ukuran_file / 1024, 2) }} KB</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $dokumen->path_file) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada dokumen yang diupload</p>
            </div>
        @endif
    </div>
</div>

<!-- Proses Pembelajaran Card -->
@if($supervisi->prosesPembelajaran)
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-indigo-50/30 to-blue-50/30 dark:from-indigo-900/10 dark:to-blue-900/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 dark:bg-indigo-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Link Pembelajaran</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Link video dan meeting pembelajaran</p>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-4">
        <!-- Link Video -->
        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Link Video Pembelajaran</div>
                    <a href="{{ $supervisi->prosesPembelajaran->link_video }}" target="_blank" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline break-all">
                        {{ $supervisi->prosesPembelajaran->link_video }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Link Meeting -->
        @if($supervisi->prosesPembelajaran->link_meeting)
        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Link Meeting</div>
                    <a href="{{ $supervisi->prosesPembelajaran->link_meeting }}" target="_blank" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline break-all">
                        {{ $supervisi->prosesPembelajaran->link_meeting }}
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Refleksi Pembelajaran Card -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-green-50/30 to-teal-50/30 dark:from-green-900/10 dark:to-teal-900/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-600 dark:bg-green-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Refleksi Pembelajaran</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Hasil refleksi dari proses pembelajaran</p>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-4">
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
            <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-7 h-7 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-lg text-sm font-bold flex-shrink-0">{{ $loop->iteration }}</span>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white mb-2">{{ $question }}</div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $supervisi->prosesPembelajaran->$key }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@else
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-indigo-50/30 to-blue-50/30 dark:from-indigo-900/10 dark:to-blue-900/10">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Proses Pembelajaran</h2>
    </div>
    <div class="p-6 text-center py-8">
        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
        </svg>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada data proses pembelajaran</p>
    </div>
</div>
@endif

<!-- Feedback Card -->
@if($supervisi->feedback && $supervisi->feedback->count() > 0)
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-yellow-50/30 to-orange-50/30 dark:from-yellow-900/10 dark:to-orange-900/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-600 dark:bg-yellow-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Feedback dari Kepala Sekolah</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $supervisi->feedback->count() }} feedback diterima</p>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-4">
        @foreach($supervisi->feedback as $fb)
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-yellow-600 dark:bg-yellow-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                        {{ substr($fb->admin->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $fb->admin->name ?? 'Kepala Sekolah' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($fb->created_at)->format('d M Y, H:i') }}</div>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $fb->feedback }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Action Buttons -->
<div class="flex items-center justify-between">
    <a href="{{ route('guru.home') }}" style="background-color: #eab308; color: white;" class="inline-flex items-center justify-center gap-2 px-5 py-3 font-semibold rounded-lg cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Beranda
    </a>

    @if(in_array($supervisi->status, ['draft', 'revision']))
        <a href="{{ route('guru.supervisi.continue', $supervisi->id) }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-semibold rounded-lg cursor-pointer transition-colors">
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

@endsection
