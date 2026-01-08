@extends('layouts.modern')

@section('page-title', $title . ' - Review Supervisi')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $title }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola dan tinjau supervisi yang telah disubmit oleh guru</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>

<!-- Filter Tabs -->
<div class="mb-6 border-b border-gray-200 dark:border-gray-700">
    <nav class="-mb-px flex space-x-8">
        <a href="{{ route('admin.supervisi.index', ['status' => 'submitted']) }}" 
           class="@if($status === 'submitted') border-amber-500 text-amber-600 dark:text-amber-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Menunggu Peninjauan
            </div>
        </a>
        
        <a href="{{ route('admin.supervisi.index', ['status' => 'under_review']) }}" 
           class="@if($status === 'under_review') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Sedang Ditinjau
            </div>
        </a>
        
        <a href="{{ route('admin.supervisi.index', ['status' => 'completed']) }}" 
           class="@if($status === 'completed') border-emerald-500 text-emerald-600 dark:text-emerald-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Telah Ditinjau
            </div>
        </a>
    </nav>
</div>

<!-- Supervisi List -->
@if($supervisiList->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    @foreach($supervisiList as $supervisi)
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow duration-200">
        <!-- Status Badge -->
        <div class="h-1 @if($supervisi->status === 'submitted') bg-gradient-to-r from-amber-500 to-orange-500 @elseif($supervisi->status === 'under_review') bg-gradient-to-r from-indigo-500 to-purple-500 @else bg-gradient-to-r from-emerald-500 to-green-500 @endif"></div>
        
        <div class="p-6">
            <!-- Guru Info -->
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    {{ substr($supervisi->user->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $supervisi->user->email }}</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3">
                    <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">Dokumen</div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $supervisi->dokumenEvaluasi->count() }}/7</div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3">
                    <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">Feedback</div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $supervisi->feedback->count() }}</div>
                </div>
            </div>

            <!-- Timestamp -->
            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $supervisi->updated_at->diffForHumans() }}
            </div>

            <!-- Action Button -->
            <a href="{{ route('admin.supervisi.show', $supervisi->id) }}" class="block w-full text-center px-4 py-2 @if($supervisi->status === 'submitted') bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 @elseif($supervisi->status === 'under_review') bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 @else bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 @endif text-white font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                @if($supervisi->status === 'submitted')
                    Mulai Peninjauan
                @elseif($supervisi->status === 'under_review')
                    Lanjutkan Peninjauan
                @else
                    Lihat Detail
                @endif
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $supervisiList->links() }}
</div>

@else
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak Ada Supervisi</h3>
    <p class="text-gray-600 dark:text-gray-400">Belum ada supervisi yang perlu ditinjau saat ini</p>
</div>
@endif

@endsection
