@extends('layouts.modern')

@section('page-title', 'Lembar Evaluasi Diri')

@section('content')
<!-- Breadcrumb -->
<div class="mb-2 sm:mb-4">
    <x-breadcrumb :items="[
        ['label' => 'Beranda', 'url' => route('guru.home')],
        ['label' => 'Supervisi'],
        ['label' => 'Evaluasi Diri', 'icon' => true]
    ]" />
</div>

<!-- Wrapper Container (3/4 width, centered) -->
<div class="w-full lg:w-3/4 mx-auto px-0 sm:px-4">

<!-- Main Card -->
<div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg">
    <!-- Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 px-3 py-2.5 sm:px-6 sm:py-4 bg-gradient-to-r from-indigo-50/30 to-blue-50/30 dark:from-indigo-900/10 dark:to-blue-900/10">
        <div class="flex items-start justify-between gap-2 sm:gap-4">
            <div>
                <h2 class="text-sm sm:text-xl font-bold text-gray-800 dark:text-gray-100">Upload Dokumen Evaluasi</h2>
                <p class="mt-0.5 sm:mt-1 text-[10px] sm:text-sm text-gray-600 dark:text-gray-400">Upload 7 dokumen yang diperlukan</p>
            </div>
            <!-- Progress Badge -->
            <div class="flex items-center gap-1.5 sm:gap-2 bg-white dark:bg-gray-800 px-2 py-1.5 sm:px-4 sm:py-2 rounded-md sm:rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <span class="text-[9px] sm:text-xs text-gray-500 dark:text-gray-400 block">Progres</span>
                    <span id="documentBadge" class="text-xs sm:text-sm font-bold text-indigo-600 dark:text-indigo-400">0/7</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="px-3 pt-3 sm:px-6 sm:pt-6">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md sm:rounded-lg p-2.5 sm:p-4">
            <div class="flex gap-2 sm:gap-3">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-[11px] sm:text-sm font-medium text-blue-900 dark:text-blue-200">Format: PDF, JPG, PNG</p>
                    <p class="text-[10px] sm:text-sm text-blue-800 dark:text-blue-300 mt-0.5 sm:mt-1">Maks. 2MB per file</p>
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama File</th>
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
                            <td class="px-4 py-3 text-sm align-top">
                                @if($isUploaded && $dokumen)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-gray-700 dark:text-gray-300 text-xs truncate max-w-xs" title="{{ $dokumen->nama_file }}">
                                            {{ $dokumen->nama_file }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs italic">-</span>
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
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-2">
                                    @if($isUploaded && $dokumen)
                                        <a
                                            href="{{ asset('storage/' . $dokumen->path_file) }}"
                                            target="_blank"
                                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors w-full sm:w-auto"
                                            title="Preview dokumen"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Preview
                                        </a>
                                    @endif
                                    
                                    <button
                                        type="button"
                                        data-upload-btn="{{ $key }}"
                                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 cursor-pointer w-full sm:w-auto"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        {{ $isUploaded ? 'Ganti' : 'Upload' }}
                                    </button>
                                    @if($isUploaded)
                                        <form id="delete-form-{{ $key }}" method="POST" action="{{ route('guru.supervisi.delete-document', [$supervisi->id]) }}" class="w-full sm:w-auto">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="jenis_dokumen" value="{{ $key }}">
                                            <button
                                                type="button"
                                                onclick="confirmDeleteForm('delete-form-{{ $key }}', 'Apakah Anda yakin ingin menghapus dokumen ini?')"
                                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg transition-colors text-white cursor-pointer w-full sm:w-auto"
                                                style="background-color: #e63946;"
                                                onmouseover="this.style.backgroundColor='#d62828'"
                                                onmouseout="this.style.backgroundColor='#e63946'"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <button
                                            type="button"
                                            disabled
                                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg transition-colors bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 cursor-not-allowed"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- File inputs will be created dynamically -->

    <!-- Action Buttons -->
    <div class="border-t border-gray-200 dark:border-gray-700 px-3 py-3 sm:px-6 sm:py-4 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 sm:gap-3">
        <a href="{{ route('guru.home') }}" style="background-color: #eab308; color: white;" class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-5 sm:py-3 text-xs sm:text-sm font-semibold rounded-md sm:rounded-lg cursor-pointer">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>

        <button id="nextButton" class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 text-white text-xs sm:text-sm font-bold rounded-md sm:rounded-lg hover:bg-blue-700 cursor-pointer">
            Lanjut
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </button>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm mx-4 shadow-xl">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Berhasil!</h3>
                <p id="modalMessage" class="text-sm text-gray-600 dark:text-gray-400 mt-1"></p>
            </div>
        </div>
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

// Update uploaded docs after upload/delete
function updateUploadedDocs() {
    // This will be called after successful upload/delete
    // For now, we'll reload the page, so uploadedDocs will be updated automatically
}

// Trigger file upload - langsung buka file picker
function triggerFileUpload(jenis) {
    console.log('Triggering file upload for:', jenis);
    const fileInput = document.getElementById('fileInput');

    if (!fileInput) {
        console.error('File input not found');
        return;
    }

    // Set the current upload type and trigger click
    currentUploadType = jenis;
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
        showToast('Format file tidak didukung. Hanya PDF, JPG, dan PNG yang diperbolehkan.', 'error');
        return;
    }

    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        showToast('Ukuran file terlalu besar. Maksimal 2MB per dokumen.', 'error');
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
            showToast('Gagal upload: ' + (result.message || 'Terjadi kesalahan saat mengupload dokumen. Silakan coba lagi.'), 'error');
        }
    } catch (error) {
        console.error('Upload error:', error);
        showToast('Error upload: Terjadi kesalahan saat mengupload dokumen.', 'error');
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

function showToast(message, type = 'success', position = 'bottom-right') {
    const modal = document.getElementById('successModal');
    const modalMessage = document.getElementById('modalMessage');

    // Set message
    modalMessage.textContent = message;

    // Show modal with fade in
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
    }, 10);

    // Hide modal after 5 seconds with fade out
    setTimeout(() => {
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }, 5000);
}

