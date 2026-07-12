@extends('layouts.modern')

@section('page-title', 'Proses Pembelajaran')

@section('content')
<!-- Wrapper Container (3/4 width, centered) -->
<div class="w-full lg:w-3/4 mx-auto space-y-4 sm:space-y-6 px-0 sm:px-4 pb-24 md:pb-0">

<!-- Form -->
<form id="prosesForm">
    @csrf

    <!-- Link Pembelajaran Card -->
    <x-card flush class="mb-4 sm:mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6 sm:py-4 bg-gray-50 dark:bg-gray-800/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-10 sm:h-10 bg-primary-600 dark:bg-primary-500 rounded-lg flex items-center justify-center">
                    <x-icon name="link" class="w-5 h-5 text-white" />
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-gray-100">Link Pembelajaran</h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Masukkan link video dan meeting</p>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6">
            <div class="space-y-4 sm:space-y-6">
                <!-- Link Video -->
                <div id="field-link_video" data-filled="{{ !empty($proses->link_video) ? 'true' : 'false' }}" class="input-field bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 sm:p-5 border border-gray-200 dark:border-gray-600">
                    <label for="link_video" class="flex items-center gap-2 text-sm sm:text-sm font-bold text-gray-800 dark:text-gray-200 mb-3">
                        <span class="flex items-center justify-center w-6 h-6 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-full text-xs font-bold">1</span>
                        Link Video <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <x-icon name="video-camera" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" />
                        <input
                            type="url"
                            name="link_video"
                            id="link_video"
                            placeholder="https://youtube.com/... atau https://drive.google.com/..."
                            value="{{ old('link_video', $proses->link_video ?? '') }}"
                            class="form-control pl-11 py-3"
                        >
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 flex items-center gap-1.5">
                        <x-icon name="information-circle" class="w-4 h-4" />
                        Link YouTube atau Google Drive
                    </p>
                </div>

                <!-- Link Meeting -->
                <div id="field-link_meeting" data-filled="{{ !empty($proses->link_meeting) ? 'true' : 'false' }}" class="input-field bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 sm:p-5 border border-gray-200 dark:border-gray-600">
                    <label for="link_meeting" class="flex items-center gap-2 text-sm sm:text-sm font-bold text-gray-800 dark:text-gray-200 mb-3">
                        <span class="flex items-center justify-center w-6 h-6 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-full text-xs font-bold">2</span>
                        Link Meeting <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <x-icon name="link" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" />
                        <input
                            type="url"
                            name="link_meeting"
                            id="link_meeting"
                            placeholder="https://meet.google.com/... atau https://zoom.us/..."
                            value="{{ old('link_meeting', $proses->link_meeting ?? '') }}"
                            class="form-control pl-11 py-3"
                        >
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 flex items-center gap-1.5">
                        <x-icon name="information-circle" class="w-4 h-4" />
                        Link Google Meet atau Zoom (Opsional)
                    </p>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Refleksi Pembelajaran Card -->
    <x-card flush>
        <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6 sm:py-4 bg-gray-50 dark:bg-gray-800/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-600 dark:bg-primary-500 rounded-lg flex items-center justify-center">
                    <x-icon name="light-bulb" class="w-5 h-5 text-white" />
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-gray-100">Refleksi Pembelajaran</h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Jawab pertanyaan refleksi berikut</p>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
            @foreach($refleksiQuestions as $field => $question)
                <div id="field-{{ $field }}" data-filled="{{ !empty($proses->$field) ? 'true' : 'false' }}" class="input-field bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 sm:p-5 border border-gray-200 dark:border-gray-600">
                    <label for="{{ $field }}" class="flex items-start gap-3 text-sm font-bold text-gray-800 dark:text-gray-200 mb-3">
                        <span class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 bg-primary-600 dark:bg-primary-500 text-white rounded-lg text-xs sm:text-sm font-bold shrink-0 shadow-md">{{ $loop->iteration }}</span>
                        <span class="flex-1 leading-relaxed pt-0.5">{{ $question }} <span class="text-red-500">*</span></span>
                    </label>
                    <div class="space-y-2">
                        <textarea
                            name="{{ $field }}"
                            id="{{ $field }}"
                            rows="4"
                            maxlength="500"
                            placeholder="Tulis jawaban Anda di sini (minimal 10 karakter)..."
                            class="form-control py-3 resize-none"
                            style="resize: none;"
                            oninput="updateCharCount('{{ $field }}')"
                        >{{ old($field, $proses->$field ?? '') }}</textarea>
                        <div class="flex justify-between items-center px-1">
                            <p class="text-xs text-gray-600 dark:text-gray-400 flex items-center gap-1.5 font-medium">
                                <x-icon name="information-circle" class="w-4 h-4" />
                                Min. 10 karakter
                            </p>
                            <div class="flex items-center gap-1.5 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-lg tabular-nums">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300" id="{{ $field }}_count">0</span>
                                <span class="text-xs text-gray-600 dark:text-gray-400">/</span>
                                <span class="text-xs font-semibold text-gray-600 dark:text-gray-400">500</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Info Alert - Moved Inside -->
            <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 dark:border-amber-400 rounded-xl p-4 sm:p-5">
                <div class="flex gap-3 sm:gap-4">
                    <div class="shrink-0">
                        <div class="w-10 h-10 bg-amber-500 dark:bg-amber-600 rounded-lg flex items-center justify-center">
                            <x-icon name="exclamation-triangle" class="w-5 h-5 text-white" />
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-amber-900 dark:text-amber-200 mb-2">Perhatian!</h4>
                        <ul class="text-xs sm:text-sm text-amber-800 dark:text-amber-300 space-y-1.5 list-disc list-inside">
                            <li>Pastikan data sudah benar sebelum submit</li>
                            <li>Data otomatis tersimpan tiap 30 detik</li>
                            <li>Setelah submit akan direview</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons - Moved Inside -->
            <div class="pt-2">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4">
                    <x-button type="button" variant="secondary" class="justify-center min-h-[44px]" onclick="window.location.href='{{ route('guru.supervisi.evaluasi', $supervisi->id) }}'">
                        <x-icon name="arrow-left" class="w-4 h-4" />
                        Kembali
                    </x-button>

                    <div class="flex flex-row items-stretch gap-2 sm:gap-3">
                        <x-button type="button" id="saveButton" variant="secondary" class="flex-1 sm:flex-none justify-center min-h-[44px]">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            Simpan
                        </x-button>

                        {{-- kelas tombol submit dikelola JS validateForm() — biarkan <button> mentah --}}
                        <button
                            type="button"
                            id="submitButton"
                            disabled
                            onclick="confirmSubmit()"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-3 min-h-[44px] text-sm font-semibold rounded-lg disabled:cursor-not-allowed"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</form>



