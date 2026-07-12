<?php

namespace Tests\Feature\Guru;

use App\Models\ProsesPembelajaran;
use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineVideoHeroTest extends TestCase
{
    use RefreshDatabase;

    private function createGuru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    private function supervisiDenganVideo(User $guru, ?string $url): Supervisi
    {
        $supervisi = Supervisi::factory()->completed()->create(['user_id' => $guru->id]);

        if ($url !== null) {
            ProsesPembelajaran::factory()->create([
                'supervisi_id' => $supervisi->id,
                'link_video' => $url,
            ]);
        }

        return $supervisi;
    }

    public function test_beranda_menampilkan_hero_video_dengan_tombol_play(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $response->assertSee('data-embed-url="https://www.youtube.com/embed/dQw4w9WgXcQ"', false);
        $response->assertSee('Putar video praktik');
    }

    public function test_video_drive_memakai_embed_preview(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, 'https://drive.google.com/file/d/1AbC-dEf_123/view');

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $response->assertSee('data-embed-url="https://drive.google.com/file/d/1AbC-dEf_123/preview"', false);
    }

    public function test_hero_menggantikan_chip_label_tidak_dobel(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $this->assertSame(1, substr_count($response->getContent(), 'Video Praktik'));
    }
}
