@extends('layouts.modern')

@section('page-title', 'Proses Pembelajaran')

@section('content')
<!-- Step Navigation -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6 p-2">
    <div class="flex">
        <button
            onclick="window.location.href='{{ route('guru.supervisi.evaluasi', $supervisi->id) }}'"
            class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
        >
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm font-medium">Lembar Evaluasi Diri</span>
        </button>
        <div class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-50 dark:bg-indigo-900/30 border-b-2 border-indigo-600 dark:border-indigo-500 rounded-lg">
            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">Proses</span>
        </div>
    </div>
</div>

<!-- Form -->
<form id="prosesForm">
    @csrf

    <!-- Link Pembelajaran Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6 shadow-sm">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-indigo-50/30 to-blue-50/30 dark:from-indigo-900/10 dark:to-blue-900/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 dark:bg-indigo-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Link Pembelajaran</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Masukkan link video dan meeting pembelajaran</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-8">
                <!-- Link Video -->
                <div>
                    <label for="link_video" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center justify-center w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-xs font-bold">1</span>
                        Link Video Pembelajaran <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                        >
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">Masukkan link YouTube atau Google Drive</p>
                </div>

                <!-- Link Meeting -->
                <div>
                    <label for="link_meeting" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center justify-center w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-xs font-bold">2</span>
                        Link Meeting <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input
                            type="url"
                            name="link_meeting"
                            id="link_meeting"
                            placeholder="https://meet.google.com/... atau https://zoom.us/..."
                            value="{{ old('link_meeting', $proses->link_meeting ?? '') }}"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                        >
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">Masukkan link Google Meet atau Zoom (Opsional)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Refleksi Pembelajaran Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6 shadow-sm">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-purple-50/30 to-pink-50/30 dark:from-purple-900/10 dark:to-pink-900/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-600 dark:bg-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Refleksi Pembelajaran</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Jawab pertanyaan refleksi berikut dengan jujur dan detail</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            @foreach($refleksiQuestions as $field => $question)
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-5 border border-gray-200 dark:border-gray-600">
                    <label for="{{ $field }}" class="flex items-start gap-3 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">
                        <span class="flex items-center justify-center w-7 h-7 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg text-sm font-bold flex-shrink-0">{{ $loop->iteration }}</span>
                        <span class="flex-1 leading-relaxed">{{ $question }} <span class="text-red-500">*</span></span>
                    </label>
                    <div class="space-y-2">
                        <textarea
                            name="{{ $field }}"
                            id="{{ $field }}"
                            rows="4"
                            required
                            maxlength="500"
                            placeholder="Tulis jawaban Anda di sini dengan detail (minimal 10 karakter)..."
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white transition-all resize-none"
                            style="resize: none;"
                            oninput="updateCharCount('{{ $field }}')"
                        >{{ old($field, $proses->$field ?? '') }}</textarea>
                        <div class="flex justify-between items-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Minimal 10 karakter
                            </p>
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-semibold text-gray-600 dark:text-gray-400" id="{{ $field }}_count">0</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">/</span>
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">500</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Info Alert -->
    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-lg p-5 mb-6 shadow-sm">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-yellow-500 dark:bg-yellow-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-yellow-900 dark:text-yellow-200 mb-1.5">Perhatian Penting!</h4>
                <ul class="text-sm text-yellow-800 dark:text-yellow-300 space-y-1 list-disc list-inside">
                    <li>Pastikan semua data sudah benar sebelum submit</li>
                    <li>Data akan otomatis tersimpan setiap 30 detik</li>
                    <li>Setelah submit, supervisi akan direview oleh Kepala Sekolah</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <button
                type="button"
                onclick="window.location.href='{{ route('guru.supervisi.evaluasi', $supervisi->id) }}'"
                style="background-color: #eab308; color: white;"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 font-semibold rounded-lg cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Evaluasi
            </button>

            <div class="flex items-stretch gap-3">
                <button
                    type="button"
                    id="saveButton"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 cursor-pointer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Draft
                </button>

                <button
                    type="submit"
                    id="submitButton"
                    disabled
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 font-bold rounded-lg disabled:cursor-not-allowed"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Submit Supervisi
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Success Modal -->
<div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl text-center p-8 transform transition-all">
        <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Supervisi Berhasil Disubmit!</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">Supervisi Anda telah berhasil disubmit dan akan segera direview oleh Kepala Sekolah.</p>
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
            <p class="text-sm text-green-800 dark:text-green-300">
                <span class="font-semibold">Langkah selanjutnya:</span> Tunggu hasil review dari Kepala Sekolah. Anda akan menerima notifikasi jika ada revisi yang diperlukan.
            </p>
        </div>
        <button
            onclick="window.location.href='{{ route('guru.home') }}'"
            class="w-full px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 dark:from-indigo-500 dark:to-purple-500 dark:hover:from-indigo-600 dark:hover:to-purple-600 text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
        >
            Kembali ke Beranda
        </button>
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

    submitButton.disabled = !isValid;

    // Update button styling based on state
    if (isValid) {
        // Enabled: hijau, teks putih
        submitButton.className = 'inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 cursor-pointer';
    } else {
        // Disabled: abu-abu terang dengan teks abu-abu gelap
        submitButton.className = 'inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-lg cursor-not-allowed';
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
    const formData = new FormData(document.getElementById('prosesForm'));

    try {
        const response = await fetch(`/guru/supervisi/${supervisiId}/proses/save`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showToast('Data berhasil disimpan!');
        } else {
            showToast(result.message || 'Gagal menyimpan data', true);
        }
    } catch (error) {
        showToast('Error: ' + error.message, true);
    }
});

// Submit form
document.getElementById('prosesForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const confirmed = await confirmModal(
        'Apakah Anda yakin ingin mensubmit supervisi ini? Setelah disubmit, supervisi akan direview oleh Kepala Sekolah.',
        'Konfirmasi Submit Supervisi'
    );
    if (!confirmed) {
        return;
    }

    // First save the data
    const formData = new FormData(e.target);

    try {
        // Save data
        const saveResponse = await fetch(`/guru/supervisi/${supervisiId}/proses/save`, {
            method: 'POST',
            body: formData
        });

        const saveResult = await saveResponse.json();

        if (saveResult.success) {
            // Then submit
            const submitResponse = await fetch(`/guru/supervisi/${supervisiId}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const submitResult = await submitResponse.json();

            if (submitResult.success) {
                document.getElementById('successModal').classList.remove('hidden');
            } else {
                showToast(submitResult.message || 'Gagal submit supervisi', true);
            }
        } else {
            showToast(saveResult.message || 'Gagal menyimpan data', true);
        }
    } catch (error) {
        showToast('Error: ' + error.message, true);
    }
});

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
document.getElementById('successModal').addEventListener('click', (e) => {
    if (e.target.id === 'successModal') {
        window.location.href = '{{ route('guru.home') }}';
    }
});
</script>
@endsection
