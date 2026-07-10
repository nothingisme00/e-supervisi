@extends('layouts.modern')

@section('page-title', 'Rubrik Penilaian')

@section('content')
@php
    $sectionLabels = ['A' => 'Kegiatan Pendahuluan', 'B' => 'Kegiatan Inti', 'C' => 'Kegiatan Penutup'];
@endphp
<div class="max-w-5xl mx-auto pb-24 md:pb-8">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Kelola Rubrik Penilaian</h2>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Item yang dinonaktifkan tidak akan muncul di form penilaian baru, tapi tetap tampil di hasil evaluasi lama yang sudah memakainya.</p>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <x-card-header title="Ambang Predikat" />
        <div class="p-3 sm:p-4 md:p-6 space-y-3">
            @foreach ($predikatList as $predikat)
                <form method="POST" action="{{ route('admin.rubrik-items.predikat.update', $predikat->id) }}" class="flex items-center gap-3">
                    @csrf @method('PUT')
                    <span class="w-32 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $predikat->label }} ({{ $predikat->kode }})</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">&ge;</span>
                    <input type="number" name="batas_minimal" value="{{ $predikat->batas_minimal }}" min="0" max="100" step="0.01" class="w-24 px-3 py-1.5 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
                    <span class="text-xs text-gray-500 dark:text-gray-400">%</span>
                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-primary-600 hover:bg-primary-700">Simpan</button>
                </form>
            @endforeach
        </div>
    </div>

    @foreach ($sectionLabels as $key => $label)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-4">
            <x-card-header title="{{ $key }}. {{ $label }}" />
            <div class="p-3 sm:p-4 md:p-6 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <th class="pb-2 pr-2">Kode</th>
                            <th class="pb-2 pr-2">Kelompok</th>
                            <th class="pb-2 pr-2">Aspek</th>
                            <th class="pb-2 pr-2 text-center">Status</th>
                            <th class="pb-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemsBySection->get($key, collect()) as $item)
                            <tr class="border-b border-gray-100 dark:border-gray-700/50 {{ !$item->is_active ? 'opacity-50' : '' }}">
                                <td class="py-2 pr-2 font-mono text-xs">{{ $item->kode }}</td>
                                <td class="py-2 pr-2 text-gray-600 dark:text-gray-400">{{ $item->kelompok_label }}</td>
                                <td class="py-2 pr-2 text-gray-900 dark:text-gray-200">{{ $item->sub_label }}</td>
                                <td class="py-2 pr-2 text-center">
                                    <x-status-badge :status="$item->is_active ? 'aktif' : 'nonaktif'" />
                                </td>
                                <td class="py-2 text-right">
                                    <form method="POST" action="{{ route('admin.rubrik-items.toggle', $item->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                                            {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <x-card-header title="Tambah Item Baru" />
        <form method="POST" action="{{ route('admin.rubrik-items.store') }}" class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode</label>
                <input type="text" name="kode" required placeholder="mis. B.10.a" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bagian</label>
                <select name="section" required onchange="this.form.section_label.value = this.options[this.selectedIndex].dataset.label" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
                    @foreach ($sectionLabels as $key => $label)
                        <option value="{{ $key }}" data-label="{{ $label }}">{{ $key }} - {{ $label }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="section_label" value="{{ reset($sectionLabels) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Kelompok</label>
                <input type="number" name="kelompok_nomor" required min="1" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kelompok</label>
                <input type="text" name="kelompok_label" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Aspek yang Diamati</label>
                <textarea name="sub_label" required rows="2" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Urutan</label>
                <input type="number" name="urutan" required min="1" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700">Tambah Item</button>
            </div>
        </form>
    </div>
</div>
@endsection
