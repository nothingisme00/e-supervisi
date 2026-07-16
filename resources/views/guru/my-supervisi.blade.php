@extends('layouts.modern')

@section('page-title', 'Supervisi Saya')

@section('content')
<div class="w-full lg:w-11/12 xl:w-5/6 mx-auto px-0 sm:px-4 md:px-6 lg:px-8">
    <x-page-header title="Supervisi Saya" subtitle="{{ $mySupervisi->count() }} supervisi">
        <x-slot:actions>
            <x-button variant="secondary" size="sm" onclick="openSupervisiGuideModal()">
                <x-icon name="book-open" class="w-4 h-4" />
                <span class="hidden sm:inline">Panduan</span>
            </x-button>
            <x-button size="sm" onclick="openSupervisiModal()">
                <x-icon name="plus" class="w-4 h-4" />
                <span class="hidden sm:inline">Buat Baru</span>
            </x-button>
        </x-slot:actions>
    </x-page-header>

    @if($mySupervisi->count() > 0)
        @php
            $totalSelesai = $mySupervisi->where('status', 'completed')->count();
            $perluTindakan = $mySupervisi->whereIn('status', ['draft', 'revision'])->count();
            $aktif = $mySupervisi->first(fn ($s) => $s->status !== 'completed');
            $progresAktif = $aktif
                ? round(($aktif->dokumenEvaluasi->count() / 7) * 50 + ($aktif->prosesPembelajaran ? 50 : 0))
                : null;
        @endphp

        <!-- Stat tiles + progress supervisi berjalan (di atas daftar) -->
        <x-card class="p-4 sm:p-5 mb-4 sm:mb-6">
            <div class="grid grid-cols-3 gap-2 sm:gap-4 {{ $progresAktif !== null ? 'mb-4' : '' }}">
                <div class="flex flex-col items-center p-2.5 sm:p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg border border-primary-100 dark:border-primary-800">
                    <x-icon name="document" class="w-5 h-5 text-primary-600 dark:text-primary-400 mb-1" />
                    <div class="text-base sm:text-lg font-bold text-primary-700 dark:text-primary-300 tabular-nums">{{ $mySupervisi->count() }}</div>
                    <div class="text-xs text-primary-600 dark:text-primary-400">Total</div>
                </div>
                <div class="flex flex-col items-center p-2.5 sm:p-3 {{ $perluTindakan > 0 ? 'bg-amber-50 dark:bg-amber-900/20 border-amber-100 dark:border-amber-800' : 'bg-gray-50 dark:bg-gray-700/20 border-gray-200 dark:border-gray-700' }} rounded-lg border">
                    <x-icon name="pencil" class="w-5 h-5 {{ $perluTindakan > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400 dark:text-gray-500' }} mb-1" />
                    <div class="text-base sm:text-lg font-bold {{ $perluTindakan > 0 ? 'text-amber-700 dark:text-amber-300' : 'text-gray-500 dark:text-gray-400' }} tabular-nums">{{ $perluTindakan }}</div>
                    <div class="text-xs {{ $perluTindakan > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400' }}">Perlu Tindakan</div>
                </div>
                <div class="flex flex-col items-center p-2.5 sm:p-3 {{ $totalSelesai > 0 ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800' : 'bg-gray-50 dark:bg-gray-700/20 border-gray-200 dark:border-gray-700' }} rounded-lg border">
                    <x-icon name="check-circle" class="w-5 h-5 {{ $totalSelesai > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500' }} mb-1" />
                    <div class="text-base sm:text-lg font-bold {{ $totalSelesai > 0 ? 'text-emerald-700 dark:text-emerald-300' : 'text-gray-500 dark:text-gray-400' }} tabular-nums">{{ $totalSelesai }}</div>
                    <div class="text-xs {{ $totalSelesai > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400' }}">Selesai</div>
                </div>
            </div>

            @if($progresAktif !== null)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Progress supervisi berjalan ({{ $aktif->tanggal_supervisi ? \Carbon\Carbon::parse($aktif->tanggal_supervisi)->translatedFormat('d M Y') : 'draft' }})</span>
                    <span class="text-xs font-bold text-primary-600 dark:text-primary-400 tabular-nums">{{ $progresAktif }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-300 {{ $progresAktif >= 100 ? 'bg-emerald-500' : 'bg-primary-600' }}" style="width: {{ $progresAktif }}%"></div>
                </div>
            </div>
            @endif
        </x-card>

        <!-- Daftar supervisi: satu bahasa visual dengan beranda -->
        <div class="grid grid-cols-1 gap-3 sm:gap-4">
            @foreach($mySupervisi as $item)
                @if($item->status == 'revision' && $item->revision_notes)
                <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl -mb-1 sm:-mb-2">
                    <div class="flex items-start gap-2">
                        <x-icon name="exclamation-triangle" class="w-4 h-4 text-red-600 dark:text-red-400 mt-0.5 shrink-0" />
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-red-800 dark:text-red-300 mb-1">Catatan Revisi:</p>
                            <p class="text-xs text-red-700 dark:text-red-400">{{ $item->revision_notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
                @include('guru._supervisi-card', ['supervisi' => $item, 'milikSendiri' => true])
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <x-card class="p-6 sm:p-10 md:p-12 text-center min-h-[50vh] flex flex-col items-center justify-center">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-primary-50 dark:bg-primary-900/30 rounded-full flex items-center justify-center mb-5 sm:mb-6">
                <x-icon name="document" class="w-8 h-8 sm:w-10 sm:h-10 text-primary-600 dark:text-primary-400" />
            </div>

            <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Belum Ada Supervisi</h2>
            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mb-6 max-w-xs">Mulai proses supervisi pembelajaran Anda sekarang</p>

            <div class="flex items-center gap-3">
                <x-button variant="secondary" onclick="openSupervisiGuideModal()">
                    <x-icon name="book-open" class="w-4 h-4" />
                    Lihat Panduan
                </x-button>
                <x-button onclick="openSupervisiModal()">
                    <x-icon name="plus" class="w-4 h-4" />
                    Buat Supervisi Baru
                </x-button>
            </div>
        </x-card>
    @endif
</div>

<!-- Modal: Buat Supervisi Baru -->
<div id="supervisiModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center">
                        <x-icon name="plus" class="w-6 h-6 text-white" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Buat Supervisi Baru</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Mulai proses supervisi pembelajaran</p>
                    </div>
                </div>
                <button onclick="closeSupervisiModal()" aria-label="Tutup" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer">
                    <x-icon name="x-mark" class="w-6 h-6" />
                </button>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <div class="flex gap-3">
                    <x-icon name="information-circle" class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" />
                    <div class="text-sm text-blue-800 dark:text-blue-300">
                        <p class="font-medium mb-1">Proses supervisi terdiri dari:</p>
                        <ol class="list-decimal list-inside space-y-1 text-xs">
                            <li>Upload 7 dokumen evaluasi diri</li>
                            <li>Isi data proses pembelajaran</li>
                            <li>Submit untuk ditinjau</li>
                        </ol>
                    </div>
                </div>
            </div>

            <form action="{{ route('guru.supervisi.store') }}" method="POST">
                @csrf
                <div class="flex gap-3">
                    <x-button type="button" variant="secondary" class="flex-1 justify-center" onclick="closeSupervisiModal()">
                        Batal
                    </x-button>
                    <x-button type="submit" class="flex-1 justify-center">
                        Mulai
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openSupervisiModal() {
        const modal = document.getElementById('supervisiModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeSupervisiModal() {
        const modal = document.getElementById('supervisiModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openSupervisiGuideModal() {
        const modal = document.getElementById('supervisiGuideModal');
        const content = document.getElementById('supervisiGuideModalContent');
        modal.style.display = 'flex';
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeSupervisiGuideModal() {
        const modal = document.getElementById('supervisiGuideModal');
        const content = document.getElementById('supervisiGuideModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 200);
    }

    // Close modal on outside click
    document.getElementById('supervisiModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeSupervisiModal();
        }
    });

    // Auto-show panduan bila guru belum punya supervisi (sekali per sesi browser)
    document.addEventListener('DOMContentLoaded', function () {
        const belumAdaSupervisi = {{ $mySupervisi->count() === 0 ? 'true' : 'false' }};
        if (belumAdaSupervisi && !sessionStorage.getItem('panduanSupervisiSudahTampil')) {
            sessionStorage.setItem('panduanSupervisiSudahTampil', '1');
            setTimeout(openSupervisiGuideModal, 800);
        }
    });
</script>

<!-- Panduan Modal with Responsive Content -->
<div id="supervisiGuideModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-[75] items-center justify-center p-4" style="display: none;" onclick="closeSupervisiGuideModal()">
    <div id="supervisiGuideModalContent" class="bg-white dark:bg-gray-800 rounded-[24px] shadow-2xl w-full max-w-[480px] max-h-[80vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0 flex flex-col" onclick="event.stopPropagation()">
        <!-- Aksen atas (warna brand, selaras Guru Guide) -->
        <div class="h-1 bg-primary-600"></div>

        <!-- Header - subtitle berbeda untuk mobile/desktop -->
        <div class="px-6 pt-5 pb-4 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-0.5">Panduan Supervisi</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 hidden md:block">Langkah-langkah di Laptop/Desktop</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 md:hidden">Langkah-langkah di Mobile</p>
            </div>
            <button onclick="closeSupervisiGuideModal()" aria-label="Tutup" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group cursor-pointer">
                <x-icon name="x-mark" class="w-5 h-5 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300" />
            </button>
        </div>

        <div class="px-6 pb-6 flex-1 overflow-y-auto">
            <!-- DESKTOP CONTENT - Hidden on mobile, shown on md and up -->
            <div class="hidden md:block space-y-3">
                <!-- LANGKAH 1 -->
                <div data-guide-step="1" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">1</div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Akses Supervisi Saya</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Klik menu <strong>"Supervisi Saya"</strong> di sidebar kiri untuk masuk ke halaman ini.</p>
                    </div>
                </div>

                <!-- LANGKAH 2 -->
                <div data-guide-step="2" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">2</div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Buat Supervisi Baru</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Klik tombol <strong>"Buat Baru"</strong> di pojok kanan atas, lalu klik <strong>"Mulai"</strong>.</p>
                    </div>
                </div>

                <!-- LANGKAH 3 -->
                <div data-guide-step="3" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">3</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Upload 7 Dokumen</h4>
                            <span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 text-xs font-bold rounded-full">WAJIB</span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Upload CP, ATP, Kalender, Prota, Prosem, Modul Ajar, dan Bahan Ajar (PDF/JPG/PNG, max 2MB).</p>
                    </div>
                </div>

                <!-- LANGKAH 4 -->
                <div data-guide-step="4" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">4</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Isi Proses Pembelajaran</h4>
                            <span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 text-xs font-bold rounded-full">WAJIB</span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Klik tab <strong>"Proses"</strong>, masukkan link video dan jawab 5 pertanyaan refleksi.</p>
                    </div>
                </div>

                <!-- LANGKAH 5 -->
                <div data-guide-step="5" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">5</div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Submit Supervisi</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Klik tombol <strong>"Submit Supervisi"</strong> untuk mengirim ke Kepala Sekolah untuk direview.</p>
                    </div>
                </div>

                <!-- LANGKAH 6 -->
                <div data-guide-step="6" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">6</div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Tunggu Review</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Pantau status supervisi di halaman ini. Lihat feedback dari Kepala Sekolah jika ada.</p>
                    </div>
                </div>
            </div>

            <!-- MOBILE CONTENT - Shown on mobile, hidden on md and up -->
            <div class="md:hidden space-y-3">
                <!-- LANGKAH 1 -->
                <div data-guide-step="1" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">1</div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Buat Supervisi Baru</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Tap menu <strong>"Home"</strong> di bawah, lalu tap tombol <strong>"Mulai Supervisi"</strong> dan isi tanggal.</p>
                    </div>
                </div>

                <!-- LANGKAH 2 -->
                <div data-guide-step="2" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">2</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Upload 7 Dokumen</h4>
                            <span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 text-xs font-bold rounded-full">WAJIB</span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Tap <strong>"Lanjutkan"</strong> di kartu supervisi, lalu upload dokumen satu per satu.</p>
                    </div>
                </div>

                <!-- LANGKAH 3 -->
                <div data-guide-step="3" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">3</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Isi Proses Pembelajaran</h4>
                            <span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 text-xs font-bold rounded-full">WAJIB</span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Tap tab <strong>"Proses"</strong>, masukkan link video dan jawab 5 refleksi.</p>
                    </div>
                </div>

                <!-- LANGKAH 4 -->
                <div data-guide-step="4" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">4</div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Submit Supervisi</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Tap tombol <strong>"Submit"</strong> untuk kirim ke Kepala Sekolah.</p>
                    </div>
                </div>

                <!-- LANGKAH 5 -->
                <div data-guide-step="5" class="flex items-start gap-3 p-4 bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold flex items-center justify-center shrink-0">5</div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Tunggu Review</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Cek status di kartu supervisi. Tap <strong>"Komentar"</strong> untuk melihat feedback.</p>
                    </div>
                </div>
            </div>

            <x-button variant="secondary" class="w-full mt-4 justify-center" onclick="closeSupervisiGuideModal()">
                Tutup
            </x-button>
        </div>
    </div>
</div>

<script>
// Helper function for delete confirmation (dipakai partial _supervisi-card: form id delete-supervisi-{id})
function confirmDeleteSupervisi(supervisiId) {
    showConfirmModal(
        'Apakah Anda yakin ingin menghapus supervisi ini? Data yang dihapus tidak dapat dikembalikan.',
        'Konfirmasi Hapus Supervisi',
        function() {
            document.getElementById('delete-supervisi-' + supervisiId).submit();
        },
        { type: 'danger', confirmText: 'Ya, Hapus' }
    );
}

// Accordion komentar di partial _supervisi-card
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
</script>
@endsection
