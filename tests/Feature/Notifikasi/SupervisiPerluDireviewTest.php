<?php

namespace Tests\Feature\Notifikasi;

use App\Models\ProsesPembelajaran;
use App\Models\Supervisi;
use App\Models\User;
use App\Notifications\SupervisiPerluDireview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SupervisiPerluDireviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_submit_mengirim_notifikasi_ke_kepala_sekolah_tingkat_sama_saja(): void
    {
        Notification::fake();

        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $kepalaSD = User::factory()->kepalaSekolah()->create(['is_active' => true, 'tingkat' => 'SD']);
        $kepalaSMP = User::factory()->kepalaSekolah()->create(['is_active' => true, 'tingkat' => 'SMP']);
        $kepalaNonaktif = User::factory()->kepalaSekolah()->create(['is_active' => false, 'tingkat' => 'SD']);
        $admin = User::factory()->admin()->create();

        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);
        ProsesPembelajaran::factory()->create(['supervisi_id' => $supervisi->id]);

        $this->actingAs($guru)
            ->post(route('guru.supervisi.submit', $supervisi->id))
            ->assertOk()
            ->assertJson(['success' => true]);

        Notification::assertSentTo($kepalaSD, SupervisiPerluDireview::class);
        Notification::assertNotSentTo($kepalaSMP, SupervisiPerluDireview::class);
        Notification::assertNotSentTo($kepalaNonaktif, SupervisiPerluDireview::class);
        Notification::assertNotSentTo($admin, SupervisiPerluDireview::class);
        Notification::assertNotSentTo($guru, SupervisiPerluDireview::class);
    }

    public function test_data_notifikasi_berisi_judul_pesan_ikon_dan_url(): void
    {
        $guru = User::factory()->guru()->create(['name' => 'Bu Ani', 'tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);
        $kepala = User::factory()->kepalaSekolah()->create(['tingkat' => 'SD']);

        $data = (new SupervisiPerluDireview($supervisi))->toArray($kepala);

        $this->assertSame('Supervisi perlu direview', $data['judul']);
        $this->assertStringContainsString('Bu Ani', $data['pesan']);
        $this->assertSame('review', $data['ikon']);
        $this->assertSame(route('kepala.evaluasi.show', $supervisi->id), $data['url']);
    }
}
