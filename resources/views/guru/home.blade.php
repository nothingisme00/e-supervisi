@extends('layouts.modern')

@section('page-title', 'Beranda')

@section('content')
<div class="w-full lg:w-11/12 xl:w-5/6 mx-auto px-0 sm:px-3 md:px-6 lg:px-8">
    <x-page-header title="Beranda" subtitle="Ringkasan supervisi Anda dan rekan sejawat" />

    <!-- Hero Carousel Section -->
    @if(isset($carouselSlides) && $carouselSlides->count() > 0)
    <div class="mb-3 sm:mb-4 md:mb-6">
        <div class="guru-carousel-container relative w-full h-32 sm:h-44 md:h-56 lg:h-64 rounded-lg sm:rounded-xl md:rounded-2xl overflow-hidden shadow-md sm:shadow-lg">
            <!-- Carousel Inner -->
            <div class="guru-carousel-inner flex w-full h-full transition-transform duration-700 ease-out">
                @foreach($carouselSlides as $index => $slide)
                <div class="guru-carousel-slide flex-shrink-0 w-full h-full relative">
                    @if($slide->image_path)
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    @else
                        <div class="w-full h-full bg-primary-600 flex items-center justify-center">
                            <x-icon name="photo" class="w-12 h-12 sm:w-16 sm:h-16 text-white/30" />
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Outer Container: Timeline Supervisi - Container + Inner Cards Architecture -->
    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg md:rounded-xl lg:rounded-2xl p-1.5 sm:p-3 md:p-5 lg:p-6 mb-2 sm:mb-3 md:mb-4 lg:mb-6 min-h-[60vh] {{ $supervisiList->count() == 0 ? 'flex items-center justify-center' : '' }}">
        <!-- Cards Wrapper with flex column and gap -->
        <div class="flex flex-col gap-1.5 sm:gap-3 md:gap-4 w-full">

            <!-- Tips & Informasi - Hidden div for desktop accordion (shown via JS on tablet/desktop) -->
            @if($supervisiList->count() > 0)
            <x-card id="tips-content" class="hidden md:block overflow-hidden transition-all duration-300 ease-in-out rounded-xl" style="max-height: 0; opacity: 0;">
                <div class="p-3 sm:p-4 md:p-5">
                    <div class="flex items-center gap-2 mb-3 sm:mb-4">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                            <x-icon name="information-circle" class="w-4 h-4 sm:w-5 sm:h-5 text-white" />
                        </div>
                        <h4 class="text-sm sm:text-base md:text-lg font-bold text-gray-900 dark:text-white">Tips & Informasi</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Tip 1: Navigasi Cepat -->
                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 border border-blue-100 dark:border-blue-900/30">
                            <div class="flex items-start gap-2">
                                <x-icon name="arrow-right" class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" />
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-1">Navigasi Cepat</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Gunakan tombol "Panduan" untuk melihat langkah lengkap supervisi</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tip 2: Track Progress -->
                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 border border-emerald-100 dark:border-emerald-900/30">
                            <div class="flex items-start gap-2">
                                <x-icon name="clipboard-check" class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" />
                                <div>
                                    <p class="text-sm font-semibold text-emerald-900 dark:text-emerald-300 mb-1">Lacak Status</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Lihat badge status supervisi: Draft, Disubmit, Ditinjau, Revisi, atau Selesai</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tip 3: Collaboration -->
                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 border border-primary-100 dark:border-primary-900/30">
                            <div class="flex items-start gap-2">
                                <x-icon name="chat-bubble" class="w-5 h-5 text-primary-600 dark:text-primary-400 shrink-0 mt-0.5" />
                                <div>
                                    <p class="text-sm font-semibold text-primary-900 dark:text-primary-300 mb-1">Kolaborasi</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Klik "Komentar" untuk melihat feedback dari Kepala Sekolah</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
            @endif

            <!-- Inner Card 2: Timeline Content Cards -->
            @if($supervisiList->count() > 0)
                <x-card class="w-full">
                    <div class="px-2 py-2 sm:px-3 sm:py-2.5 md:px-5 md:py-4 lg:px-6 lg:py-5">
                        <div class="space-y-2 sm:space-y-3 md:space-y-4">
                @foreach($supervisiList as $item)
                    @include('guru._supervisi-card', ['supervisi' => $item, 'milikSendiri' => $item->user_id == auth()->id()])
                @endforeach
                        </div>
                        <!-- End space-y-4 -->
                        
                        <!-- Pagination Links -->
                        @if($supervisiList->hasPages())
                        <div class="mt-6 px-2">
                            {{ $supervisiList->links() }}
                        </div>
                        @endif
                    </div>
                    <!-- End padding wrapper -->
                </x-card>
                <!-- End Inner Card 2: Timeline Content -->
            @else
                <!-- Inner Card 2: Empty State -->
                <x-card class="w-full">
                    <div class="px-3 py-4 sm:px-5 sm:py-6 md:px-6 md:py-8 lg:px-8 lg:py-10">
                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl sm:rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-6 sm:p-10 md:p-14 lg:p-16 text-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-5 md:mb-6 shadow-inner">
                                <x-icon name="document" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 text-primary-600 dark:text-primary-400" />
                            </div>
                            <h3 class="text-base sm:text-lg md:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-3">Belum Ada Supervisi</h3>
                            <p class="text-sm sm:text-base md:text-base text-gray-500 dark:text-gray-400 mb-5 sm:mb-6 md:mb-8 max-w-md mx-auto leading-relaxed">Anda belum memiliki supervisi apapun. Mulai dengan membuat supervisi baru.</p>

                            <x-button type="button" onclick="openSupervisiModal()" class="shadow-lg hover:shadow-xl">
                                <x-icon name="plus" class="w-5 h-5 sm:w-5 sm:h-5 md:w-6 md:h-6" />
                                Buat Supervisi Baru
                            </x-button>
                        </div>
                    </div>
                </x-card>
                <!-- End Inner Card 2: Empty State -->
            @endif
        </div>
        <!-- End Cards Wrapper -->
    </div>
    <!-- End Outer Container -->
