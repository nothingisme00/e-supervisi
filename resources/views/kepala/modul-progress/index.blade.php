@extends('layouts.modern')

@section('page-title', 'Progres Modul')

@section('content')
<div class="max-w-5xl mx-auto pb-24 md:pb-8">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Progres Baca Modul Ajar</h2>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Pantau sejauh mana setiap guru mempelajari modul. Penilaian formal menyusul di fase berikutnya.</p>

    @if ($moduls->isEmpty())
        <x-empty-state title="Belum ada modul" description="Rekap muncul setelah admin mengunggah modul ajar." />
    @else
        <div class="flex items-center gap-2 mb-4" role="tablist" aria-label="Sudut pandang rekap">
            <a href="{{ route('kepala.modul-progress.index', ['mode' => 'modul']) }}" role="tab" aria-selected="{{ $mode === 'modul' ? 'true' : 'false' }}"
               class="px-4 py-2 rounded-lg text-sm font-semibold {{ $mode === 'modul' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Per Modul</a>
            <a href="{{ route('kepala.modul-progress.index', ['mode' => 'guru']) }}" role="tab" aria-selected="{{ $mode === 'guru' ? 'true' : 'false' }}"
               class="px-4 py-2 rounded-lg text-sm font-semibold {{ $mode === 'guru' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Per Guru</a>
        </div>

        <form method="GET" action="{{ route('kepala.modul-progress.index') }}" class="flex items-center gap-2 mb-4">
            <input type="hidden" name="mode" value="{{ $mode }}">
            @if ($mode === 'modul')
                <label for="modul_id" class="text-sm text-gray-700 dark:text-gray-300">Modul:</label>
                <select id="modul_id" name="modul_id" class="flex-1 max-w-md px-3 py-1.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                    @foreach ($moduls as $modul)
                        <option value="{{ $modul->id }}" @selected($selectedModul && $selectedModul->id === $modul->id)>{{ $modul->judul }}</option>
                    @endforeach
                </select>
            @else
                <label for="guru_id" class="text-sm text-gray-700 dark:text-gray-300">Guru:</label>
                <select id="guru_id" name="guru_id" class="flex-1 max-w-md px-3 py-1.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                    @foreach ($gurus as $guru)
                        <option value="{{ $guru->id }}" @selected($selectedGuru && $selectedGuru->id === $guru->id)>{{ $guru->name }}</option>
                    @endforeach
                </select>
            @endif
            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-primary-600 hover:bg-primary-700">Tampilkan</button>
        </form>

        <x-card flush>
            <x-card-header :title="$mode === 'modul' ? ($selectedModul->judul ?? '') : ($selectedGuru->name ?? '')" />
            <div class="p-3 sm:p-4 md:p-6 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <th class="pb-2 pr-2">{{ $mode === 'modul' ? 'Guru' : 'Modul' }}</th>
                            <th class="pb-2 pr-2 w-1/3">Progres Baca</th>
                            <th class="pb-2">Terakhir Dibuka</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="py-2.5 pr-2 text-gray-900 dark:text-gray-200">{{ $row['label'] }}</td>
                                <td class="py-2.5 pr-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                            <div class="h-full rounded-full bg-primary-600 dark:bg-primary-500" style="width: {{ $row['persen'] }}%"></div>
                                        </div>
                                        <span class="w-12 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 tabular-nums">{{ $row['persen'] }}%</span>
                                    </div>
                                </td>
                                <td class="py-2.5 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $row['terakhir'] ? $row['terakhir']->translatedFormat('d F Y H:i') : 'Belum dibuka' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif
</div>
@endsection
