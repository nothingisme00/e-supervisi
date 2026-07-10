<?php

namespace App\Support;

/**
 * Menerjemahkan URL video (YouTube / Google Drive) menjadi URL embed
 * dan thumbnail. URL yang tidak dikenali menghasilkan null — pemakai
 * jatuh kembali ke tautan keluar biasa.
 */
final class VideoEmbed
{
    /** Pola host-anchored: menolak youtube.com yang bukan host sebenarnya. */
    private const YOUTUBE_PATTERNS = [
        '#^https?://(?:www\.|m\.)?youtube\.com/watch\?(?:[^\s]*&)?v=([A-Za-z0-9_-]{11})#',
        '#^https?://(?:www\.|m\.)?youtube\.com/shorts/([A-Za-z0-9_-]{11})#',
        '#^https?://youtu\.be/([A-Za-z0-9_-]{11})#',
    ];

    private const DRIVE_PATTERN = '#^https?://drive\.google\.com/file/d/([A-Za-z0-9_-]+)#';

    public static function youtubeId(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }

        foreach (self::YOUTUBE_PATTERNS as $pattern) {
            if (preg_match($pattern, $url, $m)) {
                return $m[1];
            }
        }

        return null;
    }

    public static function youtubeEmbedUrl(?string $url): ?string
    {
        $id = self::youtubeId($url);

        return $id === null ? null : 'https://www.youtube.com/embed/' . $id;
    }

    public static function embedUrl(?string $url): ?string
    {
        if ($embed = self::youtubeEmbedUrl($url)) {
            return $embed;
        }

        if ($url !== null && preg_match(self::DRIVE_PATTERN, $url, $m)) {
            return 'https://drive.google.com/file/d/' . $m[1] . '/preview';
        }

        return null;
    }

    public static function thumbnailUrl(?string $url): ?string
    {
        $id = self::youtubeId($url);

        return $id === null ? null : 'https://img.youtube.com/vi/' . $id . '/hqdefault.jpg';
    }
}
