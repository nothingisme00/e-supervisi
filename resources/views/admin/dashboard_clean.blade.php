@extends('layouts.modern')

@section('page-title', 'Dashboard Admin')

@section('content')

<!-- Header Section -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Dashboard Admin</h1>
    <p class="text-gray-600 dark:text-gray-400">Selamat datang di panel administrator</p>
</div>

<!-- Statistics Cards - Clean & Minimalist -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Total Pengguna -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Pengguna</span>
            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</div>
    </div>

    <!-- Perlu Review -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-amber-300 dark:hover:border-amber-600 transition-all">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Perlu Review</span>
            <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supervisiSubmitted }}</div>
    </div>

    <!-- Sedang Review -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Sedang Review</span>
            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supervisiUnderReview }}</div>
    </div>

    <!-- Selesai -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-600 transition-all">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Selesai</span>
            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supervisiCompleted }}</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('admin.users.index') }}" class="flex-1 inline-flex items-center justify-center px-5 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-xl border border-gray-300 dark:border-gray-600 transition-all shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Kelola Pengguna
        </a>
        <a href="{{ route('admin.users.create') }}" class="flex-1 inline-flex items-center justify-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-all shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah User Baru
        </a>
        <button onclick="openGuideModal()" class="flex-1 inline-flex items-center justify-center px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Panduan Admin
        </button>
    </div>
</div>

<!-- Content Grid - Clean Layout -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    
    <!-- Data Guru - Simplified -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Data Guru</h2>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">{{ $totalGuru }} guru</span>
        </div>
        
        <div class="space-y-3 max-h-[600px] overflow-y-auto">
            @forelse($guruList as $guru)
            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold shrink-0">
                    {{ strtoupper(substr($guru->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-gray-900 dark:text-white truncate">{{ $guru->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2 mt-0.5">
                        <span>{{ $guru->nik }}</span>
                        @if($guru->last_login_at)
                        <span>•</span>
                        <span class="truncate">{{ $guru->last_login_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
                <div class="shrink-0 text-right">
                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $guru->total_supervisi }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">supervisi</div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-sm">Belum ada data guru</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Perlu Review & Sedang Review - Combined -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Dalam Proses</h2>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">{{ $supervisiUnderReviewList->count() }} item</span>
        </div>
        
        <div class="space-y-3 max-h-[600px] overflow-y-auto">
            @forelse($supervisiUnderReviewList->sortByDesc('created_at') as $supervisi)
            <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-900 dark:text-white truncate">{{ $supervisi->guru->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $supervisi->mapel }}</div>
                    </div>
                    @if($supervisi->status == 'submitted')
                    <span class="px-2 py-1 text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-md">Perlu Review</span>
                    @else
                    <span class="px-2 py-1 text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-md">Sedang Review</span>
                    @endif
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $supervisi->created_at->format('d M Y') }}
                    @if($supervisi->kepala_sekolah_id)
                    • Reviewer: {{ $supervisi->kepala_sekolah->name ?? '-' }}
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm">Tidak ada supervisi dalam proses</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Supervisi Selesai - Simplified -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Selesai</h2>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">{{ $supervisiCompletedList->count() }} selesai</span>
        </div>
        
        <div class="space-y-3 max-h-[600px] overflow-y-auto">
            @forelse($supervisiCompletedList as $supervisi)
            <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-900 dark:text-white truncate">{{ $supervisi->guru->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $supervisi->mapel }}</div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-md">Selesai</span>
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $supervisi->created_at->format('d M Y') }}
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm">Belum ada supervisi selesai</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Admin Guide Modal -->
<div id="guideModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Panduan Administrator</h3>
            <button onclick="closeGuideModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-6 py-4 overflow-y-auto max-h-[calc(90vh-8rem)]">
            <div class="space-y-4">
                @php
                $guides = [
                    ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'title' => 'Kelola Pengguna', 'desc' => 'Tambah, edit, atau hapus data pengguna (admin, kepala sekolah, dan guru)'],
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Monitor Supervisi', 'desc' => 'Pantau status dan progress supervisi yang sedang berjalan'],
                    ['icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Review Dokumen', 'desc' => 'Periksa dan validasi dokumen supervisi yang diajukan guru'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Lihat Statistik', 'desc' => 'Analisis data dan laporan aktivitas supervisi'],
                    ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'title' => 'Pengaturan Sistem', 'desc' => 'Konfigurasi sistem dan preferensi aplikasi'],
                    ['icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Bantuan & Support', 'desc' => 'Hubungi tim support jika mengalami kendala']
                ];
                @endphp
                
                @foreach($guides as $index => $guide)
                <div class="flex gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $guide['icon'] }}"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $index + 1 }}. {{ $guide['title'] }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $guide['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <button onclick="closeGuideModal()" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
function openGuideModal() {
    document.getElementById('guideModal').classList.remove('hidden');
}

function closeGuideModal() {
    document.getElementById('guideModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('guideModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeGuideModal();
    }
});
</script>

@endsection
