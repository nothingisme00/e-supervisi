@props(['supervisi'])

@php($videoUrl = $supervisi->prosesPembelajaran->link_video ?? null)

@if($videoUrl)
    @php($videoThumb = \App\Support\VideoEmbed::thumbnailUrl($videoUrl))
    <a href="{{ route('guru.supervisi.detail', $supervisi->id) }}"
       class="flex items-center gap-1.5 sm:gap-1.5 md:gap-2 px-2 py-1 sm:px-2.5 sm:py-1.5 md:px-3 bg-red-50 dark:bg-red-900/20 rounded-md md:rounded-lg border border-red-100 dark:border-red-800 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
        @if($videoThumb)
            <img src="{{ $videoThumb }}" alt="Thumbnail video praktik" loading="lazy"
                 class="w-12 h-7 sm:w-14 sm:h-8 object-cover rounded flex-shrink-0">
        @else
            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
        @endif
        <span class="text-[10px] sm:text-xs font-semibold text-red-700 dark:text-red-300">Video Praktik</span>
    </a>
@endif
