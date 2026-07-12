@extends('layouts.modern')

@section('page-title', 'Lihat Supervisi - ' . $supervisi->user->name)

@section('content')
<div class="w-full lg:w-3/4 mx-auto px-0 sm:px-4 pb-24 md:pb-0">

    <x-page-header title="Lihat Supervisi" subtitle="Supervisi rekan sejawat" :back-url="route('guru.home')" />

    <!-- Header Section -->
    <x-card flush class="mb-4 sm:mb-6">
        <!-- Strip aksen atas -->
        <div class="h-1.5 sm:h-2 bg-primary-600"></div>

        <div class="p-4 sm:p-6">
            <!-- Mobile: Stack vertically, Desktop: Side by side -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 sm:gap-4">
                <div class="flex items-center sm:items-start gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-base sm:text-lg ring-2 sm:ring-4 ring-primary-100 dark:ring-primary-900/50 shrink-0">
                        {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-base sm:text-xl lg:text-2xl font-bold text-gray-800 dark:text-white leading-tight">{{ $supervisi->user->name }}</h2>
                        <div class="flex flex-wrap gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1 sm:mt-2">
                            @if($supervisi->user->mata_pelajaran)
                            <div class="flex items-center gap-1">
                                <x-icon name="book-open" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-500 dark:text-gray-400" />
                                <span class="font-medium">{{ $supervisi->user->mata_pelajaran }}</span>
                            </div>
                            @endif
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1 sm:mt-2 flex items-center gap-1">
                            <x-icon name="calendar" class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                            {{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
                <!-- Status Badge -->
                <div class="self-start sm:self-auto mt-1 sm:mt-0">
                    <x-status-badge :status="$supervisi->status" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-end gap-1">
                        <x-icon name="clock" class="w-3.5 h-3.5" />
                        Disubmit: {{ $supervisi->updated_at->translatedFormat('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Vertical Card Layout -->
    <div class="space-y-4 sm:space-y-6">

        <!-- Card 1: Dokumen Evaluasi Diri -->
        <x-card flush>
            <x-card-header title="Dokumen Evaluasi Diri" />
            <div class="p-3 sm:p-4 md:p-6">
                @if($supervisi->dokumenEvaluasi && count($supervisi->dokumenEvaluasi) > 0)
                    <div class="max-h-80 sm:max-h-96 overflow-y-auto space-y-2">
                        @foreach($supervisi->dokumenEvaluasi as $dokumen)
                        <div class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 @if($dokumen->tipe_file == 'pdf') bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 @else bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 @endif rounded-lg flex items-center justify-center shrink-0">
                                <x-icon name="document" class="w-4 h-4 sm:w-5 sm:h-5" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">{{ $dokumen->nama_file }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ strtoupper($dokumen->tipe_file) }} • {{ number_format($dokumen->ukuran_file / 1024, 2) }} KB</p>
                            </div>
                            <x-button href="{{ route('guru.supervisi.preview', $dokumen->id) }}" target="_blank" size="sm" class="shrink-0">
                                <x-icon name="eye" class="w-4 h-4" />
                                <span class="hidden sm:inline">Preview</span>
                            </x-button>
                        </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state
                        icon="document"
                        title="Tidak ada dokumen"
                        description="Tidak ada dokumen evaluasi"
                        :compact="true"
                    />
                @endif
            </div>
        </x-card>

        <!-- Card 2: Link Pembelajaran -->
        <x-card flush>
            <x-card-header title="Link Pembelajaran" />
            <div class="p-3 sm:p-4 md:p-6 space-y-3 sm:space-y-4">
                @if($supervisi->prosesPembelajaran)
                    @if($supervisi->prosesPembelajaran->link_video)
                    <x-video-praktik :url="$supervisi->prosesPembelajaran->link_video" />
                    @endif

                    @if($supervisi->prosesPembelajaran->link_meeting)
                    <!-- Link Meeting -->
                    <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-r-lg p-3 sm:p-4">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <x-icon name="video-camera" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" />
                            <div class="flex-1 min-w-0 overflow-hidden">
                                <div class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mb-1 sm:mb-2">Link Meeting</div>
                                <a href="{{ $supervisi->prosesPembelajaran->link_meeting }}" target="_blank" class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:underline max-w-full">
                                    <x-icon name="arrow-top-right-on-square" class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" />
                                    <span class="truncate">{{ $supervisi->prosesPembelajaran->link_meeting }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!$supervisi->prosesPembelajaran->link_video && !$supervisi->prosesPembelajaran->link_meeting)
                    <x-empty-state
                        icon="link"
                        title="Tidak ada link"
                        description="Tidak ada link pembelajaran"
                        :compact="true"
                    />
                    @endif
                @else
                    <x-empty-state
                        icon="link"
                        title="Tidak ada data"
                        description="Tidak ada data proses pembelajaran"
                        :compact="true"
                    />
                @endif
            </div>
        </x-card>

        <!-- Card 3: Refleksi Pembelajaran -->
        <x-card flush>
            <x-card-header title="Refleksi Pembelajaran" />
            <div class="p-3 sm:p-4 md:p-6">
                @if($supervisi->prosesPembelajaran)
                    <div class="space-y-2 sm:space-y-3 max-h-80 sm:max-h-96 overflow-y-auto">
                        @php
                            $refleksiItems = [
                                ['key' => 'refleksi_1', 'title' => '1. Apa yang sudah berjalan dengan baik?'],
                                ['key' => 'refleksi_2', 'title' => '2. Apa yang masih menjadi tantangan?'],
                                ['key' => 'refleksi_3', 'title' => '3. Apa yang akan saya lakukan untuk meningkatkan pembelajaran?'],
                                ['key' => 'refleksi_4', 'title' => '4. Apa dukungan yang saya butuhkan?'],
                                ['key' => 'refleksi_5', 'title' => '5. Refleksi tambahan'],
                            ];
                            $hasAnyRefleksi = false;
                        @endphp

                        @foreach($refleksiItems as $index => $item)
                            @if($supervisi->prosesPembelajaran->{$item['key']})
                                @php $hasAnyRefleksi = true; @endphp
                                <div class="border-l-4 border-primary-500 bg-primary-50 dark:bg-primary-900/20 rounded-r-lg p-2.5 sm:p-3">
                                    <div class="flex items-start gap-2">
                                        <span class="flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 bg-primary-600 text-white rounded-lg text-xs font-bold shrink-0 tabular-nums">{{ $index + 1 }}</span>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs sm:text-sm font-semibold text-primary-900 dark:text-primary-300 mb-0.5 sm:mb-1 leading-tight">{{ $item['title'] }}</div>
                                            <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $supervisi->prosesPembelajaran->{$item['key']} }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if(!$hasAnyRefleksi)
                        <x-empty-state
                            icon="document"
                            title="Tidak ada refleksi"
                            description="Tidak ada data refleksi"
                            :compact="true"
                        />
                        @endif
                    </div>
                @else
                    <x-empty-state
                        icon="document"
                        title="Tidak ada refleksi"
                        description="Tidak ada data refleksi"
                        :compact="true"
                    />
                @endif
            </div>
        </x-card>

        <!-- Card 4: Diskusi & Feedback -->
        <x-card flush>
            <x-card-header title="Diskusi & Feedback" />
            <div class="p-3 sm:p-4 md:p-6">
                @include('supervisi._feedback-thread', [
                    'feedbacks' => $supervisi->feedback,
                    'supervisi' => $supervisi,
                    'action' => route('guru.supervisi.comment', $supervisi->id),
                ])

                <!-- Add Comment Form -->
                @if($supervisi->status !== 'draft')
                <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-200 dark:border-gray-700">
                    <form action="{{ route('guru.supervisi.comment', $supervisi->id) }}" method="POST" class="space-y-2 sm:space-y-3">
                        @csrf
                        <x-form.field label="Tambahkan Komentar atau Balasan" name="komentar">
                            <textarea name="komentar" id="komentar" rows="3" required class="form-control resize-none" placeholder="Tulis komentar, pertanyaan, atau balasan Anda di sini..."></textarea>
                        </x-form.field>
                        <div class="flex justify-end">
                            <x-button type="submit">
                                <x-icon name="paper-airplane" class="w-4 h-4" />
                                Kirim Komentar
                            </x-button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </x-card>

    </div> <!-- End Vertical Card Layout -->

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
