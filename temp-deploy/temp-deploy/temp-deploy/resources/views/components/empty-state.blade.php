@props([
    'icon' => 'inbox',
    'title' => 'Tidak ada data',
    'description' => 'Belum ada data yang tersedia.',
    'actionText' => null,
    'actionUrl' => null,
    'compact' => false
])

<div class="text-center {{ $compact ? 'py-8' : 'py-16' }}">
    <div class="inline-flex items-center justify-center {{ $compact ? 'w-16 h-16 mb-4' : 'w-24 h-24 mb-6' }} bg-gray-100 dark:bg-gray-800 rounded-2xl">
        @if($icon === 'inbox')
        <svg class="{{ $compact ? 'w-8 h-8' : 'w-12 h-12' }} text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
        @elseif($icon === 'document')
        <svg class="{{ $compact ? 'w-8 h-8' : 'w-12 h-12' }} text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        @elseif($icon === 'users')
        <svg class="{{ $compact ? 'w-8 h-8' : 'w-12 h-12' }} text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        @elseif($icon === 'search')
        <svg class="{{ $compact ? 'w-8 h-8' : 'w-12 h-12' }} text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        @elseif($icon === 'clock')
        <svg class="{{ $compact ? 'w-8 h-8' : 'w-12 h-12' }} text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        @elseif($icon === 'check')
        <svg class="{{ $compact ? 'w-8 h-8' : 'w-12 h-12' }} text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        @endif
    </div>
    
    <h3 class="{{ $compact ? 'text-base' : 'text-lg' }} font-semibold text-gray-900 dark:text-white mb-2">{{ $title }}</h3>
    <p class="{{ $compact ? 'text-sm' : 'text-base' }} text-gray-500 dark:text-gray-400 {{ $actionText ? 'mb-6' : '' }}">{{ $description }}</p>
    
    @if($actionText && $actionUrl)
    <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        {{ $actionText }}
    </a>
    @endif
</div>
