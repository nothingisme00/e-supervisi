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
        <x-icon :name="$icon" class="{{ $compact ? 'w-8 h-8' : 'w-12 h-12' }} text-gray-400 dark:text-gray-500" />
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
