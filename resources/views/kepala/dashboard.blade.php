@extends('layouts.modern')

@section('page-title', 'Supervisi Pembelajaran')

@section('content')
<x-page-header title="Supervisi Pembelajaran" subtitle="Monitor dan evaluasi supervisi guru">
    <x-slot:actions>
        <x-button variant="secondary" size="sm" onclick="openTipsModal()">
            <x-icon name="information-circle" class="w-4 h-4" />
            <span class="hidden sm:inline">Tips & Info</span>
        </x-button>
        <x-button variant="secondary" size="sm" onclick="openGuideModal()">
            <x-icon name="book-open" class="w-4 h-4" />
            <span class="hidden sm:inline">Panduan</span>
        </x-button>
    </x-slot:actions>
</x-page-header>

<!-- Grid 3 kolom review per status (warna selaras x-status-badge) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">
    @include('kepala._review-column', [
        'accent' => 'blue',
        'icon' => 'clock',
        'title' => 'Perlu Review',
        'subtitle' => 'Menunggu peninjauan',
        'count' => $supervisiPending,
        'list' => $pendingList,
        'useReviewedAt' => false,
        'buttonLabel' => 'Mulai Review',
        'buttonIcon' => 'arrow-right',
        'buttonVariant' => 'primary',
        'emptyIcon' => 'clock',
        'emptyDescription' => 'Belum ada supervisi yang perlu direview saat ini',
    ])

    @include('kepala._review-column', [
        'accent' => 'amber',
        'icon' => 'eye',
        'title' => 'Sedang Ditinjau',
        'subtitle' => 'Dalam proses review',
        'count' => $supervisiInProgress,
        'list' => $inProgressList,
        'useReviewedAt' => true,
        'buttonLabel' => 'Lanjutkan Review',
        'buttonIcon' => 'arrow-right',
        'buttonVariant' => 'primary',
        'emptyIcon' => 'document',
        'emptyDescription' => 'Belum ada supervisi yang sedang ditinjau',
    ])

    @include('kepala._review-column', [
        'accent' => 'emerald',
        'icon' => 'check-circle',
        'title' => 'Telah Selesai',
        'subtitle' => 'Review selesai',
        'count' => $supervisiReviewed,
        'list' => $completedList,
        'useReviewedAt' => true,
        'buttonLabel' => 'Lihat Detail',
        'buttonIcon' => 'eye',
        'buttonVariant' => 'secondary',
        'emptyIcon' => 'check',
        'emptyDescription' => 'Belum ada supervisi yang telah selesai',
    ])
</div>

@endsection
