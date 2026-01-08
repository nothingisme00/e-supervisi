@props([
    'src' => '',
    'alt' => '',
    'fallbackIcon' => true,
    'fallbackGradient' => 'from-indigo-600 via-purple-600 to-indigo-800'
])

@if($src)
    <img 
        src="{{ $src }}" 
        alt="{{ $alt }}" 
        loading="lazy"
        decoding="async"
        {{ $attributes }}
    >
@elseif($fallbackIcon)
    <div class="w-full h-full bg-gradient-to-br {{ $fallbackGradient }} flex items-center justify-center" {{ $attributes->except(['class'])->merge(['class' => $attributes->get('class', '')]) }}>
        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    </div>
@endif
