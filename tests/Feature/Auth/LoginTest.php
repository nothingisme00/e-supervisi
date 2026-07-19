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

    public function test_login_page_menampilkan_nama_yayasan_sebagai_brand(): void
    {
        // Rebrand: header login memakai nama aplikasi "Yayasan Az-Zahro".
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Yayasan Az-Zahro');
    }

    public function test_login_page_membersihkan_jejak_idle_logout(): void
    {
        // Regresi "login lalu ter-logout seketika": jejak lastActivityTime /
        // sessionLogoutTime basi di localStorage (sisa sesi lama) menendang
        // login baru via init() script idle-logout. Halaman login harus
        // membersihkan jejak itu.
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee("localStorage.removeItem('lastActivityTime')", false);
        $response->assertSee("localStorage.removeItem('sessionLogoutTime')", false);
    }

    public function test_login_page_tanpa_blok_selamat_datang(): void
    {
        // Redesign hirarki: blok "Selamat Datang" dihapus agar nama yayasan
        // jadi satu-satunya heading dominan (tidak ada dua heading bersaing).
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('Selamat Datang');
    }

    public function test_halaman_login_selalu_bisa_dibuka_meski_sering_diakses(): void
    {
        // Regresi ERR_TOO_MANY_REDIRECTS: GET /login tidak boleh ikut kena
        // throttle — hanya POST yang dibatasi. Halaman harus selalu render.
        for ($i = 0; $i < 8; $i++) {
            $response = $this->get('/login');
        }

        $response->assertStatus(200);
    }

    public function test_post_login_terthrottle_kembali_ke_login_dengan_detik_tunggu(): void
    {
        // Lampaui batas throttle:5,1 pada POST /login.
        for ($i = 0; $i < 6; $i++) {
            $response = $this->from('/login')->post('/login', [
                'nik' => '1234567890123456',
                'password' => 'salah',
                'role' => 'guru',
            ]);
        }

        // Bukan halaman error: kembali ke login dengan pesan + detik tunggu untuk countdown.
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('throttle');
        $response->assertSessionHas('throttle_seconds');
        $this->assertIsInt(session('throttle_seconds'));
        $this->assertGreaterThan(0, session('throttle_seconds'));
    }

    public function test_halaman_login_menampilkan_countdown_saat_throttle(): void
    {
        $response = $this->withSession(['throttle_seconds' => 42])->get('/login');

        $response->assertStatus(200);
        $response->assertSee('data-throttle-seconds="42"', false);
        $response->assertSee('id="throttle-countdown"', false);
        $response->assertSee('Terlalu banyak percobaan login');
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

    public function test_change_password_info_flash_is_visible(): void
    {
        User::factory()->guru()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
            'is_active' => true,
            'must_change_password' => true,
        ]);

        $response = $this->followingRedirects()->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $response->assertSee('mengganti password default');
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

    public function test_logout_flash_message_is_visible_on_login_page(): void
    {
        $user = User::factory()->guru()->create(['must_change_password' => false]);

        $response = $this->actingAs($user)->followingRedirects()->post('/logout');

        $response->assertSee('berhasil logout');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->guru()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_halaman_login_tidak_menampilkan_opsi_ingat_saya(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertDontSee('Ingat saya');
        $response->assertDontSee('name="remember"', false);
    }

    public function test_login_tidak_memasang_cookie_remember_meski_dikirim_paksa(): void
    {
        User::factory()->admin()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'admin',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertCookieMissing(auth()->guard('web')->getRecallerName());
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
