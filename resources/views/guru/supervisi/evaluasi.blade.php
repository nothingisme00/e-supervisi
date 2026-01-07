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
<div class="w-full lg:w-3/4 mx-auto px-0 sm:px-4 pb-24 md:pb-0">

<!-- Main Card -->
<div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg">
    <!-- Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-indigo-50/30 to-blue-50/30 dark:from-indigo-900/10 dark:to-blue-900/10">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0 flex-1">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100">Upload Dokumen Evaluasi</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload 7 dokumen yang diperlukan</p>
            </div>
            <!-- Progress Badge -->
            <div class="flex items-center gap-2.5 bg-white dark:bg-gray-800 px-4 py-2.5 sm:px-5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 block">Progres</span>
                    <span id="documentBadge" class="text-base sm:text-lg font-bold text-indigo-600 dark:text-indigo-400">0/7 Dokumen</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="px-4 pt-4 sm:px-6 sm:pt-5">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 sm:p-5">
            <div class="flex gap-3">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm sm:text-base font-medium text-blue-900 dark:text-blue-200">Format: PDF, JPG, PNG</p>
                    <p class="text-sm text-blue-800 dark:text-blue-300 mt-0.5">Maks. 2MB per file</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Section -->
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

        <!-- ======================= -->
        <!-- MOBILE: Card Layout (visible only on mobile) -->
        <!-- ======================= -->
        <div class="md:hidden space-y-3">
            @foreach($documents as $key => $label)
                @php
                    $isUploaded = in_array($key, $uploadedDocuments);
                    $dokumen = $supervisi->dokumenEvaluasi->where('jenis_dokumen', $key)->first();
                @endphp
                
                <!-- Document Card -->
                <div id="doc-card-{{ $key }}" data-uploaded="{{ $isUploaded ? 'true' : 'false' }}" class="doc-card bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600 {{ $isUploaded ? 'border-l-4 border-l-green-500' : '' }}">
                    <!-- Row 1: Number + Document Name + Status -->
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            <!-- Number Badge -->
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-bold rounded-lg shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <!-- Document Name -->
                            <div class="min-w-0">
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white leading-tight">
                                    {{ Str::beforeLast($label, ' (') }}
                                </h4>
                                @if(Str::contains($label, '('))
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ Str::between($label, '(', ')') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <!-- Status Badge -->
                        @if($isUploaded)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-sm font-semibold rounded-lg shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Sudah
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-lg shrink-0">
                                Belum
                            </span>
                        @endif
                    </div>
                    
                    <!-- Row 2: File Name (if uploaded) -->
                    @if($isUploaded && $dokumen)
                        <div class="flex items-center gap-2 mb-3 px-3 py-2.5 bg-indigo-50 dark:bg-indigo-900/40 border border-indigo-200 dark:border-indigo-700 rounded-lg">
                            <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-indigo-700 dark:text-indigo-200 truncate" title="{{ $dokumen->nama_file }}">
                                {{ $dokumen->nama_file }}
                            </span>
                        </div>
                    @endif
                    
                    <!-- Row 3: Action Buttons -->
                    <div class="flex gap-2">
                        @if($isUploaded && $dokumen)
                            <!-- Preview Button -->
                            <a
                                href="{{ asset('storage/' . $dokumen->path_file) }}"
                                target="_blank"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                            </a>
                            <!-- Ganti Button -->
                            <button
                                type="button"
                                data-upload-btn="{{ $key }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 cursor-pointer transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Ganti
                            </button>
                            <!-- Delete Button -->
                            <form id="delete-form-mobile-{{ $key }}" method="POST" action="{{ route('guru.supervisi.delete-document', [$supervisi->id]) }}" class="contents">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="jenis_dokumen" value="{{ $key }}">
                                <button
                                    type="button"
                                    onclick="confirmDeleteForm('delete-form-mobile-{{ $key }}', 'Apakah Anda yakin ingin menghapus dokumen ini?')"
                                    class="inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors cursor-pointer"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <!-- Upload Button (Full Width) -->
                            <button
                                type="button"
                                data-upload-btn="{{ $key }}"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 cursor-pointer transition-colors shadow-sm"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Upload Dokumen
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ======================= -->
        <!-- DESKTOP: Table Layout (hidden on mobile, visible on md and above) -->
        <!-- ======================= -->
        <div class="hidden md:block overflow-x-auto">
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
                        <tr id="doc-row-{{ $key }}" data-uploaded="{{ $isUploaded ? 'true' : 'false' }}" class="doc-card hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-200 dark:border-gray-700">
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
                                <div class="flex items-center justify-center gap-2">
                                    @if($isUploaded && $dokumen)
                                        <a
                                            href="{{ asset('storage/' . $dokumen->path_file) }}"
                                            target="_blank"
                                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors"
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
                                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        {{ $isUploaded ? 'Ganti' : 'Upload' }}
                                    </button>
                                    @if($isUploaded)
                                        <form id="delete-form-{{ $key }}" method="POST" action="{{ route('guru.supervisi.delete-document', [$supervisi->id]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="jenis_dokumen" value="{{ $key }}">
                                            <button
                                                type="button"
                                                onclick="confirmDeleteForm('delete-form-{{ $key }}', 'Apakah Anda yakin ingin menghapus dokumen ini?')"
                                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer"
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
                                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed"
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
    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-4 sm:px-6 sm:py-4 flex flex-row items-center justify-between gap-3 sm:gap-4">
        <a href="{{ route('guru.home') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 md:px-5 md:py-2.5 min-h-[44px] md:min-h-0 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg md:rounded-lg cursor-pointer transition-colors">
            <svg class="w-5 h-5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>

        <button id="nextButton" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 md:px-6 md:py-2.5 min-h-[44px] md:min-h-0 bg-blue-600 text-white text-sm font-bold rounded-lg md:rounded-lg hover:bg-blue-700 cursor-pointer transition-colors">
            Lanjut
            <svg class="w-5 h-5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </button>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden opacity-0 transition-all duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 max-w-sm mx-4 shadow-2xl transform transition-all duration-300 scale-95" id="successModalContent">
        <!-- Close button -->
        <button onclick="hideSuccessModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div class="text-center">
            <!-- Success Icon - Large circle -->
            <div class="w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
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
        
        // Skip if button doesn't exist
        if (!prosesTabButton) {
            console.log('prosesTabButton not found, skipping...');
            return;
        }
        
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

    // Auto-scroll to first incomplete document (mobile only)
    function scrollToFirstIncomplete() {
        // Only run on mobile view
        const isMobile = window.innerWidth < 768;
        if (!isMobile) {
            return; // Skip auto-scroll on desktop
        }
        
        // Get all document cards/rows
        const docElements = document.querySelectorAll('.doc-card');
        
        for (let elem of docElements) {
            // Check if element is actually visible using offsetParent (works with parent hidden elements)
            // For table rows, check if the table is visible
            const parent = elem.closest('.md\\:hidden') || elem.closest('.hidden.md\\:block') || elem.closest('table');
            let isVisible = false;
            
            if (parent) {
                const parentStyle = window.getComputedStyle(parent);
                isVisible = parentStyle.display !== 'none';
            } else {
                // Direct visibility check
                isVisible = elem.offsetParent !== null || elem.offsetWidth > 0;
            }
            
            if (elem.dataset.uploaded === 'false' && isVisible) {
                setTimeout(() => {
                    elem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Add highlight effect
                    elem.classList.add('ring-2', 'ring-indigo-500', 'ring-offset-2');
                    elem.style.transition = 'all 0.3s ease';
                    setTimeout(() => {
                        elem.classList.remove('ring-2', 'ring-indigo-500', 'ring-offset-2');
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
