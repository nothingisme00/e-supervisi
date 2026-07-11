{{--
    Kartu baku (border + shadow-sm, tanpa gradient/ornamen).

    Pemakaian:
        <x-card>Konten kartu</x-card>
        <x-card flush class="mb-6">
            <x-card-header title="Riwayat" />
            <div class="p-4">...</div>
        </x-card>

    Props:
        - flush (bool, default false): tambah overflow-hidden — dipakai saat kartu
          berisi elemen yang harus terpotong rapi di sudut membulat (mis. table, header solid).
--}}
@props([
    'flush' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm'.($flush ? ' overflow-hidden' : '')]) }}>
    {{ $slot }}
</div>
