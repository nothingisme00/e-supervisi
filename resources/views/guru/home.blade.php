@extends('layouts.modern')

@section('page-title', 'Beranda')

@section('content')
<!-- Header Card -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700 text-white rounded-lg p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p class="text-sm text-indigo-100 dark:text-indigo-200">{{ auth()->user()->mata_pelajaran }} • {{ auth()->user()->tingkat }}</p>
        </div>
        <div>
            <a href="{{ route('guru.supervisi.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-gray-100 text-indigo-600 font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Supervisi Baru
            </a>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Daftar Supervisi</h3>

    @if($supervisiList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            @foreach($supervisiList as $item)
            <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-600 dark:bg-indigo-500 rounded-full flex items-center justify-center text-white text-base font-semibold flex-shrink-0">
                        {{ substr($item->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900 dark:text-white text-sm mb-0.5 truncate">{{ $item->user->name }}</div>
                        <div class="text-gray-600 dark:text-gray-400 text-xs">{{ $item->user->mata_pelajaran }} • {{ $item->user->tingkat }}</div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mb-3">
                    @if($item->status == 'draft')
                        <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300 text-xs font-medium rounded-full">Draft</span>
                    @elseif($item->status == 'submitted')
                        <span class="inline-block px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs font-medium rounded-full">Disubmit</span>
                    @elseif($item->status == 'under_review')
                        <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-medium rounded-full">Sedang Direview</span>
                    @elseif($item->status == 'completed')
                        <span class="inline-block px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-medium rounded-full">Selesai</span>
                    @elseif($item->status == 'revision')
                        <span class="inline-block px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 text-xs font-medium rounded-full">Perlu Revisi</span>
                    @endif

                    @php
                        $docCount = $item->dokumenEvaluasi->count();
                        $hasProses = $item->prosesPembelajaran != null;
                    @endphp

                    <span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 text-xs font-medium rounded-full">
                        Dokumen: {{ $docCount }}/7
                    </span>

                    @if($hasProses)
                        <span class="inline-block px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-medium rounded-full">
                            Proses ✓
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                            Proses -
                        </span>
                    @endif

                    @if($item->feedback->count() > 0)
                        <span class="inline-block px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs font-medium rounded-full">{{ $item->feedback->count() }} Feedback</span>
                    @endif
                </div>

                <div class="pt-3 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
                    <div class="text-gray-600 dark:text-gray-400 text-xs">
                        {{ \Carbon\Carbon::parse($item->tanggal_supervisi)->format('d M Y') }}
                    </div>

                    @if($item->user_id == auth()->id())
                        @if($item->status == 'draft')
                            <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs font-medium rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                                Lanjutkan
                            </a>
                        @elseif($item->status == 'revision')
                            <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white text-xs font-medium rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Revisi
                            </a>
                        @else
                            <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 text-white text-xs font-medium rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Lihat
                            </a>
                        @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Belum ada supervisi</p>
            @if(!$activeSupervisi)
                <a href="{{ route('guru.supervisi.create') }}" class="inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors">
                    Mulai Supervisi Baru
                </a>
            @endif
        </div>
    @endif
</div>

<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Tips</h3>
    <ul class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed space-y-2 list-disc list-inside">
        <li>Pastikan semua dokumen evaluasi sudah lengkap sebelum submit</li>
        <li>Upload video pembelajaran dengan kualitas yang baik</li>
        <li>Isi refleksi pembelajaran dengan detail dan jujur</li>
        <li>Periksa kembali semua data sebelum mengirim supervisi</li>
    </ul>
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
</script>
@endsection
