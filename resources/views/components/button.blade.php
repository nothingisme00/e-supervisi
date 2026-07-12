{{--
    Tombol baku (render <a> jika ada href, selain itu <button>).

    Pemakaian:
        <x-button>Simpan</x-button>
        <x-button variant="secondary" size="sm">Batal</x-button>
        <x-button variant="danger" type="submit">Hapus</x-button>
        <x-button href="{{ route('admin.users.index') }}" variant="ghost">Kembali</x-button>

    Props:
        - variant (primary|secondary|danger|ghost, default primary)
        - size (sm|md, default md)
        - href (string, opsional): jika diisi, render <a>. Selain itu <button type="button">
          (override via atribut type, mis. type="submit").
--}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 disabled:opacity-50 disabled:pointer-events-none';

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2.5 text-sm min-h-[44px]',
    ];

    $variants = [
        'primary' => 'bg-primary-600 hover:bg-primary-700 text-white',
        'secondary' => 'border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'ghost' => 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700',
    ];

    $classes = $base.' '.($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>{{ $slot }}</button>
@endif
