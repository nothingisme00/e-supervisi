@extends('layouts.modern')

@section('page-title', 'Modul Ajar')

@section('content')
<div class="max-w-6xl mx-auto pb-24 md:pb-8">
    <x-page-header title="Modul Ajar" subtitle="Pelajari modul secara mandiri. Progres baca Anda tersimpan otomatis." />

    <form method="GET" action="{{ route('guru.modul.index') }}" class="flex items-center gap-2 mb-6">
        <label for="kategori" class="text-sm text-gray-700 dark:text-gray-300">Kategori:</label>
        <select id="kategori" name="kategori"
                class="px-3 py-1.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
            <option value="">Semua</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" @selected(request('kategori') == $kategori->id)>{{ $kategori->nama }}</option>
            @endforeach
        </select>
        <x-button type="submit" size="sm">Terapkan</x-button>
    </form>

    @if ($moduls->isEmpty())
        <x-empty-state title="Belum ada modul" description="Modul ajar yang diunggah admin akan tampil di sini." />
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($moduls as $modul)
                @php
                    $progress = $progressByModul->get($modul->id);
                    $persen = $progress ? $progress->persen() : 0;
                @endphp
                <a href="{{ route('guru.modul.show', $modul->id) }}"
                   class="group block rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900">
                    <x-card flush class="h-full group-hover:border-primary-300 dark:group-hover:border-primary-700 group-hover:shadow-md transition-all">
                        <div class="aspect-[16/9] bg-gray-100 dark:bg-gray-700">
                            @if ($modul->thumbnail_url)
                                <img src="{{ $modul->thumbnail_url }}" alt="Sampul {{ $modul->judul }}"
                                     class="w-full h-full object-cover object-top" loading="lazy" decoding="async">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-icon name="book-open" class="w-10 h-10 text-gray-300 dark:text-gray-600" />
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $modul->judul }}</h2>
                            <span class="shrink-0 px-2 py-0.5 rounded-full text-xs bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400">{{ $modul->kategori->nama }}</span>
                        </div>
                        @if ($modul->deskripsi)
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $modul->deskripsi }}</p>
                        @endif
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                            <span class="tabular-nums">{{ $modul->jumlah_halaman }} halaman</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300 tabular-nums">{{ $persen }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden" role="progressbar" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100" aria-label="Progres baca {{ $modul->judul }}">
                            <div class="h-full rounded-full bg-primary-600 dark:bg-primary-500" style="width: {{ $persen }}%"></div>
                        </div>
                        </div>
                    </x-card>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
