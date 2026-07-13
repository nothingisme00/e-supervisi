@extends('layouts.modern')

@section('page-title', 'Feedback Supervisi - ' . $supervisi->user->name)

@section('content')
<div class="w-full lg:w-3/4 mx-auto px-0 sm:px-4 pb-24 md:pb-0">

    <x-evaluasi-guru-header :supervisi="$supervisi" />
    <x-evaluasi-stepper :supervisi="$supervisi" :aktif="3" />

    <div class="space-y-4 sm:space-y-6">
        @if($supervisi->status !== 'submitted')
        <!-- Card 3.5: Rubrik Penilaian -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <x-card-header title="Rubrik Penilaian" />
            <div class="p-3 sm:p-4 md:p-6">
                @if($supervisi->evaluasiRubrik)
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
                    <div class="flex gap-3">
                        @if($supervisi->status !== 'completed')
                            <a href="{{ route('kepala.evaluasi.rubrik', $supervisi->id) }}" wire:navigate class="px-4 py-2 rounded-xl text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700">Edit Rubrik</a>
                        @endif
                        <a href="{{ route('kepala.evaluasi.rubrik.pdf', $supervisi->id) }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">Unduh PDF</a>
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Rubrik penilaian belum diisi.</p>
                    <a href="{{ route('kepala.evaluasi.rubrik', $supervisi->id) }}" wire:navigate class="inline-block px-4 py-2 rounded-xl text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700">Isi Rubrik Penilaian</a>
                @endif
            </div>
        </div>

        <!-- Card 4: Riwayat Feedback & Diskusi -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Card Header -->
            <x-card-header title="Diskusi & Feedback" />
            <!-- Card Content -->
            <div class="p-3 sm:p-4 md:p-6">
                @include('supervisi._feedback-thread', [
                    'feedbacks' => $supervisi->feedback,
                    'supervisi' => $supervisi,
                    'action' => route('kepala.evaluasi.feedback', $supervisi->id),
                    'readonly' => $supervisi->status === 'completed',
                    'minlength' => 10,
                    'revisionNoteTitle' => 'Revisi Diminta',
                    'revisionNote' => 'Guru akan melakukan revisi sesuai feedback di atas.',
                ])
            </div>
        </div>

        <!-- Card 5: Berikan Feedback -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Card Header -->
            <x-card-header title="Berikan Feedback" />
            <!-- Card Content -->
            <div class="p-3 sm:p-4 md:p-6">
                @if($supervisi->status === 'completed')
                    <!-- Status Completed Message -->
                    <div class="text-center py-6 sm:py-8">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2">Supervisi Telah Selesai Ditinjau</h4>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-3 sm:mb-4">
                            Supervisi ini telah ditandai sebagai selesai. Anda masih dapat melihat riwayat feedback di atas.
                        </p>
                        <a href="{{ route('kepala.evaluasi.index') }}"
                           class="inline-flex items-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-5 sm:py-2.5 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar Evaluasi
                        </a>
                    </div>
                @else
                <form action="{{ route('kepala.evaluasi.feedback', $supervisi->id) }}" method="POST" class="space-y-4 sm:space-y-5">
            @csrf

            <div>
                <label for="komentar" class="block text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100 mb-1.5 sm:mb-2">
                    Komentar dan Saran
                </label>
                <textarea
                    name="komentar"
                    id="komentar"
                    rows="4"
                    class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-xs sm:text-sm resize-none"
                    placeholder="Berikan feedback, komentar, atau saran untuk guru..."
                    required minlength="10">{{ old('komentar') }}</textarea>
                @error('komentar')
                    <p class="mt-1.5 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-start gap-2 p-3 sm:p-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg border border-amber-300 dark:border-amber-700">
                <input
                    type="checkbox"
                    name="is_revision_request"
                    id="is_revision_request"
                    value="1"
                    class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 bg-white dark:bg-gray-700 border-amber-400 dark:border-amber-600 rounded focus:ring-0 mt-0.5">
                <label for="is_revision_request" class="text-xs sm:text-sm font-semibold text-amber-900 dark:text-amber-200 cursor-pointer">
                    Minta revisi untuk supervisi ini
                </label>
            </div>

            <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-2 sm:gap-3 pt-3 sm:pt-4">
                <button type="submit"
                        class="px-4 py-2 sm:px-5 sm:py-2.5 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors">
                    Kirim Feedback
                </button>
            </div>
                </form>
                @endif
            </div>
        </div>
        @else
        <!-- Empty State: Review belum dimulai -->
        <x-card class="p-6 sm:p-8 text-center">
            <x-icon name="information-circle" class="w-10 h-10 text-primary-500 dark:text-primary-400 mx-auto mb-3" />
            <h4 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2">Review Belum Dimulai</h4>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-4 max-w-md mx-auto">
                Mulai review di langkah 1 (Tinjau Materi) untuk membuka rubrik penilaian dan feedback.
            </p>
            <x-button href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" class="inline-flex">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Ke Langkah 1
            </x-button>
        </x-card>
        @endif
    </div>

    <x-evaluasi-action-bar :langkah="3" judul="Feedback">
        <x-button href="{{ route('kepala.evaluasi.rubrik', $supervisi->id) }}" variant="secondary">
            <x-icon name="arrow-left" class="w-4 h-4" />
            Kembali
        </x-button>
        @if ($supervisi->status === 'under_review')
            <x-button type="button" id="completeButton" onclick="confirmComplete()">
                <x-icon name="check-circle" class="w-4 h-4" />
                Tandai Selesai
            </x-button>
        @endif
    </x-evaluasi-action-bar>
</div>

<!-- Complete Form (Hidden) -->
<form id="completeForm" action="{{ route('kepala.evaluasi.complete', $supervisi->id) }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
// Complete Confirmation Function
function confirmComplete() {
    const completeButton = document.getElementById('completeButton');

    // Prevent submission if button is disabled
    if (completeButton && completeButton.disabled) {
        return false;
    }

    showConfirmModal(
        'Apakah Anda yakin ingin menandai supervisi ini sebagai selesai ditinjau? Status akan berubah menjadi "Telah Selesai" dan supervisi akan dipindahkan ke tab Telah Selesai.',
        'Konfirmasi Selesaikan Tinjauan',
        function() {
            document.getElementById('completeForm').submit();
        },
        { type: 'info', confirmText: 'Ya, Selesaikan' }
    );
}

// Toggle Complete Button based on Revision Checkbox
document.addEventListener('DOMContentLoaded', function() {
    const revisionCheckbox = document.getElementById('is_revision_request');
    const completeButton = document.getElementById('completeButton');

    if (revisionCheckbox && completeButton) {
        revisionCheckbox.addEventListener('change', function() {
            // opacity & pointer-events ikut varian disabled: bawaan x-button;
            // cukup set atribut disabled + kursor.
            completeButton.disabled = this.checked;
            completeButton.classList.toggle('cursor-not-allowed', this.checked);
        });
    }
});

// Toggle reply form visibility
function toggleReplyForm(id) {
    const form = document.getElementById('reply-form-' + id);
    if (form) {
        form.classList.toggle('hidden');
    }
}
</script>

@endsection
