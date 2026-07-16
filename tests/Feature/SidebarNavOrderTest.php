<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarNavOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_sidebar_menampilkan_beranda_sebelum_notifikasi(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $response = $this->actingAs($guru)->get(route('guru.my-supervisi'));

        $response->assertOk();
        $response->assertSeeInOrder([
            '<span class="flex-1">Beranda</span>',
            '<span class="flex-1">Notifikasi</span>',
        ], false);
    }

    public function test_admin_sidebar_menampilkan_dashboard_sebelum_notifikasi(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSeeInOrder([
            '<span class="flex-1">Dashboard</span>',
            '<span class="flex-1">Notifikasi</span>',
        ], false);
    }

    public function test_kepala_sidebar_menampilkan_dashboard_sebelum_notifikasi(): void
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false]);

        $response = $this->actingAs($kepala)->get(route('kepala.dashboard'));

        $response->assertOk();
        $response->assertSeeInOrder([
            '<span class="flex-1">Dashboard</span>',
            '<span class="flex-1">Notifikasi</span>',
        ], false);
    }
}
