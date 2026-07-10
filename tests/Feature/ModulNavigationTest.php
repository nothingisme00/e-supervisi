<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sidebar_has_modul_link(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertSee(route('admin.modul.index'));
    }

    public function test_guru_sidebar_has_modul_link(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $response->assertSee(route('guru.modul.index'));
    }

    public function test_kepala_sidebar_has_progres_modul_link(): void
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false]);

        $response = $this->actingAs($kepala)->get(route('kepala.dashboard'));

        $response->assertSee(route('kepala.modul-progress.index'));
    }
}
