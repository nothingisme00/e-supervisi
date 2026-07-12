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
    <x-button href="{{ $actionUrl }}" variant="primary">
        <x-icon name="plus" class="w-4 h-4" />
        {{ $actionText }}
    </x-button>
    @endif
</div>
