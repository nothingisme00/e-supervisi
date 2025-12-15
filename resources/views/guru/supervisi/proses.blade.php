@extends('layouts.modern')

@section('page-title', 'Proses Pembelajaran')

@section('content')
<!-- Breadcrumb -->
<div class="mb-2 sm:mb-4">
    <x-breadcrumb :items="[
        ['label' => 'Beranda', 'url' => route('guru.home')],
        ['label' => 'Supervisi'],
        ['label' => 'Proses Pembelajaran', 'icon' => true]
    ]" />
</div>

<!-- Wrapper Container (3/4 width, centered) -->
<div class="w-full lg:w-3/4 mx-auto space-y-3 sm:space-y-6 px-0 sm:px-4 pb-24 md:pb-0">

<!-- Form -->
<form id="prosesForm">
    @csrf

    <!-- Link Pembelajaran Card -->
    <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden mb-3 sm:mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700 px-3 py-2.5 sm:px-6 sm:py-4 bg-gradient-to-r from-indigo-50/30 to-blue-50/30 dark:from-indigo-900/10 dark:to-blue-900/10">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-600 dark:bg-indigo-500 rounded-md sm:rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm sm:text-lg font-bold text-gray-800 dark:text-gray-100">Link Pembelajaran</h2>
                    <p class="text-[10px] sm:text-sm text-gray-600 dark:text-gray-400">Masukkan link video dan meeting</p>
                </div>
            </div>
        </div>

        <div class="p-3 sm:p-6">
            <div class="space-y-3 sm:space-y-6">
                <!-- Link Video -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg sm:rounded-xl p-3 sm:p-5 border-2 border-gray-200 dark:border-gray-600">
                    <label for="link_video" class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 sm:mb-3">
                        <span class="flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] sm:text-xs font-bold">1</span>
                        Link Video <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <input
                            type="url"
                            name="link_video"
                            id="link_video"
                            required
                            placeholder="https://youtube.com/... atau https://drive.google.com/..."
                            value="{{ old('link_video', $proses->link_video ?? '') }}"
                            class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2 sm:py-3 text-xs sm:text-sm border-2 border-gray-300 dark:border-gray-600 rounded-md sm:rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-white transition-all"
                        >
                    </div>
                    <p class="text-[10px] sm:text-xs text-gray-600 dark:text-gray-400 mt-1.5 sm:mt-2 flex items-center gap-1 sm:gap-1.5">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Link YouTube atau Google Drive
                    </p>
                </div>

                <!-- Link Meeting -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg sm:rounded-xl p-3 sm:p-5 border-2 border-gray-200 dark:border-gray-600">
                    <label for="link_meeting" class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 sm:mb-3">
                        <span class="flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] sm:text-xs font-bold">2</span>
                        Link Meeting <span class="text-gray-500 text-[9px] sm:text-xs">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input
                            type="url"
                            name="link_meeting"
                            id="link_meeting"
                            placeholder="https://meet.google.com/... atau https://zoom.us/..."
                            value="{{ old('link_meeting', $proses->link_meeting ?? '') }}"
                            class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2 sm:py-3 text-xs sm:text-sm border-2 border-gray-300 dark:border-gray-600 rounded-md sm:rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-white transition-all"
                        >
                    </div>
                    <p class="text-[10px] sm:text-xs text-gray-600 dark:text-gray-400 mt-1.5 sm:mt-2 flex items-center gap-1 sm:gap-1.5">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Link Google Meet atau Zoom (Opsional)
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Refleksi Pembelajaran Card -->
    <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-700 px-3 py-2.5 sm:px-6 sm:py-4 bg-gradient-to-r from-purple-50/30 to-pink-50/30 dark:from-purple-900/10 dark:to-pink-900/10">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-600 dark:bg-purple-500 rounded-md sm:rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm sm:text-lg font-bold text-gray-800 dark:text-gray-100">Refleksi Pembelajaran</h2>
                    <p class="text-[10px] sm:text-sm text-gray-600 dark:text-gray-400">Jawab pertanyaan refleksi berikut</p>
                </div>
            </div>
        </div>

        <div class="p-3 sm:p-6 space-y-3 sm:space-y-5">
            @foreach($refleksiQuestions as $field => $question)
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg sm:rounded-xl p-3 sm:p-5 border-2 border-gray-200 dark:border-gray-600">
                    <label for="{{ $field }}" class="flex items-start gap-2 sm:gap-3 text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 sm:mb-3">
                        <span class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 bg-purple-600 dark:bg-purple-500 text-white rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold shrink-0 shadow-md">{{ $loop->iteration }}</span>
                        <span class="flex-1 leading-relaxed pt-0.5 sm:pt-1">{{ $question }} <span class="text-red-500">*</span></span>
                    </label>
                    <div class="space-y-1.5 sm:space-y-2">
                        <textarea
                            name="{{ $field }}"
                            id="{{ $field }}"
                            rows="4"
                            required
                            maxlength="500"
                            placeholder="Tulis jawaban Anda di sini (minimal 10 karakter)..."
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm border-2 border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white transition-all resize-none"
                            style="resize: none;"
                            oninput="updateCharCount('{{ $field }}')"
                        >{{ old($field, $proses->$field ?? '') }}</textarea>
                        <div class="flex justify-between items-center px-0.5 sm:px-1">
                            <p class="text-[10px] sm:text-xs text-gray-600 dark:text-gray-400 flex items-center gap-1 sm:gap-1.5 font-medium">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Min. 10 karakter
                            </p>
                            <div class="flex items-center gap-1 sm:gap-1.5 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg">
                                <span class="text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-300" id="{{ $field }}_count">0</span>
                                <span class="text-[10px] sm:text-xs text-gray-600 dark:text-gray-400">/</span>
                                <span class="text-[10px] sm:text-xs font-semibold text-gray-600 dark:text-gray-400">500</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Info Alert - Moved Inside -->
            <div class="bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 border-l-4 border-amber-500 dark:border-amber-400 rounded-lg sm:rounded-xl p-3 sm:p-5">
                <div class="flex gap-2 sm:gap-4">
                    <div class="shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-amber-500 dark:bg-amber-600 rounded-md sm:rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xs sm:text-sm font-bold text-amber-900 dark:text-amber-200 mb-1 sm:mb-2">Perhatian!</h4>
                        <ul class="text-[10px] sm:text-sm text-amber-800 dark:text-amber-300 space-y-1 sm:space-y-1.5 list-disc list-inside">
                            <li>Pastikan data sudah benar sebelum submit</li>
                            <li>Data otomatis tersimpan tiap 30 detik</li>
                            <li>Setelah submit akan direview</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons - Moved Inside -->
            <div class="pt-1 sm:pt-2">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 sm:gap-4">
                    <button
                        type="button"
                        onclick="window.location.href='{{ route('guru.supervisi.evaluasi', $supervisi->id) }}'"
                        class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-6 sm:py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </button>

                    <div class="flex flex-col sm:flex-row items-stretch gap-2 sm:gap-3">
                        <button
                            type="button"
                            id="saveButton"
                            class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan
                        </button>

                        <button
                            type="button"
                            id="submitButton"
                            disabled
                            onclick="confirmSubmit()"
                            class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-6 sm:py-3 text-xs sm:text-sm font-medium rounded-md sm:rounded-lg disabled:cursor-not-allowed"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


