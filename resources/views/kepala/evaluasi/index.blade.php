@extends('layouts.modern')

@section('page-title', 'Evaluasi Supervisi')

@section('content')
<x-page-header
    title="Evaluasi Supervisi"
    subtitle="Kelola dan evaluasi supervisi pembelajaran guru"
    :back-url="route('kepala.dashboard')" />

<!-- Filter & Search Section -->
<x-card class="p-3 sm:p-5 mb-4 sm:mb-6">
    <form method="GET" action="{{ route('kepala.evaluasi.index') }}" class="flex flex-col sm:flex-row gap-2 sm:gap-4">
        <!-- Search Input -->
        <div class="flex-1">
            <div class="relative">
                <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" />
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru..." class="form-control pl-10 py-2.5">
            </div>
        </div>

        <!-- Status Filter -->
        <div class="w-full sm:w-56">
            <x-custom-dropdown
                name="status"
                :value="request('status', '')"
                placeholder="Semua Status"
                :options="[
                    ['value' => '', 'label' => 'Semua Status'],
                    ['value' => 'submitted', 'label' => 'Perlu Review'],
                    ['value' => 'under_review', 'label' => 'Sedang Ditinjau'],
                    ['value' => 'revision', 'label' => 'Perlu Revisi'],
                    ['value' => 'completed', 'label' => 'Selesai'],
                ]" />
        </div>

        <!-- Filter Button -->
        <x-button type="submit">
            <x-icon name="funnel" class="w-4 h-4" />
            Filter
        </x-button>

        @if(request('search') || request('status'))
        <x-button href="{{ route('kepala.evaluasi.index') }}" variant="secondary">
            Reset
        </x-button>
        @endif
    </form>
</x-card>

<!-- Supervisi List -->
<x-card flush>
    <x-card-header title="Daftar Supervisi" />

    <!-- Body -->
    <div class="p-3 sm:p-6">
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3 sm:mb-4">Total <span class="font-semibold tabular-nums">{{ $supervisiList->total() }}</span> supervisi ditemukan</p>

        @if($supervisiList->count() > 0)
        <div class="space-y-2 sm:space-y-3">
            @foreach($supervisiList as $supervisi)
            <div class="relative p-3 sm:p-4 bg-white dark:bg-gray-700 rounded-xl hover:bg-primary-50/50 dark:hover:bg-gray-600 transition-all border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-600 shadow-sm hover:shadow-md">
                <!-- Aksen kiri: warna makna status (selaras x-status-badge) -->
                <div class="absolute left-0 top-2 sm:top-3 bottom-2 sm:bottom-3 w-1 @if($supervisi->status == 'submitted') bg-blue-500 @elseif($supervisi->status == 'under_review') bg-amber-500 @elseif($supervisi->status == 'revision') bg-red-500 @else bg-emerald-500 @endif rounded-r-full"></div>

                <div class="flex items-start justify-between gap-2 sm:gap-4 ml-2 sm:ml-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2 flex-wrap">
                            <div class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</div>
                            <x-status-badge :status="$supervisi->status" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1.5 sm:gap-2 text-xs text-gray-500 dark:text-gray-400">
                            @if($supervisi->user->mata_pelajaran)
                            <div class="flex items-center gap-1.5">
                                <x-icon name="book-open" class="w-3.5 h-3.5" />
                                <span class="font-medium">{{ $supervisi->user->mata_pelajaran }}</span>
                            </div>
                            @endif
                            @if($supervisi->user->tingkat)
                            <div class="flex items-center gap-1.5">
                                <x-icon name="users" class="w-3.5 h-3.5" />
                                <span>{{ $supervisi->user->tingkat }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-1.5">
                                <x-icon name="calendar" class="w-3.5 h-3.5" />
                                <span>{{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>

                        @if($supervisi->reviewed_at)
                        <div class="flex items-center gap-1 text-xs text-primary-600 dark:text-primary-400 mt-1.5 sm:mt-2 pt-1.5 sm:pt-2 border-t border-gray-100 dark:border-gray-600">
                            <x-icon name="check-circle" class="w-3.5 h-3.5" />
                            <span>Ditinjau {{ $supervisi->reviewed_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>

                    <x-button href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" size="sm" class="shrink-0">
                        Detail
                    </x-button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($supervisiList->hasPages())
        <div class="mt-4 sm:mt-6">
            {{ $supervisiList->links() }}
        </div>
        @endif

        @else
        <x-empty-state
            icon="document"
            title="Tidak ada supervisi"
            description="Tidak ada supervisi yang ditemukan"
            :compact="true"
        />
        @if(request('search') || request('status'))
        <div class="text-center pb-4">
            <x-button href="{{ route('kepala.evaluasi.index') }}" size="sm">
                Lihat Semua Supervisi
            </x-button>
        </div>
        @endif
        @endif
    </div>
</x-card>

@endsection
