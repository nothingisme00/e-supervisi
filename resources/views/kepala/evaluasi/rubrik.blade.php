@extends('layouts.modern')

@section('page-title', 'Rubrik Penilaian')

@section('content')
@php
    $sectionLabels = ['A' => 'Kegiatan Pendahuluan', 'B' => 'Kegiatan Inti', 'C' => 'Kegiatan Penutup'];
    $sectionKeys = array_keys($sectionLabels);
    $totalItem = $rubrikItemsBySection->flatten()->count();
@endphp
<div class="max-w-4xl mx-auto pb-24 md:pb-8">
    <x-page-header
        title="Rubrik Penilaian Supervisi"
        subtitle="{{ $supervisi->user->name }} • {{ optional($supervisi->tanggal_supervisi)->translatedFormat('d F Y') }}"
        :back-url="route('kepala.evaluasi.show', $supervisi->id)" />

    <x-evaluasi-guru-header :supervisi="$supervisi" />
    <x-evaluasi-stepper :supervisi="$supervisi" :aktif="2" />

    <form method="POST" action="{{ route('kepala.evaluasi.rubrik.store', $supervisi->id) }}" id="rubrikForm">
        @csrf

        <!-- Sticky progress + step nav -->
        <div class="sticky top-[72px] sm:top-[88px] md:top-[72px] lg:top-[84px] z-10 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3 mb-6 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <span id="rubrikProgressCount">0</span>/{{ $totalItem }} terisi
                </span>
                <span class="text-xs text-gray-400" id="rubrikProgressPreview"></span>
            </div>
            <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden mb-3">
                <div id="rubrikProgressBar" class="h-full bg-primary-600 transition-all duration-300" style="width: 0%"></div>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach ($sectionKeys as $i => $key)
                    <button type="button" onclick="rubrikGoToStep({{ $i + 1 }})" data-rubrik-step-btn="{{ $i + 1 }}"
                        class="rubrik-step-btn px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                        {{ $i + 1 }}. {{ $sectionLabels[$key] }}
                        <span class="rubrik-step-count font-normal opacity-75 tabular-nums" data-rubrik-step-count="{{ $i + 1 }}"></span>
                    </button>
                @endforeach
                <button type="button" onclick="rubrikGoToStep(4)" data-rubrik-step-btn="4"
                    class="rubrik-step-btn px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                    4. Ringkasan
                </button>
            </div>
        </div>

        @foreach ($sectionKeys as $i => $key)
            <div class="rubrik-step-content" data-rubrik-step="{{ $i + 1 }}">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $i + 1 }}. {{ $sectionLabels[$key] }}</h2>
                @foreach ($rubrikItemsBySection->get($key, collect())->groupBy('kelompok_nomor') as $kelompokItems)
                    <x-card flush class="mb-5">
                        <x-card-header title="{{ $kelompokItems->first()->kelompok_nomor }}. {{ $kelompokItems->first()->kelompok_label }}" />
                        <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach ($kelompokItems as $item)
                                <div class="px-4 py-4 sm:px-5">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">{{ $item->sub_label }}</p>
                                    <div class="grid grid-cols-3 rounded-lg border border-gray-200 dark:border-gray-600 divide-x divide-gray-200 dark:divide-gray-600 overflow-hidden rubrik-radio-group" data-item-id="{{ $item->id }}">
                                        @foreach ([0 => 'Tidak', 1 => 'Kurang Lengkap / Sesuai', 2 => 'Sudah Lengkap / Sesuai'] as $val => $label)
                                            <label class="block" title="{{ $val }} · {{ $label }}">
                                                <input type="radio" name="skor[{{ $item->id }}]" value="{{ $val }}" class="sr-only peer rubrik-radio"
                                                    {{ (isset($skorTersimpan[$item->id]) && (int) $skorTersimpan[$item->id] === $val) ? 'checked' : '' }}>
                                                <span class="flex flex-col sm:flex-row items-center justify-center gap-0.5 sm:gap-1.5 text-center min-h-[44px] px-2 py-2 text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 peer-checked:bg-primary-600 peer-checked:text-white transition-colors peer-focus-visible:ring-2 peer-focus-visible:ring-inset peer-focus-visible:ring-primary-500">
                                                    <span class="text-sm font-bold tabular-nums">{{ $val }}</span>
                                                    <span class="text-xs font-medium leading-tight">{{ $label }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                @endforeach
                <div class="flex justify-end">
                    <x-button type="button" onclick="rubrikGoToStep({{ $i + 2 }})">
                        Lanjut
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </x-button>
                </div>
            </div>
        @endforeach

        <div class="rubrik-step-content" data-rubrik-step="4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">4. Ringkasan</h2>
            <x-card class="p-4 sm:p-5 mb-4">
                <x-form.field label="Masukan terhadap Pelaksanaan Proses Pembelajaran secara umum" name="masukan_umum">
                    <textarea name="masukan_umum" id="masukan_umum" rows="4" class="form-control resize-none">{{ optional($supervisi->evaluasiRubrik)->masukan_umum }}</textarea>
                </x-form.field>
            </x-card>
            <x-card class="p-4 sm:p-5 mb-4">
                <x-form.input
                    name="nama_pengawas"
                    label="Nama Pengawas Madrasah (opsional)"
                    value="{{ optional($supervisi->evaluasiRubrik)->nama_pengawas }}" />
            </x-card>
        </div>

        <x-evaluasi-action-bar :langkah="2" judul="Isi Rubrik Penilaian">
            <x-button href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" variant="secondary">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Kembali
            </x-button>
            <x-button type="submit" variant="secondary">Simpan Draf</x-button>
            <x-button type="submit" id="btnLanjutFeedback" name="lanjut" value="1" disabled>
                Simpan &amp; Lanjut Feedback
                <x-icon name="arrow-right" class="w-4 h-4" />
            </x-button>
        </x-evaluasi-action-bar>
    </form>
</div>

<script>
let rubrikCurrentStep = 1;

function rubrikGoToStep(step) {
    if (step < 1 || step > 4) return;
    rubrikCurrentStep = step;
    document.querySelectorAll('.rubrik-step-content').forEach(el => {
        el.style.display = (parseInt(el.dataset.rubrikStep) === step) ? 'block' : 'none';
    });
    document.querySelectorAll('.rubrik-step-btn').forEach(btn => {
        const active = parseInt(btn.dataset.rubrikStepBtn) === step;
        btn.classList.toggle('bg-primary-600', active);
        btn.classList.toggle('text-white', active);
        btn.classList.toggle('border-primary-600', active);
    });
}

function rubrikUpdateProgress() {
    const groups = document.querySelectorAll('.rubrik-radio-group');
    let filled = 0;
    groups.forEach(g => { if (g.querySelector('.rubrik-radio:checked')) filled++; });
    document.getElementById('rubrikProgressCount').textContent = filled;
    document.getElementById('rubrikProgressBar').style.width = (filled / groups.length * 100) + '%';

    const btnLanjut = document.getElementById('btnLanjutFeedback');
    if (btnLanjut) btnLanjut.disabled = filled < groups.length;

    // Hitungan terisi per bagian di tombol step nav
    document.querySelectorAll('.rubrik-step-content').forEach(section => {
        const step = section.dataset.rubrikStep;
        const badge = document.querySelector('[data-rubrik-step-count="' + step + '"]');
        if (!badge) return;
        const sectionGroups = section.querySelectorAll('.rubrik-radio-group');
        if (!sectionGroups.length) return;
        let sectionFilled = 0;
        sectionGroups.forEach(g => { if (g.querySelector('.rubrik-radio:checked')) sectionFilled++; });
        badge.textContent = '(' + sectionFilled + '/' + sectionGroups.length + ')';
    });
}

document.addEventListener('change', (e) => {
    if (e.target.classList.contains('rubrik-radio')) rubrikUpdateProgress();
});

rubrikGoToStep(1);
rubrikUpdateProgress();
</script>
@endsection
