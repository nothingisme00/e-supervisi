<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Supervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->admin()->create(['must_change_password' => false]);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->createAdmin())->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_guru_cannot_access_admin_dashboard(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $response = $this->actingAs($guru)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_kepala_cannot_access_admin_dashboard(): void
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false]);

        $response = $this->actingAs($kepala)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect('/login');
    }

    public function test_dashboard_shows_statistics(): void
    {
        $admin = $this->createAdmin();

        // Create some test data
        Supervisi::factory()->submitted()->count(3)->create();
        Supervisi::factory()->underReview()->count(2)->create();
        Supervisi::factory()->completed()->count(1)->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalSupervisi');
        $response->assertViewHas('supervisiPending');
        $response->assertViewHas('supervisiInProgress');
        $response->assertViewHas('supervisiReviewed');
    }
}
