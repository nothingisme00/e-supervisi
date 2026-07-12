@extends('layouts.modern')

@section('page-title', 'Lembar Evaluasi Diri')

@section('content')
<!-- Wrapper Container (3/4 width, centered) -->
<div class="w-full lg:w-3/4 mx-auto px-0 sm:px-4 pb-24 md:pb-0">

<!-- Main Card -->
<x-card flush>
    <!-- Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-4 sm:px-6 sm:py-5 bg-gray-50 dark:bg-gray-800/60">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0 flex-1">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100">Upload Dokumen Evaluasi</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload 7 dokumen yang diperlukan</p>
            </div>
            <!-- Progress Badge -->
            <div class="flex items-center gap-2.5 bg-white dark:bg-gray-800 px-4 py-2.5 sm:px-5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm shrink-0">
                <x-icon name="document" class="w-6 h-6 sm:w-7 sm:h-7 text-primary-600 dark:text-primary-400" />
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 block">Progres</span>
                    <span id="documentBadge" class="text-base sm:text-lg font-bold text-primary-600 dark:text-primary-400 tabular-nums">0/7 Dokumen</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="px-4 pt-4 sm:px-6 sm:pt-5">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 sm:p-5">
            <div class="flex gap-3">
                <x-icon name="information-circle" class="w-6 h-6 text-blue-600 dark:text-blue-400 shrink-0" />
                <div>
                    <p class="text-sm sm:text-base font-medium text-blue-900 dark:text-blue-200">Format: PDF, JPG, PNG</p>
                    <p class="text-sm text-blue-800 dark:text-blue-300 mt-0.5">Maks. 2MB per file</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Section: SATU pola kartu responsif untuk semua ukuran -->
    <div class="p-4 sm:p-6">
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

        <div class="space-y-3">
            @foreach($documents as $key => $label)
                @php
                    $isUploaded = in_array($key, $uploadedDocuments);
                    $dokumen = $supervisi->dokumenEvaluasi->where('jenis_dokumen', $key)->first();
                @endphp

                <!-- Document Card -->
                <div id="doc-card-{{ $key }}" data-uploaded="{{ $isUploaded ? 'true' : 'false' }}" class="doc-card bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600 {{ $isUploaded ? 'border-l-4 border-l-emerald-500' : '' }}">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-4">
                        <!-- Info: nomor + nama + status + file -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 text-sm font-bold rounded-lg shrink-0 tabular-nums">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="min-w-0">
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-white leading-tight">
                                            {{ Str::beforeLast($label, ' (') }}
                                        </h3>
                                        @if(Str::contains($label, '('))
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ Str::between($label, '(', ')') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <!-- Status Upload -->
                                @if($isUploaded)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-semibold rounded-full shrink-0">
                                        <x-icon name="check" class="w-4 h-4" />
                                        Sudah
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-full shrink-0">
                                        Belum
                                    </span>
                                @endif
                            </div>

                            @if($isUploaded && $dokumen)
                                <div class="flex items-center gap-2 mt-3 px-3 py-2.5 bg-primary-50 dark:bg-primary-900/40 border border-primary-200 dark:border-primary-700 rounded-lg">
                                    <x-icon name="document" class="w-4 h-4 text-primary-500 dark:text-primary-400 shrink-0" />
                                    <span class="text-sm font-medium text-primary-700 dark:text-primary-200 truncate" title="{{ $dokumen->nama_file }}">
                                        {{ $dokumen->nama_file }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Aksi: 1 tombol primer (upload/ganti) + aksi sekunder ikon ber-label -->
                        <div class="flex gap-2 md:shrink-0">
                            @if($isUploaded && $dokumen)
                                <x-button type="button" data-upload-btn="{{ $key }}" class="flex-1 md:flex-none justify-center min-h-[44px]">
                                    <x-icon name="arrow-down-tray" class="w-4 h-4 rotate-180" />
                                    Ganti
                                </x-button>
                                <x-button href="{{ route('guru.supervisi.preview', $dokumen->id) }}" target="_blank" variant="ghost" size="sm" class="flex-1 md:flex-none justify-center min-h-[44px]">
                                    <x-icon name="eye" class="w-4 h-4" />
                                    Preview
                                </x-button>
                                <form id="delete-form-{{ $key }}" method="POST" action="{{ route('guru.supervisi.delete-document', [$supervisi->id]) }}" class="contents">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="jenis_dokumen" value="{{ $key }}">
                                    <x-button type="button" variant="ghost" size="sm" class="flex-1 md:flex-none justify-center min-h-[44px]" onclick="confirmDeleteForm('delete-form-{{ $key }}', 'Apakah Anda yakin ingin menghapus dokumen ini?')">
                                        <x-icon name="trash" class="w-4 h-4" />
                                        Hapus
                                    </x-button>
                                </form>
                            @else
                                <x-button type="button" data-upload-btn="{{ $key }}" class="w-full md:w-auto justify-center min-h-[44px]">
                                    <x-icon name="arrow-down-tray" class="w-4 h-4 rotate-180" />
                                    Upload Dokumen
                                </x-button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- File inputs will be created dynamically -->

    <!-- Action Buttons -->
    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-4 sm:px-6 sm:py-4 flex flex-row items-center justify-between gap-3 sm:gap-4">
        <x-button href="{{ route('guru.home') }}" variant="secondary" class="min-h-[44px]">
            <x-icon name="arrow-left" class="w-4 h-4" />
            Kembali
        </x-button>

        <button id="nextButton" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 md:px-6 min-h-[44px] bg-primary-600 text-white text-sm font-bold rounded-lg hover:bg-primary-700 cursor-pointer transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900">
            Lanjut
            <x-icon name="arrow-right" class="w-5 h-5 md:w-4 md:h-4" />
        </button>
    </div>
</x-card>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden opacity-0 transition-all duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-sm mx-4 shadow-2xl transform transition-all duration-300 scale-95" id="successModalContent">
        <!-- Close button -->
        <button onclick="hideSuccessModal()" aria-label="Tutup" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors cursor-pointer">
            <x-icon name="x-mark" class="w-5 h-5" />
        </button>

        <div class="text-center">
            <!-- Success Icon - Large circle -->
            <div class="w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-5">
                <x-icon name="check" class="w-10 h-10 text-emerald-500 dark:text-emerald-400" />
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                Berhasil!
            </h3>

            <!-- Description -->
            <p id="modalMessage" class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-1"></p>
        </div>

        <!-- Progress indicator -->
        <div class="mt-6 h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
            <div id="successProgress" class="h-full bg-emerald-500 rounded-full" style="width: 100%; transition: width 3s linear;"></div>
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

// Update document list after upload/delete (reload page for simplicity)
function refreshDocumentList() {
    window.location.reload();
}

// Trigger file upload - langsung buka file picker
function triggerFileUpload(jenis) {
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
        return;
    }

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

    try {
        const response = await fetch(`/guru/supervisi/${supervisiId}/upload`, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

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

// Auto-close timer reference
let successModalTimer = null;
const AUTO_CLOSE_DURATION = 3000; // 3 seconds

function showToast(message, type = 'success', position = 'bottom-right') {
    const modal = document.getElementById('successModal');
    const modalContent = document.getElementById('successModalContent');
    const modalMessage = document.getElementById('modalMessage');
    const progressBar = document.getElementById('successProgress');

    // Clear any existing timer
    if (successModalTimer) {
        clearTimeout(successModalTimer);
        successModalTimer = null;
    }

    // Set message
    modalMessage.textContent = message;

    // Reset progress bar immediately
    if (progressBar) {
        progressBar.style.transition = 'none';
        progressBar.style.width = '100%';
        // Force reflow to ensure the reset takes effect
        progressBar.offsetWidth;
    }

    // Show modal with smooth animation
    modal.classList.remove('hidden');

    // Use requestAnimationFrame for smoother animation
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        if (modalContent) {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }

        // Start progress bar countdown after modal is visible
        requestAnimationFrame(() => {
            if (progressBar) {
                progressBar.style.transition = `width ${AUTO_CLOSE_DURATION}ms linear`;
                progressBar.style.width = '0%';
            }
        });
    });

    // Hide modal after countdown
    successModalTimer = setTimeout(() => {
        hideSuccessModal();
    }, AUTO_CLOSE_DURATION);
}

function hideSuccessModal() {
    const modal = document.getElementById('successModal');
    const modalContent = document.getElementById('successModalContent');
    const progressBar = document.getElementById('successProgress');

    // Clear timer if manually closed
    if (successModalTimer) {
        clearTimeout(successModalTimer);
        successModalTimer = null;
    }

    // Stop progress bar animation
    if (progressBar) {
        progressBar.style.transition = 'none';
    }

    modal.classList.add('opacity-0');
    if (modalContent) {
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
    }
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Global variable to track current upload type
let currentUploadType = null;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
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

    // Add event listeners to all upload buttons
    const uploadButtons = document.querySelectorAll('[data-upload-btn]');
    uploadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const jenis = this.getAttribute('data-upload-btn');
            triggerFileUpload(jenis);
        });
    });

    // Next button - check if all documents are uploaded
    const nextButton = document.getElementById('nextButton');
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            checkDocumentsAndNavigate(`/guru/supervisi/${supervisiId}/proses`);
        });

        // Disable next button if documents not complete
        if (uploadedDocs.length < 7) {
            nextButton.disabled = true;
            nextButton.classList.add('bg-gray-300', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-300', 'cursor-not-allowed');
            nextButton.classList.remove('bg-primary-600', 'text-white', 'hover:bg-primary-700');
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

    // Auto-scroll to first incomplete document (mobile only)
    function scrollToFirstIncomplete() {
        // Only run on mobile view
        const isMobile = window.innerWidth < 768;
        if (!isMobile) {
            return; // Skip auto-scroll on desktop
        }

        // Get all document cards (satu pola — semua selalu terlihat)
        const docElements = document.querySelectorAll('.doc-card');

        for (let elem of docElements) {
            if (elem.dataset.uploaded === 'false') {
                setTimeout(() => {
                    elem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Add highlight effect
                    elem.classList.add('ring-2', 'ring-primary-500', 'ring-offset-2');
                    elem.style.transition = 'all 0.3s ease';
                    setTimeout(() => {
                        elem.classList.remove('ring-2', 'ring-primary-500', 'ring-offset-2');
                    }, 2500);
                }, 800);
                break;
            }
        }
    }

    // Call auto-scroll on page load
    scrollToFirstIncomplete();
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
