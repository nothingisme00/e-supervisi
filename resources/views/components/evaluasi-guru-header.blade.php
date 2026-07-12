{{--
    Header ringkas guru untuk halaman alur evaluasi (show / rubrik / feedback).

    Props:
        - supervisi (Supervisi, wajib): relasi user harus ter-load.
--}}
@props(['supervisi'])

<x-card class="mb-4 sm:mb-6 p-4 sm:p-5">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-base shadow-md ring-2 ring-primary-100 dark:ring-primary-900/50 shrink-0">
                {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
            </div>
            <div class="min-w-0">
                <h1 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">NIK {{ $supervisi->user->nik }}</p>
            </div>
        </div>
        <div class="shrink-0">
            <x-status-badge :status="$supervisi->status" />
        </div>
    </div>
</x-card>