</div>
<!-- End Wrapper Container -->

<!-- Confirm Submit Modal -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div id="confirmModalContent" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-sm w-full transform transition-all duration-200 scale-95 opacity-0">
        <!-- Content -->
        <div class="p-6 text-center">
            <!-- Icon -->
            <div class="w-14 h-14 rounded-full flex items-center justify-center bg-primary-100 dark:bg-primary-900/30 mx-auto mb-4">
                <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <button onclick="proceedSubmit()" class="flex-1 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-medium rounded-lg transition-colors">
                    Ya, Submit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<!-- Success Submit Modal -->
<div id="successModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[70] items-center justify-center p-4">
    <div id="successModalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0">
        <!-- Content -->
        <div class="p-8 text-center">
            <!-- Success Icon - Large circle -->
            <div class="w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                Supervisi Berhasil Disubmit!
            </h3>

            <!-- Description -->
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                Data akan direview oleh Kepala Sekolah. Anda akan diberitahu jika ada revisi.
            </p>

            <!-- Button -->
            <button
                onclick="window.location.href='{{ route('guru.home') }}'"
                class="w-full px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors duration-200">
                Kembali ke Beranda
            </button>
        </div>
    </div>
</div>

<!-- Save Options Modal -->
<div id="saveOptionsModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div id="saveOptionsModalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0">
        <!-- Content -->
        <div class="p-8 text-center">
            <!-- Success Icon - Large circle -->
            <div class="w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                Data Berhasil Disimpan!
            </h3>

            <!-- Description -->
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                Anda dapat melanjutkan mengisi data atau kembali ke beranda.
            </p>

            <!-- Buttons -->
            <div class="flex flex-col gap-3">
                <button onclick="closeSaveOptionsModal(); window.location.href='{{ route('guru.home') }}';" class="w-full px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors duration-200">
                    Kembali ke Beranda
                </button>
                <button onclick="closeSaveOptionsModal()" class="w-full text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium py-2 transition-colors">
                    Lanjut Mengisi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast - Minimal tanpa tombol -->
<div id="successToast" class="hidden fixed top-4 left-1/2 -translate-x-1/2 z-50 transform -translate-y-20 opacity-0 transition-all duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl px-5 py-4 flex items-center gap-3 min-w-[280px]">
        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-semibold text-gray-900 dark:text-white text-sm">Berhasil!</p>
            <p class="text-gray-500 dark:text-gray-400 text-xs">Data berhasil disimpan</p>
        </div>
    </div>
</div>

