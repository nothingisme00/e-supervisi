<?php

namespace Tests\Feature\Guru;

use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MySupervisiGuideTest extends TestCase
{
    use RefreshDatabase;

    private function createGuru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    public function test_supervisi_saya_kosong_memuat_flag_autoshow_panduan(): void
    {
        $guru = $this->createGuru();

        $response = $this->actingAs($guru)->get(route('guru.my-supervisi'));

        $response->assertOk();
        $response->assertSee('const belumAdaSupervisi = true', false);
    }

    public function test_supervisi_saya_berisi_tidak_autoshow_panduan(): void
    {
        $guru = $this->createGuru();
        Supervisi::factory()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->get(route('guru.my-supervisi'));

        $response->assertOk();
        $response->assertSee('const belumAdaSupervisi = false', false);
    }

    public function test_modal_panduan_memakai_kartu_langkah_selaras_guide(): void
    {
        $guru = $this->createGuru();

        $response = $this->actingAs($guru)->get(route('guru.my-supervisi'));

        $response->assertOk();
        // Penanda kartu langkah bergaya Guru Guide (angka 1..6, bukan border-l-4 lama).
        $response->assertSee('data-guide-step="1"', false);
        $response->assertSee('data-guide-step="6"', false);
    }
}
