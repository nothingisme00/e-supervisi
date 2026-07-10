<?php

namespace Tests\Feature\Notifikasi;

use App\Models\User;
use App\Notifications\PengingatSupervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoncengTopbarTest extends TestCase
{
    use RefreshDatabase;

    public function test_lonceng_tampil_di_topbar_guru(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $this->actingAs($guru)->get(route('guru.home'))
            ->assertStatus(200)
            ->assertSee('id="notif-dropdown-btn"', false);
    }

    public function test_badge_menampilkan_jumlah_belum_dibaca(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);
        $guru->notify(new PengingatSupervisi());
        $guru->notify(new PengingatSupervisi());

        $this->actingAs($guru)->get(route('guru.home'))
            ->assertSee('data-notif-badge', false)
            ->assertSee('Pengingat pengisian supervisi');
    }
}
