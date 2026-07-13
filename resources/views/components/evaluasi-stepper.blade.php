{{--
    Stepper 4 langkah alur evaluasi kepala sekolah.
    Status langkah dihitung dari data supervisi; node 1-3 adalah link halaman,
    node 4 (Selesai) indikator saja.

    Props:
        - supervisi (Supervisi, wajib)
        - aktif (int 1-3, wajib): langkah halaman yang sedang dibuka.
--}}
@props(['supervisi', 'aktif'])

@php
    $jumlahItemAktif = \App\Models\RubrikItem::active()->count();
    $rubrikLengkap = $supervisi->evaluasiRubrik
        && $supervisi->evaluasiRubrik->scores->count() >= $jumlahItemAktif
        && $jumlahItemAktif > 0;
    $adaFeedbackKepala = $supervisi->feedback
        ->contains(fn ($f) => $f->user && $f->user->role === 'kepala_sekolah');

    $langkah = [
        1 => ['label' => 'Tinjau Materi',
              'selesai' => in_array($supervisi->status, ['under_review', 'revision', 'completed']),
              'url' => route('kepala.evaluasi.show', $supervisi->id)],
        2 => ['label' => 'Isi Rubrik',
              'selesai' => $rubrikLengkap,
              'url' => route('kepala.evaluasi.rubrik', $supervisi->id)],
        3 => ['label' => 'Feedback',
              'selesai' => $adaFeedbackKepala,
              'url' => route('kepala.evaluasi.feedback.show', $supervisi->id)],
        4 => ['label' => 'Selesai',
              'selesai' => $supervisi->status === 'completed',
              'url' => null],
    ];
@endphp

<x-card class="mb-4 sm:mb-6 px-4 py-4 sm:px-6">
    <ol class="flex items-start">
        @foreach ($langkah as $n => $step)
            @php
                $status = $step['selesai'] ? 'selesai' : ($n === (int) $aktif ? 'aktif' : 'mendatang');
            @endphp
            @if ($n > 1)
                <div aria-hidden="true"
                     class="flex-1 h-0.5 mt-4 sm:mt-5 {{ $langkah[$n - 1]['selesai'] ? 'bg-primary-600' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
            @endif
            <li class="flex flex-col items-center gap-1.5 shrink-0 px-1 sm:px-3"
                data-stepper-step="{{ $n }}" data-status="{{ $status }}">
                @php
                    $lingkaran = match ($status) {
                        'selesai' => 'bg-primary-600 text-white',
                        'aktif' => 'bg-white dark:bg-gray-800 text-primary-700 dark:text-primary-300 border-2 border-primary-600 ring-4 ring-primary-100 dark:ring-primary-900/40',
                        default => 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500',
                    };
                    $labelCls = match ($status) {
                        'selesai' => 'text-primary-700 dark:text-primary-300',
                        'aktif' => 'text-primary-700 dark:text-primary-300 font-bold',
                        default => 'text-gray-400 dark:text-gray-500',
                    };
                @endphp
                @if ($step['url'] && $status !== 'aktif')
                    <a href="{{ $step['url'] }}" wire:navigate
                       class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $lingkaran }}">
                        @if ($step['selesai']) <x-icon name="check" class="w-4 h-4 sm:w-5 sm:h-5" /> @else {{ $n }} @endif
                    </a>
                @else
                    <span class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $lingkaran }}">
                        @if ($step['selesai'] && $status !== 'aktif') <x-icon name="check" class="w-4 h-4 sm:w-5 sm:h-5" /> @else {{ $n }} @endif
                    </span>
                @endif
                <span class="text-[11px] sm:text-xs font-semibold text-center {{ $labelCls }}">{{ $step['label'] }}</span>
            </li>
        @endforeach
    </ol>
</x-card>
