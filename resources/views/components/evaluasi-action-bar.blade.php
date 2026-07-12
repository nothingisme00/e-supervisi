{{--
    Bar aksi sticky bawah untuk halaman alur evaluasi.

    Props:
        - langkah (int, wajib), judul (string, wajib) — teks kiri "Langkah N · Judul".
        - slot: tombol aksi (kanan).
    bottom-20 di mobile memberi ruang bottom-nav; md:bottom-4 di desktop.
--}}
@props(['langkah', 'judul'])

<div class="sticky bottom-20 md:bottom-4 z-20 mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 shadow-[0_-6px_16px_-8px_rgba(15,23,42,0.2),0_4px_12px_-6px_rgba(15,23,42,0.15)] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Langkah {{ $langkah }} · {{ $judul }}</p>
    <div class="flex items-center justify-end gap-2 sm:gap-3">
        {{ $slot }}
    </div>
</div>
