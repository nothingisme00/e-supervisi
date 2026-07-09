<?php

namespace Tests\Feature\Guru;

use App\Models\Modul;
use App\Models\ModulKategori;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulTest extends TestCase
{
    use RefreshDatabase;

    private function createGuru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    public function test_guru_can_view_modul_list_with_progress(): void
    {
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(['judul' => 'Modul Literasi', 'jumlah_halaman' => 10]);
        ModulProgress::create(['user_id' => $guru->id, 'modul_id' => $modul->id, 'halaman_terjauh' => 6]);

        $response = $this->actingAs($guru)->get(route('guru.modul.index'));

        $response->assertStatus(200);
        $response->assertSee('Modul Literasi');
        $response->assertSee('60%');
    }

    public function test_inactive_modul_hidden_from_list(): void
    {
        Modul::factory()->create(['judul' => 'Modul Nonaktif', 'is_active' => false]);

        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.index'));

        $response->assertDontSee('Modul Nonaktif');
    }

    public function test_list_can_be_filtered_by_kategori(): void
    {
        $kategoriA = ModulKategori::factory()->create(['nama' => 'Pedagogik']);
        $kategoriB = ModulKategori::factory()->create(['nama' => 'Numerasi']);
        Modul::factory()->create(['judul' => 'Modul Pedagogik Satu', 'modul_kategori_id' => $kategoriA->id]);
        Modul::factory()->create(['judul' => 'Modul Numerasi Satu', 'modul_kategori_id' => $kategoriB->id]);

        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.index', ['kategori' => $kategoriA->id]));

        $response->assertSee('Modul Pedagogik Satu');
        $response->assertDontSee('Modul Numerasi Satu');
    }

    public function test_empty_state_shown_when_no_modul(): void
    {
        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada modul');
    }

    public function test_admin_cannot_access_guru_modul_list(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $response = $this->actingAs($admin)->get(route('guru.modul.index'));

        $response->assertStatus(403);
    }
}