</div>
<!-- End Wrapper Container -->

<!-- Confirm Submit Modal -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div id="confirmModalContent" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-sm w-full transform transition-all duration-200 scale-95 opacity-0">
        <!-- Content -->
        <div class="p-6 text-center">
            <!-- Icon -->
            <div class="w-14 h-14 rounded-full flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/30 mx-auto mb-4">
                <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                Sudah yakin untuk submit?
            </h3>

            <!-- Description -->
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                Data akan direview Kepala Sekolah dan tidak bisa diedit kecuali diminta revisi.
            </p>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button onclick="closeConfirmModal()" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors">
                    Batal
                </button>
                <button onclick="proceedSubmit()" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition-colors">
                    Ya, Submit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<!-- Success Submit Modal -->
<div id="successModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[70] items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
        <!-- Icon & Content -->
        <div class="p-6 text-center">
            <!-- Success Icon -->
            <div class="w-14 h-14 rounded-full flex items-center justify-center bg-green-100 dark:bg-green-900/30 mx-auto mb-4">
                <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                Supervisi Berhasil Disubmit!
            </h3>

            <!-- Description -->
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                Data akan direview oleh Kepala Sekolah. Anda akan diberitahu jika ada revisi.
            </p>

            <!-- Button -->
            <button
                onclick="window.location.href='{{ route('guru.home') }}'"
                class="w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition-colors">
                Kembali ke Beranda
            </button>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div id="successToast" class="hidden fixed top-4 right-4 z-50 animate-slide-in">
    <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-5 py-3.5 rounded-lg shadow-xl flex items-center gap-3 min-w-[300px]">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <span class="font-semibold">Data berhasil disimpan!</span>
    </div>
