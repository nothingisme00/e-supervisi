{{--
    Header halaman baku: judul + subjudul opsional + link kembali opsional +
    slot "actions" rata kanan (tombol-tombol aksi halaman).

    Pemakaian:
        <x-page-header title="Daftar Pengguna" subtitle="Kelola akun guru & kepala sekolah" :back-url="route('admin.dashboard')">
            <x-slot:actions>
                <x-button href="{{ route('admin.users.create') }}">Tambah</x-button>
            </x-slot:actions>
        </x-page-header>

    Props:
        - title (string, wajib)
        - subtitle (string, opsional)
        - backUrl (string, opsional): jika diisi, render link "Kembali" di atas judul.
        - slot actions (opsional): area rata kanan untuk tombol/aksi halaman.
--}}
@props([
    'title',
    'subtitle' => null,
    'backUrl' => null,
])

<div class="mb-6 flex flex-wrap items-start justify-between gap-3">
    <div>
        @if($backUrl)
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 mb-2 rounded transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Kembali
            </a>
        @endif

        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $title }}</h1>

        @if($subtitle)
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>
