{{--
    Kolom kartu review supervisi (dipakai kepala.dashboard, 3 kolom per status).

    Props:
        - accent (string, wajib): 'blue' | 'amber' | 'emerald' — selaras warna x-status-badge
          (submitted -> biru, under_review -> amber, completed -> emerald).
        - icon (string, wajib): nama x-icon untuk header kolom.
        - title / subtitle (string, wajib): judul & keterangan header kolom.
        - count (int, wajib): angka ringkasan di kanan header.
        - list (Collection<Supervisi>, wajib): daftar supervisi dengan relasi user ter-eager-load.
        - useReviewedAt (bool, wajib): true jika tanggal item memakai reviewed_at (fallback updated_at).
        - buttonLabel / buttonIcon / buttonVariant (string, wajib): tombol aksi tiap item.
        - emptyIcon / emptyDescription (string, wajib): tampilan saat daftar kosong.
--}}
@props([
    'accent',
    'icon',
    'title',
    'subtitle',
    'count',
    'list',
    'useReviewedAt',
    'buttonLabel',
    'buttonIcon',
    'buttonVariant',
    'emptyIcon',
    'emptyDescription',
])

@php
    // Kelas Tailwind ditulis utuh per accent agar terdeteksi saat build Vite.
    $accentClasses = [
        'blue' => [
            'bar' => 'bg-blue-500',
            'iconBox' => 'bg-blue-50 dark:bg-blue-900/30',
            'iconText' => 'text-blue-600 dark:text-blue-400',
            'count' => 'text-blue-600 dark:text-blue-400',
            'itemHover' => 'hover:border-blue-300 dark:hover:border-blue-700',
            'avatar' => 'bg-blue-600',
        ],
        'amber' => [
            'bar' => 'bg-amber-500',
            'iconBox' => 'bg-amber-50 dark:bg-amber-900/30',
            'iconText' => 'text-amber-600 dark:text-amber-400',
            'count' => 'text-amber-600 dark:text-amber-400',
            'itemHover' => 'hover:border-amber-300 dark:hover:border-amber-700',
            'avatar' => 'bg-amber-600',
        ],
        'emerald' => [
            'bar' => 'bg-emerald-500',
            'iconBox' => 'bg-emerald-100 dark:bg-emerald-900/30',
            'iconText' => 'text-emerald-600 dark:text-emerald-400',
            'count' => 'text-emerald-600 dark:text-emerald-400',
            'itemHover' => 'hover:border-emerald-300 dark:hover:border-emerald-700',
            'avatar' => 'bg-emerald-600',
        ],
    ][$accent];
@endphp

<x-card flush>
    <div class="h-1 {{ $accentClasses['bar'] }}"></div>
    <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                <div class="w-10 h-10 sm:w-14 sm:h-14 {{ $accentClasses['iconBox'] }} rounded-lg sm:rounded-xl flex items-center justify-center shrink-0">
                    <x-icon :name="$icon" class="w-5 h-5 sm:w-7 sm:h-7 {{ $accentClasses['iconText'] }}" />
                </div>
                <div class="min-w-0">
                    <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white truncate">{{ $title }}</h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
                </div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-xl sm:text-3xl font-bold {{ $accentClasses['count'] }} tabular-nums">{{ $count }}</div>
                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">Supervisi</div>
            </div>
        </div>
    </div>
    <div class="p-3 sm:p-5 max-h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900/30">
        @if($list->count() > 0)
        <div class="space-y-2 sm:space-y-3">
            @foreach($list as $supervisi)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-3 sm:p-4 border border-gray-200 dark:border-gray-700 {{ $accentClasses['itemHover'] }} hover:shadow-md transition-all">
                <div class="flex items-start gap-2 sm:gap-3 mb-2 sm:mb-3">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 {{ $accentClasses['avatar'] }} rounded-lg flex items-center justify-center text-white font-bold text-sm sm:text-base shrink-0">
                        {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white truncate mb-0.5 sm:mb-1">{{ $supervisi->user->name }}</div>
                        <div class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                            <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-gray-100 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 rounded-lg sm:rounded-lg font-medium text-xs sm:text-sm">{{ $supervisi->user->mata_pelajaran ?? '-' }}</span>
                            <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-gray-100 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 rounded-lg sm:rounded-lg font-medium text-xs sm:text-sm">{{ $supervisi->user->tingkat ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2 sm:mb-3">
                    <x-icon name="calendar" class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                    {{ ($useReviewedAt && $supervisi->reviewed_at ? $supervisi->reviewed_at : $supervisi->updated_at)->translatedFormat('d M Y, H:i') }}
                </div>
                <x-button href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" :variant="$buttonVariant" size="sm" class="w-full justify-center">
                    <x-icon :name="$buttonIcon" class="w-4 h-4" />
                    {{ $buttonLabel }}
                </x-button>
            </div>
            @endforeach
        </div>
        @else
        <x-empty-state
            :icon="$emptyIcon"
            title="Tidak ada supervisi"
            :description="$emptyDescription"
            :compact="true"
        />
        @endif
    </div>
</x-card>
