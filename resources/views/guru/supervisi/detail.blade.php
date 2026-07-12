@extends('layouts.modern')

@section('page-title', 'Detail Supervisi')

@section('content')
<div class="w-full lg:w-3/4 mx-auto px-0 sm:px-4 pb-24 md:pb-0">
    
    <!-- Back Button -->
    <div class="mb-3 sm:mb-4">
        <a href="{{ route('guru.home') }}"
           class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-all duration-200 group text-sm">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">Kembali ke Beranda</span>
        </a>
    </div>


        {{-- Notifikasi sukses/error ditangani toast global di layouts.modern --}}

        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm sm:shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-4 sm:mb-6">
            <!-- Decorative Header Bar -->
            <div class="h-1.5 sm:h-2 bg-primary-600"></div>
            
            <div class="p-4 sm:p-6">
                <!-- Mobile: Stack vertically, Desktop: Side by side -->
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 sm:gap-4">
                    <div class="flex items-center sm:items-start gap-3 sm:gap-4">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-base sm:text-lg shadow-md ring-2 sm:ring-4 ring-primary-100 dark:ring-primary-900/50 flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-base sm:text-xl lg:text-2xl font-bold text-gray-800 dark:text-white leading-tight">Detail Supervisi Pembelajaran</h1>
                            <p class="text-gray-600 dark:text-gray-300 text-xs sm:text-sm truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5 sm:mt-1 flex items-center">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <!-- Status Badge -->
                    <div class="self-start sm:self-auto mt-1 sm:mt-0">
                        <x-status-badge :status="$supervisi->status" />
                    </div>
                </div>
                
                @if($supervisi->catatan)
                <div class="mt-3 sm:mt-4 p-3 sm:p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 dark:border-blue-600 rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-blue-900 dark:text-blue-200 mb-0.5 sm:mb-1">Catatan Supervisi</p>
                            <p class="text-xs sm:text-sm text-blue-800 dark:text-blue-300">{{ $supervisi->catatan }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Vertical Card Layout -->
        <div class="space-y-4 sm:space-y-6">


            <!-- Card 1: Dokumen Evaluasi Diri -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Card Header -->
                <x-card-header title="Dokumen Evaluasi Diri" />
                <!-- Card Content -->
                <div class="p-3 sm:p-4 md:p-6">
                @if($supervisi->dokumenEvaluasi->count() > 0)
                    <div class="max-h-80 sm:max-h-96 overflow-y-auto space-y-2">
                        @foreach($supervisi->dokumenEvaluasi as $dokumen)
                            <div class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-all duration-200">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 @if($dokumen->tipe_file == 'pdf') bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 @else bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 @endif rounded-lg flex items-center justify-center flex-shrink-0">
                                    @if($dokumen->tipe_file == 'pdf')
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white truncate">{{ ucfirst(str_replace('_', ' ', $dokumen->jenis_dokumen)) }}</div>
                                    <div class="text-[10px] sm:text-xs text-gray-600 dark:text-gray-400 truncate">{{ $dokumen->nama_file }}</div>
                                </div>
                                <a href="{{ route('guru.supervisi.preview', $dokumen->id) }}" target="_blank" class="p-1.5 sm:p-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-all duration-200 flex-shrink-0 active:scale-95">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 sm:py-12">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Belum ada dokumen yang diupload</p>
                    </div>
                @endif
                </div>
            </div>


    <!-- Card 2: Link Pembelajaran -->
    @if($supervisi->prosesPembelajaran)
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <x-card-header title="Link Pembelajaran" />
        <div class="p-3 sm:p-4 md:p-6 space-y-3 sm:space-y-4">
                @if($supervisi->prosesPembelajaran->link_video)
                <x-video-praktik :url="$supervisi->prosesPembelajaran->link_video" />
                @endif

                <!-- Link Meeting -->
                @if($supervisi->prosesPembelajaran->link_meeting)
                <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-r-lg p-3 sm:p-4">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <div class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mb-1 sm:mb-2">Link Meeting</div>
                            <a href="{{ $supervisi->prosesPembelajaran->link_meeting }}" target="_blank" class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:underline max-w-full group">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                <span class="truncate">{{ $supervisi->prosesPembelajaran->link_meeting }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @else
    <!-- Empty State untuk Link Pembelajaran -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <x-card-header title="Link Pembelajaran" />
        <div class="p-4 sm:p-6 text-center py-6 sm:py-8">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Belum ada data link pembelajaran</p>
        </div>
    </div>
    @endif

    <!-- Card 3: Refleksi Pembelajaran -->
    @if($supervisi->prosesPembelajaran)
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <x-card-header title="Refleksi Pembelajaran" />
        <div class="p-3 sm:p-4 md:p-6">
            <div class="space-y-2 sm:space-y-3 max-h-80 sm:max-h-96 overflow-y-auto">
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
                    <div class="border-l-4 @if($loop->iteration == 1) border-green-500 bg-green-50 dark:bg-green-900/20 @elseif($loop->iteration == 2) border-blue-500 bg-blue-50 dark:bg-blue-900/20 @elseif($loop->iteration == 3) border-primary-500 bg-primary-50 dark:bg-primary-900/20 @elseif($loop->iteration == 4) border-primary-500 bg-primary-50 dark:bg-primary-900/20 @else border-teal-500 bg-teal-50 dark:bg-teal-900/20 @endif rounded-r-lg p-2.5 sm:p-3">
                        <div class="flex items-start gap-2">
                            <span class="flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 @if($loop->iteration == 1) bg-green-600 text-white @elseif($loop->iteration == 2) bg-blue-600 text-white @elseif($loop->iteration == 3) bg-primary-600 text-white @elseif($loop->iteration == 4) bg-primary-600 text-white @else bg-teal-600 text-white @endif rounded text-[10px] sm:text-xs font-bold flex-shrink-0">{{ $loop->iteration }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mb-0.5 sm:mb-1 leading-tight">{{ $question }}</div>
                                <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $supervisi->prosesPembelajaran->$key }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <!-- Empty State untuk Refleksi Pembelajaran -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <x-card-header title="Refleksi Pembelajaran" />
        <div class="p-4 sm:p-6 text-center py-6 sm:py-8">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Belum ada data refleksi pembelajaran</p>
        </div>
    </div>
    @endif

    @if($supervisi->evaluasiRubrik)
    <!-- Card 3.5: Hasil Rubrik Penilaian -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <x-card-header title="Hasil Rubrik Penilaian" />
        <div class="p-4 sm:p-6">
            <div class="flex flex-wrap items-center gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Akhir</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $supervisi->evaluasiRubrik->nilai_akhir }}%</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Skor</p>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $supervisi->evaluasiRubrik->skor_total }}/{{ $supervisi->evaluasiRubrik->skor_maksimal }}</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">
                    {{ \App\Models\PredikatRubrik::where('kode', $supervisi->evaluasiRubrik->predikat)->value('label') ?? $supervisi->evaluasiRubrik->predikat }}
                </span>
            </div>
            @if($supervisi->evaluasiRubrik->masukan_umum)
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">{{ $supervisi->evaluasiRubrik->masukan_umum }}</p>
            @endif
            <a href="{{ route('guru.supervisi.rubrik.pdf', $supervisi->id) }}" class="inline-block px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">Unduh PDF</a>
        </div>
    </div>
    @endif

    <!-- Card 4: Diskusi & Feedback -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <x-card-header title="Diskusi & Feedback" />
        <div class="p-3 sm:p-4 md:p-6">
            @include('supervisi._feedback-thread', [
                'feedbacks' => $supervisi->feedback,
                'supervisi' => $supervisi,
                'action' => route('guru.supervisi.comment', $supervisi->id),
                'revisionNoteTitle' => 'Tindakan Diperlukan',
                'revisionNote' => 'Silakan lakukan revisi sesuai feedback di atas dan submit ulang supervisi Anda.',
            ])

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
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-primary-500 dark:focus:border-primary-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm resize-none"
                            placeholder="Tulis komentar, pertanyaan, atau balasan Anda di sini..."></textarea>
                        @error('komentar')
                            <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
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

</div> <!-- End Vertical Card Layout -->

<!-- Action Buttons Section -->
@if(in_array($supervisi->status, ['draft', 'revision']))
<div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
    <div class="flex justify-center">
        <a href="{{ route('guru.supervisi.continue', $supervisi->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:px-6 sm:py-3 text-sm sm:text-base @if($supervisi->status == 'revision') bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 @else bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 @endif text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 active:scale-95">
        @if($supervisi->status == 'revision')
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Lakukan Revisi
        @else
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
            Lanjutkan Supervisi
        @endif
    </a>
    </div>
</div>
@endif

    </div> <!-- End container -->

<script>
function toggleReplyForm(id) {
    const form = document.getElementById('reply-form-' + id);
    if (form) {
        form.classList.toggle('hidden');
    }
}
</script>

@endsection