</div>

<!-- Welcome Onboarding Modal (First Time User) -->
<div id="welcomeModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[80] items-center justify-center p-2 sm:p-3 md:p-4 opacity-0 transition-opacity duration-500" style="display: none;" onclick="closeWelcomeModal()">
    <div id="welcomeModalContent" class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl md:rounded-2xl shadow-2xl w-[92%] sm:w-[85%] md:max-w-lg transform scale-90 opacity-0 transition-all duration-500" onclick="event.stopPropagation()">
        <!-- Modal Content -->
        <div class="relative overflow-hidden">
            <div class="relative px-4 py-5 sm:px-5 sm:py-6 md:px-6 md:py-7">
                <!-- Icon -->
                <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-primary-600 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg">
                    <x-icon name="book-open" class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-white" />
                </div>

                <!-- Title -->
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 dark:text-white text-center mb-2 sm:mb-2.5 md:mb-3">
                    Selamat Datang! 👋
                </h3>

                <!-- Description -->
                <p class="text-sm sm:text-base md:text-lg text-gray-600 dark:text-gray-300 text-center mb-3 sm:mb-4 md:mb-5 leading-relaxed">
                    Belum ada supervisi. <strong>Baca panduan</strong> untuk memulai dengan baik.
                </p>

                <!-- Benefits List - Simplified to 2 items -->
                <div class="space-y-2 sm:space-y-2.5 mb-4 sm:mb-5 bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 sm:p-3.5 md:p-4">
                    <div class="flex items-start gap-2.5 sm:gap-3">
                        <x-icon name="check" class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 dark:text-green-400 shrink-0 mt-0.5" />
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300">Pahami alur proses supervisi</p>
                    </div>
                    <div class="flex items-start gap-2.5 sm:gap-3">
                        <x-icon name="check" class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 dark:text-green-400 shrink-0 mt-0.5" />
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300">Ketahui dokumen yang diperlukan</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-2.5 sm:gap-3">
                    <x-button type="button" onclick="openGuideFromWelcome()" class="w-full text-sm sm:text-base md:text-lg">
                        <x-icon name="book-open" class="w-4.5 h-4.5 sm:w-5 sm:h-5" />
                        Baca Panduan Sekarang
                    </x-button>

                    <x-button type="button" variant="secondary" onclick="closeWelcomeModal()" class="w-full text-sm sm:text-base md:text-lg">
                        Nanti Saja
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown hover effect (border color only)
        const allSelects = document.querySelectorAll('select');
        allSelects.forEach(select => {
            select.addEventListener('mouseenter', function() {
                if (document.activeElement !== this) {
                    this.style.borderColor = '#818cf8';
                }
            });
            select.addEventListener('mouseleave', function() {
                if (document.activeElement !== this) {
                    this.style.borderColor = '';
                }
            });
        });
    });

    // Toggle Tips & Informasi Accordion
    function toggleTips() {
        const content = document.getElementById('tips-content');
        const chevron = document.getElementById('tips-chevron');

        if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
            // Open
            content.style.maxHeight = content.scrollHeight + 'px';
            content.style.opacity = '1';
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        } else {
            // Close
            content.style.maxHeight = '0px';
            content.style.opacity = '0';
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    }

    // Delete supervisi function with modal confirmation
    function confirmDeleteSupervisi(supervisiId) {
        showConfirmModal(
            'Apakah Anda yakin ingin menghapus supervisi ini? Semua data termasuk dokumen yang telah diupload akan dihapus secara permanen.',
            'Konfirmasi Hapus Supervisi',
            function() {
                document.getElementById('delete-supervisi-' + supervisiId).submit();
            }
        );
    }

    // Delete supervisi function (async version - if still used)
    async function deleteSupervisi(supervisiId) {
        showConfirmModal(
            'Apakah Anda yakin ingin menghapus supervisi ini? Semua data termasuk dokumen yang telah diupload akan dihapus secara permanen.',
            'Konfirmasi Hapus Supervisi',
            async function() {
                try {
                    const response = await fetch(`/guru/supervisi/${supervisiId}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        showToast(result.message, 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showToast(result.message || 'Gagal menghapus supervisi', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan: ' + error.message, 'error');
                }
            }
        );
    }

    // Welcome Modal Functions
    function showWelcomeModal() {
        const modal = document.getElementById('welcomeModal');
        const content = document.getElementById('welcomeModalContent');

        modal.style.display = 'flex';

        // Trigger reflow
        modal.offsetHeight;

        // Start animation
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-90', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeWelcomeModal() {
        const modal = document.getElementById('welcomeModal');
        const content = document.getElementById('welcomeModalContent');

        // Animate out
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-90', 'opacity-0');

        setTimeout(() => {
            modal.style.display = 'none';
        }, 500);
    }

    function openGuideFromWelcome() {
        // Close welcome modal first
        const welcomeModal = document.getElementById('welcomeModal');
        const welcomeContent = document.getElementById('welcomeModalContent');

        welcomeModal.classList.remove('opacity-100');
        welcomeModal.classList.add('opacity-0');
        welcomeContent.classList.remove('scale-100', 'opacity-100');
        welcomeContent.classList.add('scale-90', 'opacity-0');

        setTimeout(() => {
            welcomeModal.style.display = 'none';
            // Open guide modal (global function in layouts.modern)
            openGuideModal();
        }, 500);
    }


    // Check if user should see welcome modal on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Always show if user has no supervisions (regardless of whether they've seen it before)
        const hasSupervisions = {{ $supervisiList->count() > 0 ? 'true' : 'false' }};

        if (!hasSupervisions) {
            // Show welcome modal after a brief delay for better UX
            setTimeout(() => {
                showWelcomeModal();
            }, 800);
        }
    });

    // Toggle Comments Accordion with smooth animation
    function toggleComments(id) {
        const commentsDiv = document.getElementById('comments-' + id);
        const chevron = document.getElementById('chevron-' + id);
        
        if (commentsDiv.style.maxHeight === '0px' || commentsDiv.style.maxHeight === '') {
            // Open
            commentsDiv.style.maxHeight = commentsDiv.scrollHeight + 'px';
            commentsDiv.style.opacity = '1';
            chevron.style.transform = 'rotate(180deg)';
        } else {
            // Close
            commentsDiv.style.maxHeight = '0px';
            commentsDiv.style.opacity = '0';
            chevron.style.transform = 'rotate(0deg)';
        }
    }

    // Supervisi Confirmation Modal Functions
    function openSupervisiModal() {
        const modal = document.getElementById('supervisiModal');
        const modalContent = document.getElementById('supervisiModalContent');

        modal.style.display = 'flex';

        // Trigger animation after a brief delay
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');

            modalContent.classList.remove('scale-90', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeSupervisiModal() {
        const modal = document.getElementById('supervisiModal');
        const modalContent = document.getElementById('supervisiModalContent');

        // Animate out
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-90', 'opacity-0');

        // Hide after animation
        setTimeout(() => {
            modal.style.display = 'none';
        }, 500);
    }

    // Submit form supervisi
    function submitSupervisiForm() {
        document.getElementById('supervisiForm').submit();
    }

    // Close modal on ESC key for supervisi modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('supervisiModal').style.display === 'flex') {
            closeSupervisiModal();
        }
    });
</script>

<!-- Modal Konfirmasi Supervisi -->
<div id="supervisiModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-[90] flex items-center justify-center p-4 opacity-0 transition-opacity duration-500" style="display: none;" onclick="if(event.target === this) closeSupervisiModal()">
    <div id="supervisiModalContent" class="bg-white dark:bg-gray-800 rounded-[24px] shadow-2xl w-full max-w-md transform scale-90 opacity-0 transition-all duration-500 overflow-hidden" onclick="event.stopPropagation()">

        <!-- Header -->
        <div class="bg-primary-600 dark:bg-primary-700 px-6 py-6 text-center relative">
            <button onclick="closeSupervisiModal()" class="absolute top-4 right-4 w-9 h-9 rounded-xl hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                <x-icon name="x-mark" class="w-5 h-5" />
            </button>
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <x-icon name="check-circle" class="w-8 h-8 text-white" />
            </div>
            <h2 class="text-xl font-bold text-white mb-2">Mulai Supervisi Baru?</h2>
            <p class="text-primary-100 text-sm">
                Tanggal supervisi tercatat saat submit
            </p>
        </div>

        <!-- Body Content -->
        <div class="p-6">
            <!-- Yang Perlu Disiapkan -->
            <div class="mb-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Yang Perlu Disiapkan:</h3>
                <div class="space-y-2.5">
                    <div class="flex items-start gap-3 p-3.5 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0 mt-0.5">
                            <x-icon name="check" class="w-3 h-3 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">7 Dokumen</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">(RPP, Silabus, dll)</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3.5 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                        <div class="w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0 mt-0.5">
                            <x-icon name="check" class="w-3 h-3 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Video & Refleksi</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Dokumentasi pembelajaran</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3.5 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                        <div class="w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center shrink-0 mt-0.5">
                            <x-icon name="check" class="w-3 h-3 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Info Pembelajaran</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Detail proses mengajar</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alur Proses -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 mb-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-2 mb-2.5">
                    <x-icon name="information-circle" class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0" />
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-200">Alur:</p>
                </div>
                <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800 dark:text-blue-300 ml-6">
                    <li>Upload dokumen</li>
                    <li>Isi info & video</li>
                    <li>Submit review</li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <form id="supervisiForm" action="{{ route('guru.supervisi.store') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <div class="flex gap-3">
                <x-button type="button" variant="secondary" onclick="closeSupervisiModal()" class="flex-1">
                    Batal
                </x-button>
                <x-button type="button" onclick="submitSupervisiForm()" class="flex-1 shadow-lg hover:shadow-xl">
                    Mulai
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-button>
            </div>
        </div>

    </div>
</div>

<!-- Guru Carousel Script -->
<script>
(function() {
    const carouselInner = document.querySelector('.guru-carousel-inner');
    const slides = document.querySelectorAll('.guru-carousel-slide');
    
    if (!carouselInner || slides.length <= 1) return;
    
    let currentSlide = 0;
    const SLIDE_DURATION = 4000; // 4 seconds
    
    function showSlide(index) {
        if (index >= slides.length) index = 0;
        currentSlide = index;
        carouselInner.style.transform = `translateX(-${index * 100}%)`;
    }
    
    function nextSlide() {
        showSlide(currentSlide + 1);
    }
    
    // Start auto-sliding
    setInterval(nextSlide, SLIDE_DURATION);
})();
</script>

@endsection
