@extends('layouts.modern')

@section('page-title', 'Notifikasi')

@section('content')
<div class="max-w-3xl mx-auto px-3 sm:px-4 py-4 sm:py-6">
    <x-page-header title="Notifikasi">
        <x-slot:actions>
            @if(auth()->user()->unreadNotifications()->count() > 0)
            <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
                @csrf
                <x-button type="submit" variant="ghost" size="sm">
                    <x-icon name="check" class="w-4 h-4" />
                    Tandai semua terbaca
                </x-button>
            </form>
            @endif
        </x-slot:actions>
    </x-page-header>

    @php
        // Kelompokkan item halaman ini per tanggal dibuat
        $grup = $notifikasi->groupBy(function ($n) {
            if ($n->created_at->isToday()) {
                return 'Hari Ini';
            }
            if ($n->created_at->isYesterday()) {
                return 'Kemarin';
            }
            return 'Sebelumnya';
        });
    @endphp

    @if($notifikasi->count() > 0)
        <div class="space-y-4 sm:space-y-5">
            @foreach(['Hari Ini', 'Kemarin', 'Sebelumnya'] as $label)
                @if($grup->has($label))
                <div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">{{ $label }}</h2>
                    <x-card flush class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($grup->get($label) as $n)
                            @include('notifikasi._item', ['n' => $n])
                        @endforeach
                    </x-card>
                </div>
                @endif
            @endforeach
        </div>
    @else
        <x-card flush>
            <x-empty-state
                icon="bell"
                title="Belum ada notifikasi"
                description="Notifikasi tentang supervisi, feedback, dan modul akan tampil di sini"
                :compact="true"
            />
        </x-card>
    @endif

    <div class="mt-4">
        {{ $notifikasi->links() }}
    </div>
</div>
@endsection
