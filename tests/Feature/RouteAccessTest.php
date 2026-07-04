<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteAccessTest extends TestCase
{
    use RefreshDatabase;

    // ============================
    // Root Route Redirects
    // ============================

    public function test_write_heavy_routes_have_rate_limit(): void
    {
        $routes = app('router')->getRoutes();
        foreach (['guru.supervisi.upload', 'guru.supervisi.comment', 'admin.supervisi.feedback', 'kepala.evaluasi.feedback'] as $name) {
            $middleware = $routes->getByName($name)->gatherMiddleware();
            $this->assertContains('throttle:30,1', $middleware, "Route {$name} tanpa rate limit");
        }
    }

    public function test_root_redirects_to_login_for_guests(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_root_redirects_admin_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $response = $this->actingAs($admin)->get('/');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_root_redirects_guru_to_guru_home(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);
        $response = $this->actingAs($guru)->get('/');
        $response->assertRedirect(route('guru.home'));
    }

    public function test_root_redirects_kepala_to_kepala_dashboard(): void
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false]);
        $response = $this->actingAs($kepala)->get('/');
        $response->assertRedirect(route('kepala.dashboard'));
    }

    // ============================
    // Role-Based Access Control
    // ============================

    public function test_guru_cannot_access_admin_routes(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);
        $this->actingAs($guru)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($guru)->get(route('admin.users.index'))->assertStatus(403);
    }

    public function test_guru_cannot_access_kepala_routes(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);
        $this->actingAs($guru)->get(route('kepala.dashboard'))->assertStatus(403);
        $this->actingAs($guru)->get(route('kepala.evaluasi.index'))->assertStatus(403);
    }

    public function test_admin_cannot_access_guru_routes(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $this->actingAs($admin)->get(route('guru.home'))->assertStatus(403);
    }

    public function test_admin_cannot_access_kepala_routes(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $this->actingAs($admin)->get(route('kepala.dashboard'))->assertStatus(403);
    }

    public function test_kepala_cannot_access_admin_routes(): void
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false]);
        $this->actingAs($kepala)->get(route('admin.dashboard'))->assertStatus(403);
    }

    public function test_kepala_cannot_access_guru_routes(): void
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false]);
        $this->actingAs($kepala)->get(route('guru.home'))->assertStatus(403);
    }

    // ============================
    // Unauthenticated Access
    // ============================

    public function test_protected_routes_redirect_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect('/login');
        $this->get(route('guru.home'))->assertRedirect('/login');
        $this->get(route('kepala.dashboard'))->assertRedirect('/login');
        $this->get(route('settings.index'))->assertRedirect('/login');
    }
}
