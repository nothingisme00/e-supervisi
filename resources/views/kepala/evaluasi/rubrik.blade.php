@extends('layouts.modern')

@section('page-title', 'Rubrik Penilaian')

@section('content')
@php
    $sectionLabels = ['A' => 'Kegiatan Pendahuluan', 'B' => 'Kegiatan Inti', 'C' => 'Kegiatan Penutup'];
    $sectionKeys = array_keys($sectionLabels);
    $totalItem = $rubrikItemsBySection->flatten()->count();
@endphp
<div class="max-w-4xl mx-auto pb-24 md:pb-8">
    <div class="mb-4">
        <a href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali ke Detail Supervisi
        </a>
    </div>

    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Rubrik Penilaian Supervisi</h2>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">{{ $supervisi->user->name }} &bull; {{ optional($supervisi->tanggal_supervisi)->translatedFormat('d F Y') }}</p>

    <form method="POST" action="{{ route('kepala.evaluasi.rubrik.store', $supervisi->id) }}" id="rubrikForm">
        @csrf

        <!-- Sticky progress + step nav -->
        <div class="sticky top-16 z-10 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3 mb-6 shadow-sm">
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
                        class="rubrik-step-btn px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        {{ $i + 1 }}. {{ $sectionLabels[$key] }}
                    </button>
                @endforeach
                <button type="button" onclick="rubrikGoToStep(4)" data-rubrik-step-btn="4"
                    class="rubrik-step-btn px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    4. Ringkasan
                </button>
            </div>
        </div>

        @foreach ($sectionKeys as $i => $key)
            <div class="rubrik-step-content" data-rubrik-step="{{ $i + 1 }}">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $i + 1 }}. {{ $sectionLabels[$key] }}</h3>
                @foreach ($rubrikItemsBySection->get($key, collect())->groupBy('kelompok_nomor') as $kelompokItems)
                    <div class="mb-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-primary-700 dark:text-primary-400 border-l-4 border-primary-500 pl-2 mb-3">
                            {{ $kelompokItems->first()->kelompok_nomor }}. {{ $kelompokItems->first()->kelompok_label }}
                        </h4>
                        @foreach ($kelompokItems as $item)
                            <div class="mb-3 last:mb-0">
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">{{ $item->sub_label }}</p>
                                <div class="flex gap-2 rubrik-radio-group" data-item-id="{{ $item->id }}">
                                    @foreach ([0 => 'Tidak', 1 => 'Kurang Lengkap/Sesuai', 2 => 'Sudah Lengkap/Sesuai'] as $val => $label)
                                        <label class="flex-1">
                                            <input type="radio" name="skor[{{ $item->id }}]" value="{{ $val }}" class="sr-only peer rubrik-radio"
                                                {{ (isset($skorTersimpan[$item->id]) && (int) $skorTersimpan[$item->id] === $val) ? 'checked' : '' }}>
                                            <span class="block text-center text-xs font-medium px-2 py-2 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 cursor-pointer peer-checked:bg-primary-600 peer-checked:text-white peer-checked:border-primary-600 transition-colors">
                                                {{ $val }} &middot; {{ $label }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <div class="flex justify-end">
                    <button type="button" onclick="rubrikGoToStep({{ $i + 2 }})" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700">Lanjut</button>
                </div>
            </div>
        @endforeach

        <div class="rubrik-step-content" data-rubrik-step="4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">4. Ringkasan</h3>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Masukan terhadap Pelaksanaan Proses Pembelajaran secara umum</label>
                <textarea name="masukan_umum" rows="4" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-xl text-sm text-gray-900 dark:text-white">{{ optional($supervisi->evaluasiRubrik)->masukan_umum }}</textarea>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Pengawas Madrasah (opsional)</label>
                <input type="text" name="nama_pengawas" value="{{ optional($supervisi->evaluasiRubrik)->nama_pengawas }}" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-xl text-sm text-gray-900 dark:text-white">
            </div>
            <button type="submit" class="w-full px-5 py-3 rounded-xl text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700">Simpan Rubrik Penilaian</button>
        </div>
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
}

document.addEventListener('change', (e) => {
    if (e.target.classList.contains('rubrik-radio')) rubrikUpdateProgress();
});

rubrikGoToStep(1);
rubrikUpdateProgress();
</script>
@endsection
