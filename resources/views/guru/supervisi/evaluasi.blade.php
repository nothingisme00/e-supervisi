@extends('layouts.modern')

@section('page-title', 'Lembar Evaluasi Diri')

@section('content')
<!-- Step Navigation -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6 p-2">
    <div class="flex">
        <div class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-50 dark:bg-indigo-900/30 border-b-2 border-indigo-600 dark:border-indigo-500 rounded-lg">
            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">Lembar Evaluasi Diri</span>
        </div>
        <button onclick="window.location.href='{{ route('guru.supervisi.proses', $supervisi->id) }}'" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm font-medium">Proses</span>
        </button>
    </div>
</div>

<!-- Main Card -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <!-- Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-indigo-50/30 to-blue-50/30 dark:from-indigo-900/10 dark:to-blue-900/10">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Upload Dokumen Evaluasi Diri</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload 7 dokumen yang diperlukan (opsional)</p>
            </div>
            <!-- Progress Badge -->
            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 block">Progres Upload</span>
                    <span id="documentBadge" class="text-sm font-bold text-indigo-600 dark:text-indigo-400">0/7 Dokumen</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="px-6 pt-6">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-blue-900 dark:text-blue-200">Format yang diperbolehkan: PDF, JPG, PNG</p>
                    <p class="text-sm text-blue-800 dark:text-blue-300 mt-1">Maksimal ukuran file: 2MB per dokumen</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="p-6">
        @php
            $documents = [
                'capaian_pembelajaran' => 'Capaian Pembelajaran (CP)',
                'alur_tujuan_pembelajaran' => 'Alur Tujuan Pembelajaran (ATP)',
                'kalender' => 'Kalender',
                'program_tahunan' => 'Program Tahunan',
                'program_semester' => 'Program Semester',
                'modul_ajar' => 'Modul Ajar (1x pertemuan)',
                'bahan_ajar' => 'Bahan Ajar'
            ];
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 border-b-2 border-gray-300 dark:border-gray-600">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama Dokumen</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Deskripsi <span class="text-gray-500 dark:text-gray-400 font-normal">(Opsional)</span></th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                    @foreach($documents as $key => $label)
                        @php
                            $isUploaded = in_array($key, $uploadedDocuments);
                            $dokumen = $supervisi->dokumenEvaluasi->where('jenis_dokumen', $key)->first();
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 align-top">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 align-top">{{ $label }}</td>
                            <td class="px-4 py-3 align-top">
                                @if($isUploaded && $dokumen)
                                    <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-3 min-w-[200px]">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            {{ $dokumen->deskripsi ?? '-' }}
                                        </p>
                                    </div>
                                @else
                                    <div class="min-w-[250px]">
                                        <textarea
                                            id="deskripsi_{{ $key }}"
                                            maxlength="100"
                                            rows="3"
                                            placeholder="Tulis deskripsi dokumen (opsional)"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent resize-none"
                                            style="resize: none;"
                                        ></textarea>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <span id="charCount_{{ $key }}">0</span>/100 karakter
                                        </p>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center align-top">
                                @if($isUploaded)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-medium rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Sudah diupload
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        Belum diupload
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        type="button"
                                        onclick="triggerFileUpload('{{ $key }}')"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        {{ $isUploaded ? 'Ganti' : 'Upload' }}
                                    </button>
                                    <button
                                        type="button"
                                        onclick="deleteDocument('{{ $key }}')"
                                        {{ !$isUploaded ? 'disabled' : '' }}
                                        style="{{ $isUploaded ? 'background-color: #dc2626; color: white;' : 'background-color: #9ca3af; color: #374151;' }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg {{ $isUploaded ? 'hover:bg-red-700 cursor-pointer' : 'cursor-not-allowed' }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <a href="{{ route('guru.home') }}" style="background-color: #eab308; color: white;" class="inline-flex items-center justify-center gap-2 px-5 py-3 font-semibold rounded-lg cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Beranda
        </a>

        <button id="nextButton" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 cursor-pointer">
            Lanjut ke Proses
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </button>
    </div>
</div>

<!-- Hidden File Inputs (satu untuk setiap jenis dokumen) -->
@php
    $documents = [
        'capaian_pembelajaran' => 'Capaian Pembelajaran (CP)',
        'alur_tujuan_pembelajaran' => 'Alur Tujuan Pembelajaran (ATP)',
        'kalender' => 'Kalender',
        'program_tahunan' => 'Program Tahunan',
        'program_semester' => 'Program Semester',
        'modul_ajar' => 'Modul Ajar (1x pertemuan)',
        'bahan_ajar' => 'Bahan Ajar'
    ];
@endphp

@foreach($documents as $key => $label)
    <input type="file" id="fileInput_{{ $key }}" accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
@endforeach

<!-- Success Toast -->
<div id="successToast" class="hidden fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span id="toastMessage">Berhasil!</span>
    </div>
</div>

<script type="text/javascript">
'use strict';

