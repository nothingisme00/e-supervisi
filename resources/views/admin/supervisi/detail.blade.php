@extends('layouts.modern')

@section('page-title', 'Detail Supervisi - ' . $supervisi->user->name)

@section('content')
<div class="w-full lg:w-3/4 mx-auto">

    <x-page-header title="Detail Supervisi" subtitle="Tinjau kelengkapan supervisi dan berikan feedback" :back-url="route('admin.supervisi.index')" />

    <!-- Kartu Identitas Guru -->
    <x-card class="p-4 sm:p-6 mb-6">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-4 min-w-0">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-lg sm:text-xl shrink-0">
                    {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate">{{ $supervisi->user->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 truncate">{{ $supervisi->user->email }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">NIK: {{ $supervisi->user->nik }}</p>
                </div>
            </div>
            <div class="text-right shrink-0">
                <x-status-badge :status="$supervisi->status" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Disubmit: {{ $supervisi->updated_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>
    </x-card>

    <!-- Grid Layout: 2x2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 items-start mb-6 sm:mb-8">

        <!-- Dokumen Evaluasi Diri -->
        <x-card flush>
            <x-card-header title="Dokumen Evaluasi Diri" />
            <div class="p-4 sm:p-5 max-h-96 overflow-y-auto space-y-2">
                @forelse($supervisi->dokumenEvaluasi as $index => $dokumen)
                    <div class="flex items-center justify-between gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <x-icon name="document" class="w-6 h-6 {{ str_ends_with($dokumen->path_file, '.pdf') ? 'text-red-500' : 'text-emerald-500' }} shrink-0" />
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Dokumen {{ $index + 1 }}</p>
                                @if($dokumen->deskripsi)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $dokumen->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                        <x-button href="{{ route('admin.supervisi.download', $dokumen->id) }}" variant="secondary" size="sm">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            <span class="hidden sm:inline">Download</span>
                        </x-button>
                    </div>
                @empty
                    <x-empty-state
                        icon="document"
                        title="Tidak ada dokumen"
                        description="Guru belum mengunggah dokumen evaluasi diri"
                        :compact="true"
                    />
                @endforelse
            </div>
        </x-card>

        <!-- Link Pembelajaran -->
        <x-card flush>
            <x-card-header title="Link Pembelajaran" />
            <div class="p-4 sm:p-5 max-h-96 overflow-y-auto space-y-3">
                @if($supervisi->prosesPembelajaran)
                    @if($supervisi->prosesPembelajaran->link_video)
                        <x-video-praktik :url="$supervisi->prosesPembelajaran->link_video" />
                    @endif

                    @if($supervisi->prosesPembelajaran->link_meeting)
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/50">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Link Meeting/Zoom</p>
                                    <a href="{{ $supervisi->prosesPembelajaran->link_meeting }}"
                                       target="_blank"
                                       class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 underline break-all">
                                        {{ Str::limit($supervisi->prosesPembelajaran->link_meeting, 50) }}
                                    </a>
                                </div>
                                <a href="{{ $supervisi->prosesPembelajaran->link_meeting }}"
                                   target="_blank"
                                   aria-label="Buka link meeting di tab baru"
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 shrink-0">
                                    <x-icon name="arrow-top-right-on-square" class="w-5 h-5" />
                                </a>
                            </div>
                        </div>
                    @endif

                    @if(!$supervisi->prosesPembelajaran->link_video && !$supervisi->prosesPembelajaran->link_meeting)
                        <x-empty-state
                            icon="link"
                            title="Tidak ada link"
                            description="Tidak ada link pembelajaran yang dibagikan"
                            :compact="true"
                        />
                    @endif
                @else
                    <x-empty-state
                        icon="link"
                        title="Tidak ada data"
                        description="Data proses pembelajaran belum diisi"
                        :compact="true"
                    />
                @endif
            </div>
        </x-card>

        <!-- Refleksi Pembelajaran -->
        <x-card flush>
            <x-card-header title="Refleksi Pembelajaran" />
            <div class="p-4 sm:p-5 max-h-96 overflow-y-auto space-y-4">
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
                            <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ $index + 1 }}. {{ $reflection['label'] }}</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $reflection['value'] }}</p>
                            </div>
                        @endif
                    @endforeach

                    @if(!$supervisi->prosesPembelajaran->refleksi_1 && !$supervisi->prosesPembelajaran->refleksi_2 && !$supervisi->prosesPembelajaran->refleksi_3 && !$supervisi->prosesPembelajaran->refleksi_4 && !$supervisi->prosesPembelajaran->refleksi_5)
                        <x-empty-state
                            icon="document"
                            title="Tidak ada refleksi"
                            description="Guru belum mengisi refleksi pembelajaran"
                            :compact="true"
                        />
                    @endif
                @else
                    <x-empty-state
                        icon="document"
                        title="Tidak ada data"
                        description="Data refleksi pembelajaran belum diisi"
                        :compact="true"
                    />
                @endif
            </div>
        </x-card>

        <!-- Riwayat Feedback (readonly — admin tidak punya form reply) -->
        <x-card flush>
            <x-card-header title="Riwayat Feedback" />
            <div class="p-4 sm:p-5">
                @include('supervisi._feedback-thread', [
                    'feedbacks' => $supervisi->feedback,
                    'supervisi' => $supervisi,
                    'readonly' => true,
                ])
            </div>
        </x-card>

    </div>

    <!-- Feedback Form Section -->
    <x-card class="p-4 sm:p-6 mb-6">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Berikan Feedback</h2>

        <form action="{{ route('admin.supervisi.feedback', $supervisi->id) }}" method="POST" class="space-y-4">
            @csrf

            <x-form.field label="Komentar / Feedback *" name="komentar">
                <textarea
                    name="komentar"
                    id="komentar"
                    rows="6"
                    required
                    class="form-control resize-none"
                    placeholder="Tuliskan feedback Anda untuk guru..."
                >{{ old('komentar') }}</textarea>
            </x-form.field>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    name="mark_completed"
                    id="mark_completed"
                    value="1"
                    class="w-4 h-4 text-primary-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-primary-500 focus:ring-2"
                >
                <label for="mark_completed" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                    Tandai sebagai "Selesai" setelah memberikan feedback
                </label>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-5 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
                <x-button href="{{ route('admin.supervisi.index') }}" variant="secondary">
                    Kembali
                </x-button>
                <div class="flex gap-3">
                    <x-button type="button" variant="danger" onclick="document.getElementById('revisionModal').classList.remove('hidden')">
                        Minta Revisi
                    </x-button>
                    <x-button type="submit">
                        Kirim Feedback
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

</div>

<!-- Revision Request Modal -->
<div id="revisionModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100">Minta Revisi</h3>
                <button
                    type="button"
                    onclick="document.getElementById('revisionModal').classList.add('hidden')"
                    aria-label="Tutup"
                    class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer">
                    <x-icon name="x-mark" class="w-6 h-6" />
                </button>
            </div>

            <form action="{{ route('admin.supervisi.revision', $supervisi->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <x-form.field label="Catatan Revisi *" name="revision_notes">
                        <textarea
                            name="revision_notes"
                            id="revision_notes"
                            rows="5"
                            required
                            minlength="10"
                            class="form-control resize-none"
                            placeholder="Jelaskan apa yang perlu direvisi..."
                        >{{ old('revision_notes') }}</textarea>
                    </x-form.field>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="secondary" onclick="document.getElementById('revisionModal').classList.add('hidden')">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="danger">
                        Kirim Permintaan Revisi
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->has('revision_notes'))
<script>
    // Buka kembali modal bila validasi revision_notes gagal (agar error terlihat)
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('revisionModal').classList.remove('hidden');
    });
</script>
@endif

{{-- Notifikasi sukses ditangani toast global di layouts.modern --}}

@endsection
