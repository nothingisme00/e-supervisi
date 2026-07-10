<?php

namespace Tests\Unit\Models;

use App\Models\Modul;
use App\Models\ModulVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulVideoModelTest extends TestCase
{
    use RefreshDatabase;

    private function makeVideo(string $url): ModulVideo
    {
        return ModulVideo::create([
            'modul_id' => Modul::factory()->create()->id,
            'judul' => 'Video uji',
            'youtube_url' => $url,
        ]);
    }

    public function test_embed_url_dari_format_watch(): void
    {
        $video = $this->makeVideo('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $video->youtube_embed_url);
    }

    public function test_embed_url_dari_format_pendek(): void
    {
        $video = $this->makeVideo('https://youtu.be/dQw4w9WgXcQ');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $video->youtube_embed_url);
    }

    public function test_embed_url_null_untuk_url_tak_dikenali(): void
    {
        $video = $this->makeVideo('https://example.com/video');

        $this->assertNull($video->youtube_embed_url);
    }

    public function test_embed_url_dari_format_shorts(): void
    {
        $video = $this->makeVideo('https://www.youtube.com/shorts/dQw4w9WgXcQ');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $video->youtube_embed_url);
    }

    public function test_embed_url_null_untuk_host_palsu(): void
    {
        // Regex lama tidak host-anchored: substring "youtube.com/watch?v=" di
        // tengah path host lain ikut lolos. Harus null setelah refactor.
        $video = $this->makeVideo('https://evil.com/youtube.com/watch?v=dQw4w9WgXcQ');

        $this->assertNull($video->youtube_embed_url);
    }
}
