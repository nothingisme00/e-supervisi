<?php

namespace Tests\Feature\Notifikasi;

use App\Models\Supervisi;
use App\Models\User;
use App\Notifications\PengingatSupervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PengingatSupervisiTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengingat_terkirim_ke_guru_tanpa_supervisi_aktif(): void
    {
        Notification::fake();

        $belumMulai = User::factory()->guru()->create(['is_active' => true]);
        $draftSaja = User::factory()->guru()->create(['is_active' => true]);
        Supervisi::factory()->draft()->create(['user_id' => $draftSaja->id]);

        $sudahSubmit = User::factory()->guru()->create(['is_active' => true]);
        Supervisi::factory()->submitted()->create(['user_id' => $sudahSubmit->id]);
        $sudahSelesai = User::factory()->guru()->create(['is_active' => true]);
        Supervisi::factory()->completed()->create(['user_id' => $sudahSelesai->id]);
        $nonaktif = User::factory()->guru()->create(['is_active' => false]);

        $this->artisan('notifikasi:pengingat-supervisi')->assertExitCode(0);

        Notification::assertSentTo($belumMulai, PengingatSupervisi::class);
        Notification::assertSentTo($draftSaja, PengingatSupervisi::class);
        Notification::assertNotSentTo($sudahSubmit, PengingatSupervisi::class);
        Notification::assertNotSentTo($sudahSelesai, PengingatSupervisi::class);
        Notification::assertNotSentTo($nonaktif, PengingatSupervisi::class);
    }

    public function test_guru_dengan_pengingat_belum_dibaca_tidak_dapat_dobel(): void
    {
        $guru = User::factory()->guru()->create(['is_active' => true]);
        $guru->notify(new PengingatSupervisi()); // 1 pengingat belum dibaca tersimpan

        Notification::fake(); // hitung hanya pengiriman SETELAH ini
        $this->artisan('notifikasi:pengingat-supervisi')->assertExitCode(0);

        Notification::assertNotSentTo($guru, PengingatSupervisi::class);
    }

    public function test_data_notifikasi_berisi_judul_pesan_ikon_dan_url(): void
    {
        $guru = User::factory()->guru()->create();

        $data = (new PengingatSupervisi())->toArray($guru);

        $this->assertArrayHasKey('judul', $data);
        $this->assertNotEmpty($data['judul']);
        $this->assertArrayHasKey('pesan', $data);
        $this->assertNotEmpty($data['pesan']);
        $this->assertSame('pengingat', $data['ikon']);
        $this->assertSame(route('guru.home'), $data['url']);
    }
}
