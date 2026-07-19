<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthPageFooterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * V7: halaman auth standalone (login, change-password) ikut menampilkan
     * co-brand footer "Sistem Supervisi Pembelajaran" seperti halaman lain.
     */
    public function test_auth_pages_show_cobrand_footer(): void
    {
        $this->get(route('login'))
            ->assertSee('Yayasan Az-Zahroh · Sistem Supervisi Pembelajaran');

        $guru = User::factory()->guru()->create(['must_change_password' => true, 'tingkat' => 'SD']);
        $this->actingAs($guru)->get(route('change-password'))
            ->assertSee('Yayasan Az-Zahroh · Sistem Supervisi Pembelajaran');
    }
}
