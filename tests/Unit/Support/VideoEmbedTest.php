<?php

namespace Tests\Unit\Support;

use App\Support\VideoEmbed;
use PHPUnit\Framework\TestCase;

class VideoEmbedTest extends TestCase
{
    public function test_embed_dari_url_watch(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_watch_tanpa_www(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://youtube.com/watch?v=dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_watch_mobile(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://m.youtube.com/watch?v=dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_watch_dengan_parameter_lain_di_depan(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://www.youtube.com/watch?feature=share&v=dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_pendek(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://youtu.be/dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_shorts(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://www.youtube.com/shorts/dQw4w9WgXcQ')
        );
    }

    public function test_host_palsu_ditolak(): void
    {
        $this->assertNull(VideoEmbed::embedUrl('https://evil.com/watch?v=dQw4w9WgXcQ'));
    }

    public function test_url_youtube_di_tengah_path_host_lain_ditolak(): void
    {
        $this->assertNull(
            VideoEmbed::embedUrl('https://evil.com/https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_google_drive(): void
    {
        $this->assertSame(
            'https://drive.google.com/file/d/1AbC-dEf_123/preview',
            VideoEmbed::embedUrl('https://drive.google.com/file/d/1AbC-dEf_123/view?usp=sharing')
        );
    }

    public function test_url_folder_drive_tidak_dikenali(): void
    {
        $this->assertNull(VideoEmbed::embedUrl('https://drive.google.com/drive/folders/1AbC'));
    }

    public function test_url_lain_tidak_dikenali(): void
    {
        $this->assertNull(VideoEmbed::embedUrl('https://vimeo.com/12345'));
    }

    public function test_null_menghasilkan_null(): void
    {
        $this->assertNull(VideoEmbed::embedUrl(null));
        $this->assertNull(VideoEmbed::thumbnailUrl(null));
        $this->assertNull(VideoEmbed::youtubeId(null));
    }

    public function test_thumbnail_youtube(): void
    {
        $this->assertSame(
            'https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg',
            VideoEmbed::thumbnailUrl('https://youtu.be/dQw4w9WgXcQ')
        );
    }

    public function test_thumbnail_drive_null(): void
    {
        $this->assertNull(
            VideoEmbed::thumbnailUrl('https://drive.google.com/file/d/1AbC-dEf_123/view')
        );
    }
}
