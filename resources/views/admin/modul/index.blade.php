@extends('layouts.modern')

@section('page-title', 'Modul Ajar')

@section('content')
<div class="max-w-5xl mx-auto pb-24 md:pb-8">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Kelola Modul Ajar</h2>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Modul yang dinonaktifkan tidak tampil di daftar guru, tapi progres baca yang sudah ada tetap tersimpan.</p>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-sm text-green-700 dark:text-green-300">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-sm text-red-700 dark:text-red-300">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Kelola Kategori --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <x-card-header title="Kategori Modul" />
        <div class="p-3 sm:p-4 md:p-6 space-y-3">
            <form method="POST" action="{{ route('admin.modul.kategori.store') }}" class="flex items-center gap-3">
                @csrf
                <input type="text" name="nama" required maxlength="255" placeholder="Nama kategori baru"
                       class="flex-1 px-3 py-1.5 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-primary-600 hover:bg-primary-700">Tambah</button>
            </form>
            <div class="flex flex-wrap gap-2">
                @forelse ($kategoris as $kategori)
                    <form method="POST" action="{{ route('admin.modul.kategori.toggle', $kategori->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-600 text-xs {{ $kategori->is_active ? 'text-gray-700 dark:text-gray-300' : 'opacity-50 text-gray-500 dark:text-gray-400' }}">
                        @csrf @method('PATCH')
                        <span>{{ $kategori->nama }}</span>
                        <button type="submit" class="font-semibold text-primary-600 dark:text-primary-400 hover:underline">{{ $kategori->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                    </form>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada kategori. Tambahkan minimal satu sebelum mengunggah modul.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tambah Modul --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <x-card-header title="Tambah Modul Baru" />
        <form method="POST" action="{{ route('admin.modul.store') }}" enctype="multipart/form-data" class="p-3 sm:p-4 md:p-6 space-y-4">
            @csrf
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" id="judul" name="judul" required maxlength="255" value="{{ old('judul') }}"
                       class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
            </div>
            <div>
                <label for="modul_kategori_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select id="modul_kategori_id" name="modul_kategori_id" required
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                    <option value="">Pilih kategori</option>
                    @foreach ($kategoris->where('is_active', true) as $kategori)
                        <option value="{{ $kategori->id }}" @selected(old('modul_kategori_id') == $kategori->id)>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="2" maxlength="2000"
                          class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">{{ old('deskripsi') }}</textarea>
            </div>
            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File PDF <span class="text-red-500">*</span></label>
                <input type="file" id="file" name="file" accept="application/pdf" required class="w-full text-sm text-gray-700 dark:text-gray-300">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hanya PDF, maksimal 20 MB. Jumlah halaman dihitung otomatis.</p>
            </div>
            <fieldset class="space-y-2">
                <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">Video YouTube (opsional, kosongkan jika tidak ada)</legend>
                @for ($i = 0; $i < 2; $i++)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input type="text" name="videos[{{ $i }}][judul]" maxlength="255" placeholder="Judul video {{ $i + 1 }}" value="{{ old("videos.$i.judul") }}"
                               class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                        <input type="url" name="videos[{{ $i }}][youtube_url]" placeholder="https://www.youtube.com/watch?v=..." value="{{ old("videos.$i.youtube_url") }}"
                               class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                    </div>
                @endfor
            </fieldset>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700">Unggah Modul</button>
        </form>
    </div>

    {{-- Daftar Modul --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <x-card-header title="Daftar Modul" />
        <div class="p-3 sm:p-4 md:p-6 space-y-3">
            @forelse ($moduls as $modul)
                <details class="border border-gray-100 dark:border-gray-700/50 rounded-lg {{ ! $modul->is_active ? 'opacity-50' : '' }}">
                    <summary class="flex items-center justify-between gap-3 px-4 py-3 cursor-pointer select-none">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-200 truncate">{{ $modul->judul }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $modul->kategori->nama }} • {{ $modul->jumlah_halaman }} halaman • {{ $modul->videos->count() }} video</p>
                        </div>
                        <x-status-badge :status="$modul->is_active ? 'aktif' : 'nonaktif'" />
                    </summary>
                    <div class="px-4 pb-4 border-t border-gray-100 dark:border-gray-700/50 pt-3 space-y-4">
                        <form method="POST" action="{{ route('admin.modul.update', $modul->id) }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input type="text" name="judul" required maxlength="255" value="{{ $modul->judul }}"
                                       class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                <select name="modul_kategori_id" required
                                        class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" @selected($modul->modul_kategori_id === $kategori->id)>{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <textarea name="deskripsi" rows="2" maxlength="2000"
                                      class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">{{ $modul->deskripsi }}</textarea>
                            <div>
                                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Ganti PDF (kosongkan bila tidak diganti — progres guru tetap tersimpan)</label>
                                <input type="file" name="file" accept="application/pdf" class="w-full text-sm text-gray-700 dark:text-gray-300">
                            </div>
                            @foreach ($modul->videos as $i => $video)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <input type="text" name="videos[{{ $i }}][judul]" maxlength="255" value="{{ $video->judul }}"
                                           class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                    <input type="url" name="videos[{{ $i }}][youtube_url]" value="{{ $video->youtube_url }}"
                                           class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                </div>
                            @endforeach
                            @php $next = $modul->videos->count(); @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input type="text" name="videos[{{ $next }}][judul]" maxlength="255" placeholder="Judul video baru"
                                       class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                <input type="url" name="videos[{{ $next }}][youtube_url]" placeholder="https://www.youtube.com/watch?v=..."
                                       class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                            </div>
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-primary-600 hover:bg-primary-700">Simpan Perubahan</button>
                        </form>
                        <form method="POST" action="{{ route('admin.modul.toggle', $modul->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                                {{ $modul->is_active ? 'Nonaktifkan Modul' : 'Aktifkan Modul' }}
                            </button>
                        </form>
                    </div>
                </details>
            @empty
                <x-empty-state title="Belum ada modul" description="Unggah modul pertama lewat formulir di atas." />
            @endforelse
        </div>
    </div>
</div>
@endsection
