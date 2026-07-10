@extends('layouts.modern')

@section('page-title', 'Notifikasi')

@section('content')
<div class="max-w-3xl mx-auto px-3 sm:px-4 py-4 sm:py-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">Notifikasi</h1>
        @if(auth()->user()->unreadNotifications()->count() > 0)
        <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
            @csrf
            <button type="submit" class="text-xs sm:text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline">Tandai semua terbaca</button>
        </form>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($notifikasi as $n)
            @include('notifikasi._item', ['n' => $n])
        @empty
            <div class="text-center py-10 px-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada notifikasi.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifikasi->links() }}
    </div>
</div>
@endsection
