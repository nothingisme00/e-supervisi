{{--
    Chip ikon notifikasi per jenis — dipakai notifikasi/_item (halaman + dropdown topbar).

    Props:
        - ikon (string): kunci jenis dari payload notifikasi ($n->data['ikon']).
          Payload lama bisa tanpa kunci → pemanggil wajib fallback 'default'.

    Peta warna bermakna: modul=primary, feedback=biru, revisi=merah,
    nilai=emerald, review/pengingat=amber, default=abu-abu.
--}}
@props(['ikon' => 'default'])

@php
    $map = [
        'modul' => ['book-open', 'bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400'],
        'feedback' => ['chat-bubble', 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'],
        'revisi' => ['exclamation-triangle', 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'],
        'nilai' => ['star', 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400'],
        'review' => ['clipboard-check', 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400'],
        'pengingat' => ['clock', 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400'],
    ];
    [$namaIkon, $warna] = $map[$ikon] ?? ['bell', 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'];
@endphp

<span class="w-9 h-9 rounded-full {{ $warna }} flex items-center justify-center">
    <x-icon :name="$namaIkon" class="w-4 h-4" />
</span>
