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
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Timeline Supervisi</h3>

    @if($supervisiList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            @foreach($supervisiList as $item)
            <div class="bg-white dark:bg-gray-700 border-2 {{ $item->user_id == auth()->id() ? 'border-indigo-400 dark:border-indigo-500' : 'border-gray-200 dark:border-gray-600' }} rounded-lg shadow-sm hover:shadow-md transition-all relative overflow-hidden">
                <!-- Badge di pojok kanan atas -->
                <div class="absolute top-0 right-0">
                    @if($item->user_id == auth()->id())
                        <div class="bg-indigo-600 dark:bg-indigo-500 text-white text-xs font-semibold px-3 py-1 rounded-bl-lg">Milik Saya</div>
                    @else
                        <div class="bg-gray-500 dark:bg-gray-600 text-white text-xs font-medium px-3 py-1 rounded-bl-lg">{{ $item->user->name }}</div>
                    @endif
                </div>

                <div class="p-4 pt-10">
                    <div class="flex items-center gap-3 mb-3">
                        @php
                            // Generate color based on first letter
                            $firstLetter = strtoupper(substr($item->user->name, 0, 1));
                            $colors = [
                                'A' => '#ef4444', // red-500
                                'B' => '#f97316', // orange-500
                                'C' => '#f59e0b', // amber-500
                                'D' => '#eab308', // yellow-500
                                'E' => '#84cc16', // lime-500
                                'F' => '#22c55e', // green-500
                                'G' => '#10b981', // emerald-500
                                'H' => '#14b8a6', // teal-500
                                'I' => '#06b6d4', // cyan-500
                                'J' => '#0ea5e9', // sky-500
                                'K' => '#3b82f6', // blue-500
                                'L' => '#6366f1', // indigo-500
                                'M' => '#8b5cf6', // violet-500
                                'N' => '#a855f7', // purple-500
                                'O' => '#d946ef', // fuchsia-500
                                'P' => '#ec4899', // pink-500
                                'Q' => '#f43f5e', // rose-500
                                'R' => '#dc2626', // red-600
                                'S' => '#ea580c', // orange-600
                                'T' => '#d97706', // amber-600
                                'U' => '#ca8a04', // yellow-600
                                'V' => '#65a30d', // lime-600
                                'W' => '#16a34a', // green-600
                                'X' => '#059669', // emerald-600
                                'Y' => '#0d9488', // teal-600
                                'Z' => '#0891b2', // cyan-600
                            ];
                            $avatarBgColor = $colors[$firstLetter] ?? '#6b7280'; // gray-600 as default
                        @endphp

                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md" style="background-color: {{ $avatarBgColor }};">
                                {{ $firstLetter }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 dark:text-white text-base mb-0.5 truncate">
                                {{ $item->user->name }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-400 text-sm">{{ $item->user->mata_pelajaran }} • {{ $item->user->tingkat }}</div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        @if($item->status == 'draft')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                Draft
                            </span>
                        @elseif($item->status == 'submitted')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                Disubmit
                            </span>
                        @elseif($item->status == 'under_review')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                Sedang Direview
                            </span>
                        @elseif($item->status == 'completed')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Selesai
                            </span>
                        @elseif($item->status == 'revision')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                Perlu Revisi
                            </span>
                        @endif

                        @php
                            $docCount = $item->dokumenEvaluasi->count();
                            $hasProses = $item->prosesPembelajaran != null;
                        @endphp

                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 text-xs font-semibold rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ $docCount }}/7
                        </span>

                        @if($hasProses)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Proses
                            </span>
                        @endif

                        @if($item->feedback->count() > 0)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                {{ $item->feedback->count() }}
                            </span>
                        @endif
                    </div>

                    <div class="pt-3 border-t-2 border-gray-200 dark:border-gray-600 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($item->tanggal_supervisi)->format('d M Y') }}
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            @if($item->user_id == auth()->id())
                                @if(in_array($item->status, ['draft', 'revision']))
                                    <button
                                        onclick="deleteSupervisi({{ $item->id }})"
                                        style="background-color: #dc2626; color: white;"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 hover:opacity-90 text-xs font-semibold rounded-lg transition-all shadow-sm"
                                        title="Hapus supervisi"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                @endif

                                @if($item->status == 'draft')
                                    <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                        Lanjutkan
                                    </a>
                                @elseif($item->status == 'revision')
                                    <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Revisi
                                    </a>
                                @else
                                    <a href="{{ route('guru.supervisi.detail', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('guru.supervisi.detail', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Belum ada supervisi</p>
            <a href="{{ route('guru.supervisi.create') }}" class="inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors">
                Mulai Supervisi Baru
            </a>
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

    // Delete supervisi function
    async function deleteSupervisi(supervisiId) {
        if (!confirm('Apakah Anda yakin ingin menghapus supervisi ini? Semua data termasuk dokumen yang telah diupload akan dihapus secara permanen.')) {
            return;
        }

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
                alert(result.message);
                window.location.reload();
            } else {
                alert(result.message || 'Gagal menghapus supervisi');
            }
        } catch (error) {
            alert('Terjadi kesalahan: ' + error.message);
        }
    }
</script>
@endsection
