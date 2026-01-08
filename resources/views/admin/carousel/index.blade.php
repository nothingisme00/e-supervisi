@extends('layouts.modern')

@section('content')
@php
    $totalSlides = $slides->count();
    $activeSlides = $slides->where('is_active', true)->count();
@endphp
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Kelola Carousel</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Atur gambar dan teks yang tampil di halaman login & dashboard guru
                    <span class="inline-flex items-center gap-1 ml-2 text-xs font-medium text-indigo-600 dark:text-indigo-400">
                        ({{ $totalSlides }} slide, {{ $activeSlides }} aktif)
                    </span>
                </p>
            </div>
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Slide</span>
            </button>
        </div>
    </div>

    <!-- Slides List -->
    <div class="grid gap-4">
        @forelse($slides as $index => $slide)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
            <div class="flex flex-col sm:flex-row">
                <!-- Image Preview -->
                <div class="sm:w-48 h-32 sm:h-auto bg-gradient-to-br from-teal-600 to-teal-800 flex-shrink-0">
                    @if($slide->image_path)
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-white/50">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="flex-1 p-4 sm:p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $slide->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Urutan: {{ $index + 1 }} dari {{ $totalSlides }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $slide->title ?: 'Tanpa Judul' }}</h3>
                            @if($slide->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $slide->description }}</p>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            <form action="{{ route('admin.carousel.toggle', $slide) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-2 rounded-lg {{ $slide->is_active ? 'text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20' : 'text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors" title="{{ $slide->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </form>
                            <button onclick="openEditModal({{ json_encode($slide) }})" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="confirmDelete({{ $slide->id }})" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada slide</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Tambahkan slide untuk ditampilkan di halaman login</p>
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Slide Pertama
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="slideModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[70] hidden items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden transform transition-all scale-100">
        <!-- Header (outside form) -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex-shrink-0">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900 dark:text-white">Tambah Slide Baru</h3>
                <span id="modalCloseBtn" onclick="window.carouselCloseModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors cursor-pointer">
                    <svg class="w-5 h-5 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </span>
            </div>
        </div>
        
        <form id="slideForm" method="POST" enctype="multipart/form-data" class="flex flex-col">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <!-- Body (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                <!-- Error Messages -->
                @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-red-800 dark:text-red-300 mb-1">Terjadi kesalahan:</h4>
                            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400 space-y-1">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Judul</label>
                    <input type="text" name="title" id="slideTitle" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" 
                        placeholder="Contoh: Meningkatkan Kualitas Pendidikan">
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="description" id="slideDescription" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none" 
                        placeholder="Deskripsi singkat untuk slide ini..."></textarea>
                </div>
                
                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Gambar</label>
                    <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-indigo-400 transition-colors bg-gray-50 dark:bg-gray-700/50">
                        <input type="file" name="image" id="slideImage" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(this)">
                        
                        <div id="imagePlaceholder" class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Klik untuk upload gambar</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">PNG, JPG, WEBP (max 2MB)</p>
                        </div>

                        <div id="imagePreview" class="hidden relative z-20">
                            <img id="previewImg" src="" alt="Preview" class="max-h-48 rounded-lg mx-auto shadow-md">
                            <button type="button" onclick="resetImage()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Active Toggle -->
                <div class="flex items-center gap-3 pt-2">
                    <div class="relative inline-block w-9 h-4">
                        <input checked id="slideActive" name="is_active" value="1" type="checkbox" class="peer appearance-none w-9 h-4 bg-gray-300 dark:bg-gray-600 rounded-full checked:bg-indigo-500 cursor-pointer transition-colors duration-200" />
                        <label for="slideActive" class="absolute top-0 left-0 w-4 h-4 bg-white rounded-full border border-gray-200 dark:border-gray-500 shadow-sm transition-transform duration-200 peer-checked:translate-x-5 peer-checked:border-indigo-400 cursor-pointer"></label>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Aktifkan slide</span>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex-shrink-0 flex gap-3">
                <span id="modalCancelBtn" onclick="window.carouselCloseModal()" class="flex-1 px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all shadow-sm text-center cursor-pointer select-none">Batal</span>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-all shadow-md shadow-indigo-500/20">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    // Base Route URL passed from PHP to JS
    const carouselBaseUrl = '{{ url("admin/carousel") }}';

    // Expose closeModal to window object for inline onclick
    window.carouselCloseModal = function() {
        const modal = document.getElementById('slideModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    };

    function openAddModal() {
        console.log('Opening add modal');
        document.getElementById('modalTitle').textContent = 'Tambah Slide Baru';
        // Set action for creating new
        const form = document.getElementById('slideForm');
        form.action = carouselBaseUrl; // POST to /admin/carousel
        console.log('Form action set to:', form.action);
        
        // Reset Method to POST
        const methodInput = document.getElementById('formMethod');
        if (methodInput) methodInput.value = 'POST';

        // Reset Inputs
        document.getElementById('slideTitle').value = '';
        document.getElementById('slideDescription').value = '';
        document.getElementById('slideImage').value = ''; // Reset file input
        document.getElementById('slideActive').checked = true;
        
         // Reset Image Preview
        resetImageUI();
        
        showModal();
    }
    
    function openEditModal(slide) {
        document.getElementById('modalTitle').textContent = 'Edit Slide';
        
        // Set action for updating: /admin/carousel/{id}
        document.getElementById('slideForm').action = `${carouselBaseUrl}/${slide.id}`;
        
        // Set Method to PUT
        const methodInput = document.getElementById('formMethod');
        if (methodInput) methodInput.value = 'PUT';

        // Fill Inputs
        document.getElementById('slideTitle').value = slide.title || '';
        document.getElementById('slideDescription').value = slide.description || '';
        document.getElementById('slideActive').checked = slide.is_active == 1;
        document.getElementById('slideImage').value = ''; // Clear any previous file selection
        
        // Handle Image Preview
        if (slide.image_url) {
            document.getElementById('previewImg').src = slide.image_url;
            document.getElementById('imagePlaceholder').classList.add('hidden');
            document.getElementById('imagePreview').classList.remove('hidden');
        } else {
            resetImageUI();
        }
        
        showModal();
    }
    
    function showModal() {
        const modal = document.getElementById('slideModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        window.carouselCloseModal();
    }
    
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePlaceholder').classList.add('hidden');
                document.getElementById('imagePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function resetImage() {
        document.getElementById('slideImage').value = '';
        resetImageUI();
    }

    function resetImageUI() {
        document.getElementById('previewImg').src = '';
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('imagePlaceholder').classList.remove('hidden');
    }
    
    function confirmDelete(id) {
        showConfirmModal(
            'Apakah Anda yakin ingin menghapus slide ini?',
            'Konfirmasi Hapus Slide',
            function() {
                const form = document.getElementById('deleteForm');
                form.action = `${carouselBaseUrl}/${id}`;
                form.submit();
            },
            { type: 'danger', confirmText: 'Ya, Hapus' }
        );
    }
    
    // Modal backdrop click to close
    (function() {
        const modal = document.getElementById('slideModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    window.carouselCloseModal();
                }
            });
        }
        
        // Add form submit listener for debugging
        const form = document.getElementById('slideForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form submitting...');
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);
                console.log('Form data:', new FormData(form));
                
                // Check if image is selected
                const imageInput = document.getElementById('slideImage');
                if (imageInput && imageInput.files.length > 0) {
                    console.log('Image selected:', imageInput.files[0].name);
                } else {
                    console.log('No image selected');
                }
            });
        }
    })();
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.carouselCloseModal();
        }
    });
    
    // Auto-open modal if there are validation errors
    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openAddModal();
        });
    @endif
</script>
@endsection