</div>

<!-- Error Toast -->
<div id="errorToast" class="hidden fixed top-4 right-4 z-50 animate-slide-in">
    <div class="bg-gradient-to-r from-red-500 to-rose-500 text-white px-5 py-3.5 rounded-lg shadow-xl flex items-center gap-3 min-w-[300px]">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <span id="errorMessage" class="font-semibold">Terjadi kesalahan!</span>
    </div>
</div>

<script>
const supervisiId = {{ $supervisi->id }};

// Validate form and enable/disable submit button
function validateForm() {
    const linkVideo = document.getElementById('link_video').value.trim();
    // Link Meeting is optional, no need to validate

    // Check all refleksi fields
    const refleksiFields = [@foreach($refleksiQuestions as $field => $question)'{{ $field }}'{{ !$loop->last ? ',' : '' }}@endforeach];

    let allRefleksiFilled = true;
    for (const field of refleksiFields) {
        const textarea = document.getElementById(field);
        if (!textarea || textarea.value.trim().length < 10) {
            allRefleksiFilled = false;
            break;
        }
    }

    // Enable submit button only if all fields are valid (link meeting is optional)
    const submitButton = document.getElementById('submitButton');
    const isValid = linkVideo && allRefleksiFilled;

    console.log('Validation check:', {
        linkVideo: !!linkVideo,
        allRefleksiFilled: allRefleksiFilled,
        isValid: isValid
    });

    submitButton.disabled = !isValid;

    // Update button styling based on state
    if (isValid) {
        // Enabled: hijau, teks putih
        submitButton.className = 'inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors cursor-pointer shadow-md hover:shadow-lg';
        console.log('Submit button ENABLED');
    } else {
        // Disabled: abu-abu terang dengan teks abu-abu gelap
        submitButton.className = 'inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-lg cursor-not-allowed opacity-60';
        console.log('Submit button DISABLED');
    }
}

// Initialize character counts and validation
document.addEventListener('DOMContentLoaded', function() {
    @foreach($refleksiQuestions as $field => $question)
        updateCharCount('{{ $field }}');
    @endforeach

    // Initial validation
    validateForm();

    // Add event listeners to all form inputs
    document.getElementById('link_video').addEventListener('input', validateForm);
    document.getElementById('link_meeting').addEventListener('input', validateForm);

    const refleksiFields = [@foreach($refleksiQuestions as $field => $question)'{{ $field }}'{{ !$loop->last ? ',' : '' }}@endforeach];
    refleksiFields.forEach(field => {
        document.getElementById(field).addEventListener('input', function() {
            updateCharCount(field);
            validateForm();
        });
    });
});

function updateCharCount(field) {
    const textarea = document.getElementById(field);
    const counter = document.getElementById(field + '_count');
    if (textarea && counter) {
        counter.textContent = textarea.value.length;
    }
}

