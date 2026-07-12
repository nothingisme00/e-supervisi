@extends('layouts.modern')

@section('page-title', $modul->judul)

@section('content')
<div class="max-w-4xl mx-auto pb-24 md:pb-8">
    <div class="flex items-center justify-between gap-3 mb-4">
        <div class="min-w-0">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white truncate">{{ $modul->judul }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $modul->kategori->nama }} • {{ $modul->jumlah_halaman }} halaman</p>
        </div>
        <a href="{{ route('guru.modul.index') }}" wire:navigate class="shrink-0 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline">&larr; Kembali</a>
    </div>

    @if ($fileMissing)
        <x-card flush>
            <x-empty-state
                icon="document"
                title="File modul tidak ditemukan"
                description="Hubungi admin untuk mengunggah ulang file modul ini."
                :compact="true"
            />
        </x-card>
    @else
        <div id="modul-reader"
             data-pdf-url="{{ route('guru.modul.file', $modul->id) }}"
             data-progress-url="{{ route('guru.modul.progress', $modul->id) }}"
             data-halaman-terjauh="{{ $progress->halaman_terjauh }}"
             data-jumlah-halaman="{{ $modul->jumlah_halaman }}"
             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="flex items-center justify-between gap-2 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <button id="pdf-prev" type="button" disabled aria-label="Halaman sebelumnya"
                        class="min-w-11 min-h-11 px-3 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">&larr;</button>
                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input id="pdf-page-input" type="number" min="1" max="{{ $modul->jumlah_halaman }}" value="{{ $progress->halaman_terjauh }}" aria-label="Loncat ke halaman"
                           class="w-16 px-2 py-1 text-center border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
                    <span id="page-info" aria-live="polite">dari {{ $modul->jumlah_halaman }}</span>
                    <span id="progress-saved" class="hidden inline-flex items-center gap-1 text-xs font-medium text-emerald-600 dark:text-emerald-400" aria-live="polite">
                        <x-icon name="check" class="w-3.5 h-3.5" />
                        Progres tersimpan
                    </span>
                </div>
                <button id="pdf-next" type="button" disabled aria-label="Halaman berikutnya"
                        class="min-w-11 min-h-11 px-3 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">&rarr;</button>
            </div>
            <div class="bg-gray-100 dark:bg-gray-900 p-2 sm:p-4">
                <div id="pdf-skeleton" class="animate-pulse bg-gray-200 dark:bg-gray-700 rounded w-full" style="aspect-ratio: 1 / 1.414;"></div>
                <canvas id="pdf-canvas" class="w-full h-auto mx-auto hidden rounded shadow"></canvas>
            </div>
        </div>
    @endif

    @if ($modul->videos->isNotEmpty())
        <x-card flush class="mt-6">
            <x-card-header title="Video Pembelajaran" />
            <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($modul->videos as $video)
                    @if ($video->youtube_embed_url)
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-200 mb-2">{{ $video->judul }}</p>
                            <div class="rounded-lg overflow-hidden" style="aspect-ratio: 16 / 9;">
                                <iframe src="{{ $video->youtube_embed_url }}" title="{{ $video->judul }}" class="w-full h-full" frameborder="0" allowfullscreen loading="lazy"></iframe>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </x-card>
    @endif
</div>

@if (! $fileMissing)
    @vite('resources/js/modul-reader.js')
@endif
@endsection
