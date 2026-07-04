<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\CarouselSlide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_login_page_has_no_cache_headers(): void
    {
        $response = $this->get('/login');
        $response->assertHeader('Cache-Control');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->admin()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_guru_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->guru()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $response->assertRedirect(route('guru.home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_kepala_sekolah_can_login(): void
    {
        $user = User::factory()->kepalaSekolah()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'kepala_sekolah',
        ]);

        $response->assertRedirect(route('kepala.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->guru()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'wrongpassword',
            'role' => 'guru',
        ]);

        $response->assertSessionHasErrors('nik');
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_role(): void
    {
        User::factory()->guru()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'admin', // Wrong role
        ]);

        $response->assertSessionHasErrors('nik');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->guru()->inactive()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $response->assertSessionHasErrors('nik');
        $this->assertGuest();
    }

    public function test_login_redirects_to_change_password_if_required(): void
    {
        User::factory()->guru()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
            'is_active' => true,
            'must_change_password' => true,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $response->assertRedirect(route('change-password'));
    }

    public function test_login_validation_requires_nik(): void
    {
        $response = $this->post('/login', [
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $response->assertSessionHasErrors('nik');
    }

    public function test_login_validation_requires_password(): void
    {
        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'role' => 'guru',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_login_validation_requires_valid_role(): void
    {
        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'invalid_role',
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->guru()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_login_sets_just_logged_in_session(): void
    {
        User::factory()->guru()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $response->assertSessionHas('just_logged_in', true);
    }
}