function showToast(message, isError = false) {
    const toast = isError ? document.getElementById('errorToast') : document.getElementById('successToast');

    if (isError) {
        document.getElementById('errorMessage').textContent = message;
    }

    toast.classList.remove('hidden');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

// Save button
document.getElementById('saveButton').addEventListener('click', async () => {
    console.log('Save button clicked');
    const saveButton = document.getElementById('saveButton');
    const originalHTML = saveButton.innerHTML;
    
    // Disable button during save
    saveButton.disabled = true;
    saveButton.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Menyimpan...';
    
    const formData = new FormData(document.getElementById('prosesForm'));

    try {
        console.log('Sending save request...');
        const response = await fetch(`/guru/supervisi/${supervisiId}/proses/save`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        });

        console.log('Response status:', response.status);
        const result = await response.json();
        console.log('Response data:', result);

        if (result.success) {
            showToast('Data berhasil disimpan!');
            console.log('Save successful');
        } else {
            showToast(result.message || 'Gagal menyimpan data', true);
            console.error('Save failed:', result.message);
        }
    } catch (error) {
        console.error('Save error:', error);
        showToast('Error: ' + error.message, true);
    } finally {
        // Re-enable button
        saveButton.disabled = false;
        saveButton.innerHTML = originalHTML;
    }
});

// Show confirm modal
function confirmSubmit() {
    console.log('Submit button clicked');
    const modal = document.getElementById('confirmModal');
    const content = document.getElementById('confirmModalContent');

    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Animate in
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

// Close confirm modal
function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    const content = document.getElementById('confirmModalContent');

    // Animate out
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 200);
}

// Proceed with submit
async function proceedSubmit() {
    console.log('Submit confirmed, proceeding...');

    // Close confirm modal
    closeConfirmModal();

    // Disable button to prevent double submission
    const submitButton = document.getElementById('submitButton');
    submitButton.disabled = true;
    submitButton.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Sedang Memproses...';

    // First save the data
    const formData = new FormData(document.getElementById('prosesForm'));

    try {
        // Save data
        console.log('Step 1: Saving data...');
        const saveResponse = await fetch(`/guru/supervisi/${supervisiId}/proses/save`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        });

        console.log('Save response status:', saveResponse.status);
        const saveResult = await saveResponse.json();
        console.log('Save result:', saveResult);

        if (saveResult.success) {
            // Then submit
            console.log('Step 2: Submitting supervisi...');
            const submitResponse = await fetch(`/guru/supervisi/${supervisiId}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            });

            console.log('Submit response status:', submitResponse.status);
            const submitResult = await submitResponse.json();
            console.log('Submit result:', submitResult);

            if (submitResult.success) {
                console.log('Submit successful! Showing modal...');
                const modal = document.getElementById('successModal');
                const content = modal.querySelector('.transform');

                // Show modal
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                // Animate in
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                console.error('Submit failed:', submitResult.message);
                showToast(submitResult.message || 'Gagal submit supervisi', true);
                // Re-enable button
                submitButton.disabled = false;
                validateForm(); // Reset button state
            }
        } else {
            console.error('Save failed:', saveResult.message);
            showToast(saveResult.message || 'Gagal menyimpan data', true);
            // Re-enable button
            submitButton.disabled = false;
            validateForm(); // Reset button state
        }
    } catch (error) {
        console.error('Submit error:', error);
        showToast('Error: ' + error.message, true);
        // Re-enable button
        submitButton.disabled = false;
        validateForm(); // Reset button state
    }
}

// Auto-save every 30 seconds
setInterval(async () => {
    const formData = new FormData(document.getElementById('prosesForm'));

    try {
        await fetch(`/guru/supervisi/${supervisiId}/proses/save`, {
            method: 'POST',
            body: formData
        });
        console.log('Auto-saved at ' + new Date().toLocaleTimeString());
    } catch (error) {
        console.error('Auto-save failed:', error);
    }
}, 30000);

// Close modal on outside click
document.getElementById('confirmModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'confirmModal') {
        closeConfirmModal();
    }
});

document.getElementById('successModal').addEventListener('click', (e) => {
    if (e.target.id === 'successModal') {
        window.location.href = '{{ route('guru.home') }}';
    }
});
</script>
@endsection