// Delete document function
async function deleteDocument(jenis) {
    console.log('deleteDocument called with jenis:', jenis);

    // Show modal confirmation instead of browser confirm
    showConfirmModal(
        'Apakah Anda yakin ingin menghapus dokumen ini?',
        'Konfirmasi Hapus Dokumen',
        async function() {
            console.log('Proceeding with delete for:', jenis);

            try {
                const response = await fetch(`/guru/supervisi/${supervisiId}/delete-document`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ jenis_dokumen: jenis })
                });

                console.log('Delete response status:', response.status);
                const result = await response.json();
                console.log('Delete response:', result);

                if (result.success) {
                    showToast('Dokumen berhasil dihapus!', 'success');
                    // Reload page after short delay
                    setTimeout(() => {
                        refreshDocumentList();
                    }, 1000);
                } else {
                    showToast('Gagal hapus: ' + (result.message || 'Terjadi kesalahan saat menghapus dokumen. Silakan coba lagi.'), 'error');
                }
            } catch (error) {
                console.error('Delete error:', error);
                showToast('Error hapus: Terjadi kesalahan saat menghapus dokumen.', 'error');
            }
        }
    );
}

// Global variable to track current upload type
let currentUploadType = null;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');

    // Create single file input dynamically
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.id = 'fileInput';
    fileInput.accept = '.pdf,.jpg,.jpeg,.png';
    fileInput.style.position = 'absolute';
    fileInput.style.left = '-9999px';
    document.body.appendChild(fileInput);

    // Update progress badge
    updateProgress();

    // Add event listener to the single file input
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && currentUploadType) {
            handleFileSelection(currentUploadType, file);
            currentUploadType = null; // Reset after use
        }
        // Reset input agar bisa upload file yang sama lagi
        e.target.value = '';
    });
    console.log('File input listener added');

    // Add event listeners to all upload buttons
    const uploadButtons = document.querySelectorAll('[data-upload-btn]');
    console.log('Found upload buttons:', uploadButtons.length);
    uploadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const jenis = this.getAttribute('data-upload-btn');
            console.log('Upload button clicked for:', jenis);

            triggerFileUpload(jenis);
        });
        console.log('Upload button listener added for:', button.getAttribute('data-upload-btn'));
    });

    // Add event listeners to all delete buttons
    const deleteButtons = document.querySelectorAll('[data-delete-btn]');
    console.log('Found delete buttons:', deleteButtons.length);
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const jenis = this.getAttribute('data-jenis');
            const isUploaded = this.getAttribute('data-uploaded') === 'true';

            console.log('Delete button clicked - jenis:', jenis, 'isUploaded:', isUploaded);
            console.log('Button classes:', this.className);

            // Check if button is in disabled state by checking class names
            const isEnabled = this.classList.contains('delete-btn-enabled');
            const isDisabled = this.classList.contains('delete-btn-disabled');

            console.log('Button state check - isUploaded:', isUploaded, 'isEnabled:', isEnabled, 'isDisabled:', isDisabled);

            // Only proceed if document is uploaded and button is enabled
            if (!isUploaded || isDisabled || !isEnabled) {
                console.log('Button is disabled, ignoring click');
                return;
            }

            console.log('Calling deleteDocument for:', jenis);
            deleteDocument(jenis);
        });
        console.log('Delete button listener added for:', button.getAttribute('data-jenis'), 'uploaded:', button.getAttribute('data-uploaded'));
    });

    // Next button - check if all documents are uploaded
    const nextButton = document.getElementById('nextButton');
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            console.log('Next button clicked');
            checkDocumentsAndNavigate(`/guru/supervisi/${supervisiId}/proses`);
        });
        console.log('Next button event listener added');
        
        // Disable next button if documents not complete
        if (uploadedDocs.length < 7) {
            nextButton.disabled = true;
            nextButton.classList.add('bg-gray-300', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-300', 'cursor-not-allowed');
            nextButton.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
        }
    } else {
        console.error('Next button not found');
    }

    // Check documents count and disable/enable proses tab button
    function updateProsesTabButton() {
        const prosesTabButton = document.getElementById('prosesTabButton');
        const uploadedCount = uploadedDocs.length;
        const requiredCount = 7;
        
        if (uploadedCount < requiredCount) {
            prosesTabButton.disabled = true;
            prosesTabButton.classList.add('bg-gray-300', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-300', 'cursor-not-allowed');
            prosesTabButton.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
        } else {
            prosesTabButton.disabled = false;
            prosesTabButton.classList.remove('bg-gray-300', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-300', 'cursor-not-allowed');
            prosesTabButton.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
        }
    }

    // Function to check documents before navigating
    function checkDocumentsAndNavigate(url) {
        const uploadedCount = uploadedDocs.length;
        const requiredCount = 7;
        
        if (uploadedCount < requiredCount) {
            showToast(`Dokumen belum lengkap: Anda harus mengupload semua ${requiredCount} dokumen terlebih dahulu sebelum dapat melanjutkan ke tab Proses. Dokumen yang sudah diupload: ${uploadedCount}/${requiredCount}`, 'warning');
            return false;
        }
        
        window.location.href = url;
    }

    // Update proses tab button state
    updateProsesTabButton();

    console.log('Initialization complete');
});

// Helper function for form confirmation
function confirmDeleteForm(formId, message) {
    showConfirmModal(message, 'Konfirmasi Hapus Dokumen', function() {
        document.getElementById(formId).submit();
    });
}
</script>

</div>
<!-- End Wrapper Container -->

@endsection