<!-- Error Modal - Dengan tombol seperti referensi -->
<div id="errorModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div id="errorModalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0">
        <!-- Content -->
        <div class="p-8 text-center">
            <!-- Error Icon - Large circle -->
            <div class="w-20 h-20 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                Terjadi Kesalahan
            </h3>

            <!-- Description -->
            <p id="errorMessage" class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                Sistem tidak dapat menyimpan data. Silakan periksa koneksi internet Anda dan coba lagi.
            </p>

            <!-- Buttons -->
            <div class="flex flex-col gap-3">
                <button onclick="hideErrorModal(); retryLastAction();" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200">
                    Coba Lagi
                </button>
                <button onclick="hideErrorModal()" class="w-full text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium py-2 transition-colors">
                    Batalkan dan kembali
                </button>
            </div>
        </div>
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


    submitButton.disabled = !isValid;

    // Update button styling based on state
    if (isValid) {
        // Enabled: primary, teks putih
        submitButton.className = 'flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[44px] bg-primary-600 text-white text-sm font-bold rounded-lg hover:bg-primary-700 transition-colors cursor-pointer';
    } else {
        // Disabled: abu-abu terang dengan teks abu-abu gelap
        submitButton.className = 'flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[44px] bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-lg cursor-not-allowed opacity-60';
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

// Last action for retry functionality
let lastAction = null;

// Catatan: jangan deklarasikan `showToast` lokal di sini — layout modern.blade
// mendeklarasikan global dengan nama sama SETELAH skrip halaman dan menimpanya,
// sehingga pesan error tampil bergaya sukses. Error memakai showErrorModal langsung.
function showErrorModal(message) {
    const modal = document.getElementById('errorModal');
    const content = document.getElementById('errorModalContent');
    document.getElementById('errorMessage').textContent = message;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function hideErrorModal() {
    const modal = document.getElementById('errorModal');
    const content = document.getElementById('errorModalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function retryLastAction() {
    if (lastAction === 'save') {
        document.getElementById('saveButton').click();
    } else if (lastAction === 'submit') {
        document.getElementById('submitButton').click();
    }
}

// Helper functions for manual toast dismissal
function hideSuccessToast() {
    const toast = document.getElementById('successToast');
    toast.classList.remove('translate-y-0', 'opacity-100');
    toast.classList.add('-translate-y-20', 'opacity-0');
    setTimeout(() => toast.classList.add('hidden'), 300);
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
            // Show options modal instead of just toast
            showSaveOptionsModal();
            console.log('Save successful');
        } else {
            showErrorModal(result.message || 'Gagal menyimpan data');
            console.error('Save failed:', result.message);
        }
    } catch (error) {
        console.error('Save error:', error);
        showErrorModal('Error: ' + error.message);
    } finally {
        // Re-enable button
        saveButton.disabled = false;
        saveButton.innerHTML = originalHTML;
    }
});

// Show save options modal
function showSaveOptionsModal() {
    const modal = document.getElementById('saveOptionsModal');
    const content = document.getElementById('saveOptionsModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

// Close save options modal
function closeSaveOptionsModal() {
    const modal = document.getElementById('saveOptionsModal');
    const content = document.getElementById('saveOptionsModalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 200);
}

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
                showErrorModal(submitResult.message || 'Gagal submit supervisi');
                // Re-enable button
                submitButton.disabled = false;
                validateForm(); // Reset button state
            }
        } else {
            console.error('Save failed:', saveResult.message);
            showErrorModal(saveResult.message || 'Gagal menyimpan data');
            // Re-enable button
            submitButton.disabled = false;
            validateForm(); // Reset button state
        }
    } catch (error) {
        console.error('Submit error:', error);
        showErrorModal('Error: ' + error.message);
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
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
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

// Auto-scroll to first incomplete field
function scrollToFirstIncompleteField() {
    const inputFields = document.querySelectorAll('.input-field');
    
    for (let field of inputFields) {
        // Skip link_meeting as it's optional
        if (field.id === 'field-link_meeting') continue;
        
        const isFilled = field.dataset.filled === 'true';
        
        if (!isFilled) {
            setTimeout(() => {
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Add highlight effect
                field.classList.add('ring-2', 'ring-primary-500', 'ring-offset-2');
                field.style.transition = 'all 0.3s ease';
                
                // Focus on the input inside
                const input = field.querySelector('input, textarea');
                if (input) {
                    setTimeout(() => input.focus(), 300);
                }
                
                setTimeout(() => {
                    field.classList.remove('ring-2', 'ring-primary-500', 'ring-offset-2');
                }, 2500);
            }, 800);
            break;
        }
    }
}

// Call auto-scroll on page load
scrollToFirstIncompleteField();
</script>
@endsection
