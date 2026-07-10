<?php

namespace Tests\Feature\Notifikasi;

use App\Models\User;
use App\Notifications\PengingatSupervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotifikasiHalamanTest extends TestCase
{
    use RefreshDatabase;

    private function guru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    public function test_halaman_notifikasi_tampil(): void
    {
        $guru = $this->guru();
        $guru->notify(new PengingatSupervisi());

        $this->actingAs($guru)->get(route('notifikasi.index'))
            ->assertStatus(200)
            ->assertSee('Pengingat pengisian supervisi');
    }

    public function test_buka_menandai_terbaca_dan_redirect(): void
    {
        $guru = $this->guru();
        $guru->notify(new PengingatSupervisi());
        $notif = $guru->notifications()->first();

        $this->actingAs($guru)->get(route('notifikasi.buka', $notif->id))
            ->assertRedirect(route('guru.home'));

        $this->assertNotNull($notif->fresh()->read_at);
    }

    public function test_baca_semua_mengosongkan_belum_dibaca(): void
    {
        $guru = $this->guru();
        $guru->notify(new PengingatSupervisi());
        $guru->notify(new PengingatSupervisi());

        $this->actingAs($guru)->post(route('notifikasi.baca-semua'))->assertRedirect();

        $this->assertSame(0, $guru->unreadNotifications()->count());
    }

    public function test_tidak_bisa_buka_notifikasi_milik_orang_lain(): void
    {
        $guru = $this->guru();
        $lain = $this->guru();
        $lain->notify(new PengingatSupervisi());
        $notif = $lain->notifications()->first();

        $this->actingAs($guru)->get(route('notifikasi.buka', $notif->id))->assertNotFound();
    }

    public function test_view_composer_membagikan_jumlah_dan_daftar_notifikasi_terbaru(): void
    {
        $guru = $this->guru();
        $guru->notify(new PengingatSupervisi());
        $guru->notify(new PengingatSupervisi());

        $captured = null;
        \Illuminate\Support\Facades\View::composer('layouts.modern', function ($view) use (&$captured) {
            $captured = $view->getData();
        });

        $this->actingAs($guru)->get(route('notifikasi.index'));

        $this->assertSame(2, $captured['unreadNotifCount']);
        $this->assertCount(2, $captured['recentNotifs']);
    }
}