// Constants
const supervisiId = parseInt('{{ $supervisi->id }}');
const documents = {
    'capaian_pembelajaran': 'Capaian Pembelajaran (CP)',
    'alur_tujuan_pembelajaran': 'Alur Tujuan Pembelajaran (ATP)',
    'kalender': 'Kalender',
    'program_tahunan': 'Program Tahunan',
    'program_semester': 'Program Semester',
    'modul_ajar': 'Modul Ajar (1x pertemuan)',
    'bahan_ajar': 'Bahan Ajar'
};

let uploadedDocs = {!! json_encode($uploadedDocuments) !!};

console.log('Script loaded. Supervisi ID:', supervisiId);
console.log('Uploaded docs:', uploadedDocs);

// Update document list after upload/delete (reload page for simplicity)
function refreshDocumentList() {
    window.location.reload();
}

// Trigger file upload - langsung buka file picker
function triggerFileUpload(jenis) {
    console.log('Triggering file upload for:', jenis);
    const fileInput = document.getElementById('fileInput_' + jenis);

    if (!fileInput) {
        console.error('File input not found for:', jenis);
        return;
    }

    // Trigger click pada hidden file input
    fileInput.click();
}

// Handle file selection dan upload
function handleFileSelection(jenis, file) {
    if (!file) {
        console.log('No file selected');
        return;
    }

    console.log('File selected:', file.name, 'for:', jenis);

    // Validate file type
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
        alert('Format file tidak didukung. Hanya PDF, JPG, dan PNG yang diperbolehkan.');
        return;
    }

    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file terlalu besar. Maksimal 2MB.');
        return;
    }

    // Upload file
    uploadFile(jenis, file);
}

// Upload file ke server
async function uploadFile(jenis, file) {
    const formData = new FormData();
    formData.append('jenis_dokumen', jenis);
    formData.append('file', file);
    formData.append('_token', '{{ csrf_token() }}');

    // Tambahkan deskripsi jika ada
    const deskripsiField = document.getElementById('deskripsi_' + jenis);
    if (deskripsiField) {
        formData.append('deskripsi', deskripsiField.value);
    }

    console.log('Uploading file...');

    try {
        const response = await fetch(`/guru/supervisi/${supervisiId}/upload`, {
            method: 'POST',
            body: formData
        });

        console.log('Response status:', response.status);

        const result = await response.json();
        console.log('Response:', result);

        if (result.success) {
            showToast('Dokumen berhasil diupload!');
            // Reload page after short delay
            setTimeout(() => {
                refreshDocumentList();
            }, 1000);
        } else {
            alert('Gagal upload: ' + (result.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        console.error('Upload error:', error);
        alert('Error: ' + error.message);
    }
}

function updateProgress() {
    const uploaded = uploadedDocs.length;
    const badge = document.getElementById('documentBadge');
    badge.textContent = `${uploaded}/7 Dokumen`;

    if (uploaded >= 7) {
        badge.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-800', 'dark:text-gray-200');
        badge.classList.add('bg-green-100', 'dark:bg-green-900/30', 'text-green-800', 'dark:text-green-300');
    } else {
        badge.classList.remove('bg-green-100', 'dark:bg-green-900/30', 'text-green-800', 'dark:text-green-300');
        badge.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-800', 'dark:text-gray-200');
    }
}

function showToast(message) {
    const toast = document.getElementById('successToast');
    const toastMessage = document.getElementById('toastMessage');
    toastMessage.textContent = message;
    toast.classList.remove('hidden');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

// Delete document function
async function deleteDocument(jenis) {
    if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
        return;
    }

    try {
        const response = await fetch(`/guru/supervisi/${supervisiId}/delete-document`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ jenis_dokumen: jenis })
        });

        const result = await response.json();

        if (result.success) {
            showToast('Dokumen berhasil dihapus!');
            // Reload page after short delay
            setTimeout(() => {
                refreshDocumentList();
            }, 1000);
        } else {
            alert('Gagal hapus: ' + (result.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');

    // Update progress badge
    updateProgress();

    // Add event listeners to all file inputs
    Object.keys(documents).forEach(jenis => {
        const fileInput = document.getElementById('fileInput_' + jenis);
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleFileSelection(jenis, file);
                }
                // Reset input agar bisa upload file yang sama lagi
                e.target.value = '';
            });
            console.log('File input listener added for:', jenis);
        }
    });

    // Next button - documents are optional, so no need to check count
    const nextButton = document.getElementById('nextButton');
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            console.log('Next button clicked');
            window.location.href = `/guru/supervisi/${supervisiId}/proses`;
        });
        console.log('Next button event listener added');
    } else {
        console.error('Next button not found');
    }

    console.log('Initialization complete');

    // Character counter untuk textarea deskripsi
    @foreach(['capaian_pembelajaran', 'alur_tujuan_pembelajaran', 'kalender', 'program_tahunan', 'program_semester', 'modul_ajar', 'bahan_ajar'] as $key)
        const textarea_{{ $key }} = document.getElementById('deskripsi_{{ $key }}');
        if (textarea_{{ $key }}) {
            textarea_{{ $key }}.addEventListener('input', function() {
                const charCount = document.getElementById('charCount_{{ $key }}');
                if (charCount) {
                    charCount.textContent = this.value.length;
                }
            });
        }
    @endforeach
});
</script>
@endsection
