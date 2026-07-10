@props(['url'])

@php($embedUrl = \App\Support\VideoEmbed::embedUrl($url))

<div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-r-lg p-3 sm:p-4">
    <div class="flex items-center gap-2 mb-2">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white">Video Praktik Pembelajaran</span>
    </div>

    @if($embedUrl)
        <div class="relative w-full overflow-hidden rounded-lg bg-gray-900 mb-2" style="padding-bottom: 56.25%;">
            <iframe src="{{ $embedUrl }}"
                    class="absolute inset-0 w-full h-full border-0"
                    title="Video praktik pembelajaran"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    @endif

    <a href="{{ $url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:underline max-w-full">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
        </svg>
        <span class="truncate">{{ $url }}</span>
    </a>
</div>
