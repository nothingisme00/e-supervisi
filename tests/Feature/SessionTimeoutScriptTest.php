<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionTimeoutScriptTest extends TestCase
{
    use RefreshDatabase;

    public function test_timeout_script_client_mengikuti_config_session_lifetime(): void
    {
        // Regresi: script idle-logout di layouts/modern hardcode 10 menit,
        // padahal server (SessionTimeout middleware) memakai session.lifetime
        // = 30 menit. Client harus satu sumber dengan server.
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('INACTIVITY_TIMEOUT = 10 * 60 * 1000', false);
        $response->assertSee('INACTIVITY_TIMEOUT = ' . (int) config('session.lifetime') . ' * 60 * 1000', false);
    }
}
