{{--
    Hero video praktik untuk kartu timeline (gaya feed media sosial, video 16:9
    sebagai fokus kartu). Dipakai guru._supervisi-card menggantikan chip badge.

    Props:
        - supervisi (Supervisi, wajib): dibaca relasi prosesPembelajaran->link_video.
          Tanpa link video komponen tidak merender apa pun.

    Perilaku klik: URL yang dikenali VideoEmbed (YouTube/Drive) diputar di tempat
    lewat iframe embed; URL lain dibuka di tab baru.
--}}
@props(['supervisi'])

@php($videoUrl = $supervisi->prosesPembelajaran->link_video ?? null)

@if($videoUrl)
    @php($embedUrl = \App\Support\VideoEmbed::embedUrl($videoUrl))
    @php($videoThumb = \App\Support\VideoEmbed::thumbnailUrl($videoUrl))

    <div class="relative aspect-video w-full bg-gradient-to-br from-slate-700 to-slate-900 dark:from-gray-800 dark:to-gray-950 border-t border-gray-100 dark:border-gray-700">
        <button type="button"
                data-embed-url="{{ $embedUrl }}"
                data-video-url="{{ $videoUrl }}"
                onclick="playVideoPraktik(this)"
                aria-label="Putar video praktik {{ $supervisi->user->name }}"
                class="group absolute inset-0 w-full h-full">
            @if($videoThumb)
                <img src="{{ $videoThumb }}" alt="" loading="lazy"
                     class="absolute inset-0 w-full h-full object-cover">
            @endif

            <span class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-colors"></span>

            <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-600/90 text-white text-xs font-semibold rounded-full">
                <x-icon name="video-camera" class="w-3.5 h-3.5" />
                Video Praktik
            </span>

            <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                <x-icon name="play" class="w-7 h-7 ml-0.5 text-primary-600" />
            </span>
        </button>
    </div>

    @once
    <script>
        function playVideoPraktik(el) {
            if (!el.dataset.embedUrl) {
                window.open(el.dataset.videoUrl, '_blank', 'noopener');
                return;
            }

            var iframe = document.createElement('iframe');
            iframe.src = el.dataset.embedUrl + (el.dataset.embedUrl.includes('youtube') ? '?autoplay=1' : '');
            iframe.className = 'absolute inset-0 w-full h-full border-0';
            iframe.title = 'Video praktik pembelajaran';
            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
            iframe.setAttribute('allowfullscreen', '');

            el.parentElement.appendChild(iframe);
            el.remove();
        }
    </script>
    @endonce
@endif
