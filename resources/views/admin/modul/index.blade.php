@extends('layouts.modern')

@section('page-title', 'Modul Ajar')

@section('content')
<div class="max-w-5xl mx-auto pb-24 md:pb-8">
    <x-page-header title="Kelola Modul Ajar" subtitle="Modul yang dinonaktifkan tidak tampil di daftar guru, tapi progres baca yang sudah ada tetap tersimpan." />

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-sm text-emerald-700 dark:text-emerald-300">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-sm text-red-700 dark:text-red-300">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Kelola Kategori --}}
    <x-card flush class="mb-6">
        <x-card-header title="Kategori Modul" />
        <div class="p-3 sm:p-4 md:p-6 space-y-3">
            <form method="POST" action="{{ route('admin.modul.kategori.store') }}" class="flex items-center gap-3">
                @csrf
                <input type="text" name="nama" required maxlength="255" placeholder="Nama kategori baru" class="form-control flex-1">
                <x-button type="submit" size="sm">Tambah</x-button>
            </form>
            <div class="flex flex-wrap gap-2">
                @forelse ($kategoris as $kategori)
                    <form method="POST" action="{{ route('admin.modul.kategori.toggle', $kategori->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-600 text-xs {{ $kategori->is_active ? 'text-gray-700 dark:text-gray-300' : 'opacity-50 text-gray-500 dark:text-gray-400' }}">
                        @csrf @method('PATCH')
                        <span>{{ $kategori->nama }}</span>
                        <button type="submit" class="font-semibold text-primary-600 dark:text-primary-400 hover:underline cursor-pointer">{{ $kategori->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                    </form>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada kategori. Tambahkan minimal satu sebelum mengunggah modul.</p>
                @endforelse
            </div>
        </div>
    </x-card>

    {{-- Tambah Modul --}}
    <x-card flush class="mb-6">
        <x-card-header title="Tambah Modul Baru" />
        <form method="POST" action="{{ route('admin.modul.store') }}" enctype="multipart/form-data" class="p-3 sm:p-4 md:p-6 space-y-4">
            @csrf
            <x-form.input
                name="judul"
                label="Judul *"
                required
                maxlength="255"
                value="{{ old('judul') }}" />
            <x-form.select name="modul_kategori_id" label="Kategori *" required>
                <option value="">Pilih kategori</option>
                @foreach ($kategoris->where('is_active', true) as $kategori)
                    <option value="{{ $kategori->id }}" @selected(old('modul_kategori_id') == $kategori->id)>{{ $kategori->nama }}</option>
                @endforeach
            </x-form.select>
            <x-form.textarea name="deskripsi" label="Deskripsi" rows="2" maxlength="2000">{{ old('deskripsi') }}</x-form.textarea>
            <x-form.field label="File PDF *" name="file" hint="Hanya PDF, maksimal 20 MB. Jumlah halaman & sampul dibuat otomatis dari halaman 1.">
                <input type="file" id="file" name="file" accept="application/pdf" required data-thumbnail-source class="w-full text-sm text-gray-700 dark:text-gray-300">
                <input type="file" name="thumbnail" accept="image/*" data-thumbnail-target class="hidden" tabindex="-1" aria-hidden="true">
            </x-form.field>
            <fieldset class="space-y-2">
                <legend class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Video YouTube (opsional, kosongkan jika tidak ada)</legend>
                @for ($i = 0; $i < 2; $i++)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input type="text" name="videos[{{ $i }}][judul]" maxlength="255" placeholder="Judul video {{ $i + 1 }}" value="{{ old("videos.$i.judul") }}" class="form-control">
                        <input type="url" name="videos[{{ $i }}][youtube_url]" placeholder="https://www.youtube.com/watch?v=..." value="{{ old("videos.$i.youtube_url") }}" class="form-control">
                    </div>
                @endfor
            </fieldset>
            <x-button type="submit">Unggah Modul</x-button>
        </form>
    </x-card>

    {{-- Daftar Modul --}}
    <x-card flush>
        <x-card-header title="Daftar Modul" />
        <div class="p-3 sm:p-4 md:p-6 space-y-3">
            @forelse ($moduls as $modul)
                <div x-data="{ open: false }" class="border border-gray-100 dark:border-gray-700/50 rounded-lg {{ ! $modul->is_active ? 'opacity-50' : '' }}">
                    <button type="button" @click="open = !open" :aria-expanded="open"
                            class="w-full flex items-center gap-3 px-4 py-3 min-h-[44px] cursor-pointer select-none text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 rounded-lg">
                        <div class="w-9 h-12 shrink-0 rounded overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            @if ($modul->thumbnail_url)
                                <img src="{{ $modul->thumbnail_url }}" alt="" class="w-full h-full object-cover" loading="lazy" decoding="async">
                            @else
                                <x-icon name="document" class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-200 truncate">{{ $modul->judul }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $modul->kategori->nama }} • {{ $modul->jumlah_halaman }} halaman • {{ $modul->videos->count() }} video</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <x-status-badge :status="$modul->is_active ? 'aktif' : 'nonaktif'" />
                            <x-icon name="chevron-down" ::class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200" />
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <div class="px-4 pb-4 border-t border-gray-100 dark:border-gray-700/50 pt-3 space-y-4">
                            <form method="POST" action="{{ route('admin.modul.update', $modul->id) }}" enctype="multipart/form-data" class="space-y-3">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <input type="text" name="judul" required maxlength="255" value="{{ $modul->judul }}" class="form-control">
                                    <select name="modul_kategori_id" required class="form-control">
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" @selected($modul->modul_kategori_id === $kategori->id)>{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <textarea name="deskripsi" rows="2" maxlength="2000" class="form-control">{{ $modul->deskripsi }}</textarea>
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Ganti PDF (kosongkan bila tidak diganti — progres guru tetap tersimpan). Sampul ikut diperbarui dari halaman 1.</label>
                                    <input type="file" name="file" accept="application/pdf" data-thumbnail-source class="w-full text-sm text-gray-700 dark:text-gray-300">
                                    <input type="file" name="thumbnail" accept="image/*" data-thumbnail-target class="hidden" tabindex="-1" aria-hidden="true">
                                </div>
                                @foreach ($modul->videos as $i => $video)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <input type="text" name="videos[{{ $i }}][judul]" maxlength="255" value="{{ $video->judul }}" class="form-control">
                                        <input type="url" name="videos[{{ $i }}][youtube_url]" value="{{ $video->youtube_url }}" class="form-control">
                                    </div>
                                @endforeach
                                @php $next = $modul->videos->count(); @endphp
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <input type="text" name="videos[{{ $next }}][judul]" maxlength="255" placeholder="Judul video baru" class="form-control">
                                    <input type="url" name="videos[{{ $next }}][youtube_url]" placeholder="https://www.youtube.com/watch?v=..." class="form-control">
                                </div>
                                <x-button type="submit" size="sm">Simpan Perubahan</x-button>
                            </form>
                            <form method="POST" action="{{ route('admin.modul.toggle', $modul->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline cursor-pointer">
                                    {{ $modul->is_active ? 'Nonaktifkan Modul' : 'Aktifkan Modul' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <x-empty-state title="Belum ada modul" description="Unggah modul pertama lewat formulir di atas." />
            @endforelse
        </div>
    </x-card>
</div>

@vite('resources/js/modul-thumbnail.js')
@endsection
