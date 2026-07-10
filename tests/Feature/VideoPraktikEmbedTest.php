<?php

namespace Tests\Feature;

use App\Models\ProsesPembelajaran;
use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoPraktikEmbedTest extends TestCase
{
    use RefreshDatabase;

    private const YOUTUBE_URL = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
    private const YOUTUBE_EMBED = 'https://www.youtube.com/embed/dQw4w9WgXcQ';
    private const DRIVE_URL = 'https://drive.google.com/file/d/1AbC-dEf_123/view?usp=sharing';
    private const DRIVE_EMBED = 'https://drive.google.com/file/d/1AbC-dEf_123/preview';
    private const UNKNOWN_URL = 'https://contoh-sekolah.sch.id/video/praktik.mp4';

    private function createGuru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    private function supervisiDenganVideo(User $guru, string $url): Supervisi
    {
        $supervisi = Supervisi::factory()->completed()->create(['user_id' => $guru->id]);
        ProsesPembelajaran::factory()->create([
            'supervisi_id' => $supervisi->id,
            'link_video' => $url,
        ]);

        return $supervisi;
    }

    public function test_detail_guru_menampilkan_embed_youtube(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->supervisiDenganVideo($guru, self::YOUTUBE_URL);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id));

        $response->assertStatus(200);
        $response->assertSee(self::YOUTUBE_EMBED);
        $response->assertSee('<iframe', false);
    }

    public function test_detail_guru_menampilkan_preview_drive(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->supervisiDenganVideo($guru, self::DRIVE_URL);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id));

        $response->assertSee(self::DRIVE_EMBED);
    }

    public function test_detail_guru_tautan_asli_youtube_tetap_tampil_di_bawah_pemutar(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->supervisiDenganVideo($guru, self::YOUTUBE_URL);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id));

        $response->assertSee(self::YOUTUBE_URL);
    }

    public function test_detail_guru_preview_drive_dirender_sebagai_iframe_dengan_tautan_asli(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->supervisiDenganVideo($guru, self::DRIVE_URL);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id));

        $response->assertSee('<iframe', false);
        $response->assertSee(self::DRIVE_URL);
    }

    public function test_detail_guru_url_tak_dikenal_jatuh_ke_tautan(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->supervisiDenganVideo($guru, self::UNKNOWN_URL);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id));

        $response->assertSee(self::UNKNOWN_URL);
        $response->assertDontSee('<iframe', false);
    }
}
