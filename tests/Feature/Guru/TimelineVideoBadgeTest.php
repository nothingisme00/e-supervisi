<?php

namespace Tests\Feature\Guru;

use App\Models\ProsesPembelajaran;
use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineVideoBadgeTest extends TestCase
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

    public function test_beranda_menampilkan_thumbnail_youtube(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $response->assertSee('img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');
    }

    public function test_beranda_menampilkan_badge_untuk_url_tanpa_thumbnail(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, 'https://drive.google.com/file/d/1AbC-dEf_123/view');

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $response->assertSee('Video Praktik');
        $response->assertDontSee('img.youtube.com');
    }

    public function test_beranda_tanpa_video_tanpa_badge(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, null);

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $response->assertDontSee('Video Praktik');
    }

    public function test_supervisi_saya_menampilkan_thumbnail_youtube(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $response = $this->actingAs($guru)->get(route('guru.my-supervisi'));

        $response->assertSee('img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');
    }
}
