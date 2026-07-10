<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\Modul;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulProgressRekapTest extends TestCase
{
    use RefreshDatabase;

    private function createKepala(): User
    {
        return User::factory()->kepalaSekolah()->create(['must_change_password' => false]);
    }

    public function test_kepala_can_view_rekap(): void
    {
        Modul::factory()->create();

        $response = $this->actingAs($this->createKepala())->get(route('kepala.modul-progress.index'));

        $response->assertStatus(200);
    }

    public function test_guru_cannot_access_rekap(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $response = $this->actingAs($guru)->get(route('kepala.modul-progress.index'));

        $response->assertStatus(403);
    }

    public function test_guru_without_progress_shown_with_zero_percent(): void
    {
        $modul = Modul::factory()->create();
        $guruRajin = User::factory()->guru()->create(['name' => 'Guru Rajin']);
        $guruBelum = User::factory()->guru()->create(['name' => 'Guru Belum Baca']);
        ModulProgress::create(['user_id' => $guruRajin->id, 'modul_id' => $modul->id, 'halaman_terjauh' => 10]);

        $response = $this->actingAs($this->createKepala())
            ->get(route('kepala.modul-progress.index', ['mode' => 'modul', 'modul_id' => $modul->id]));

        $response->assertSee('Guru Rajin');
        $response->assertSee('100%');
        $response->assertSee('Guru Belum Baca');
        $response->assertSee('0%');
    }

    public function test_mode_guru_shows_all_moduls_for_selected_guru(): void
    {
        $guru = User::factory()->guru()->create(['name' => 'Guru Dipantau']);
        $modulA = Modul::factory()->create(['judul' => 'Modul Alpha', 'jumlah_halaman' => 10]);
        $modulB = Modul::factory()->create(['judul' => 'Modul Beta', 'jumlah_halaman' => 10]);
        ModulProgress::create(['user_id' => $guru->id, 'modul_id' => $modulA->id, 'halaman_terjauh' => 5]);

        $response = $this->actingAs($this->createKepala())
            ->get(route('kepala.modul-progress.index', ['mode' => 'guru', 'guru_id' => $guru->id]));

        $response->assertSee('Modul Alpha');
        $response->assertSee('50%');
        $response->assertSee('Modul Beta');
        $response->assertSee('0%');
    }

    public function test_empty_state_when_no_modul(): void
    {
        $response = $this->actingAs($this->createKepala())->get(route('kepala.modul-progress.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada modul');
    }
}
