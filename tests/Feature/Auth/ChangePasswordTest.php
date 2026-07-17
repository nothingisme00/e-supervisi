<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_change_password_page_accessible_for_authenticated_users(): void
    {
        $user = User::factory()->guru()->create(['must_change_password' => true]);

        $response = $this->actingAs($user)->get('/change-password');

        $response->assertStatus(200);
    }

    public function test_halaman_ganti_password_selalu_bisa_dibuka_meski_sering_diakses(): void
    {
        // Pola redirect-loop yang sama dengan login: GET tidak boleh di-throttle,
        // hanya POST yang dibatasi.
        $user = User::factory()->guru()->create(['must_change_password' => true]);

        for ($i = 0; $i < 12; $i++) {
            $response = $this->actingAs($user)->get('/change-password');
        }

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/change-password');
        $response->assertRedirect('/login');
    }

    public function test_must_change_password_middleware_redirects(): void
    {
        $user = User::factory()->guru()->create([
            'must_change_password' => true,
        ]);

        $response = $this->actingAs($user)->get(route('guru.home'));

        $response->assertRedirect(route('change-password'));
    }

    public function test_user_without_must_change_password_can_access_protected_routes(): void
    {
        $user = User::factory()->guru()->create([
            'must_change_password' => false,
        ]);

        $response = $this->actingAs($user)->get(route('guru.home'));

        $response->assertStatus(200);
    }
}
