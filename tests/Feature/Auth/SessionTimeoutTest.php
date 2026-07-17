<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SessionTimeoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bug: user yang kembali via cookie "Ingat saya" (viaRemember) ikut
     * ditendang SessionTimeout karena middleware memeriksa baris sesi LAMA
     * milik user (yang memang sudah basi), padahal sesi HTTP saat ini baru
     * dibuat dan belum punya baris di tabel sessions.
     */
    public function test_user_kembali_via_remember_me_tetap_login_meski_sesi_lama_basi(): void
    {
        config(['session.driver' => 'database']);

        $user = User::factory()->admin()->create();

        // Baris sesi lama yang sudah melewati idle timeout
        DB::table('sessions')->insert([
            'id' => 'sesi-lama-basi',
            'user_id' => $user->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode(serialize([])),
            'last_activity' => time() - ((int) config('session.lifetime') * 60) - 300,
        ]);

        // actingAs meniru kondisi viaRemember: user terautentikasi,
        // tapi sesi request ini belum tercatat di tabel sessions
        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
        $this->assertAuthenticated();
    }

    /**
     * Pesan "Sesi Anda telah berakhir..." dikirim sebagai flash 'warning',
     * tapi halaman login hanya merender flash 'success' — logout idle
     * jadi terasa misterius tanpa penjelasan.
     */
    public function test_halaman_login_menampilkan_flash_warning_sesi_berakhir(): void
    {
        $pesan = 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan login kembali.';

        $response = $this->withSession(['warning' => $pesan])->get(route('login'));

        $response->assertOk();
        $response->assertSee($pesan);
    }

    /**
     * Regresi gejala asli: "berhasil login tiba-tiba keluar lagi".
     * Login baru harus tetap bertahan walau di tabel sessions masih ada
     * baris sesi lama user yang sudah basi.
     */
    public function test_login_baru_tidak_ditendang_meski_ada_sesi_lama_basi(): void
    {
        config(['session.driver' => 'database']);

        $user = User::factory()->admin()->create([
            'nik' => '1234567890123456',
            'password' => bcrypt('password123'),
            'is_active' => true,
            'must_change_password' => false,
        ]);

        DB::table('sessions')->insert([
            'id' => 'sesi-kemarin-basi',
            'user_id' => $user->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode(serialize([])),
            'last_activity' => time() - ((int) config('session.lifetime') * 60) - 300,
        ]);

        $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'password123',
            'role' => 'admin',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))->assertOk();
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Kebijakan: idle timeout 30 menit. .env.testing tidak menyetel
     * SESSION_LIFETIME, jadi ini menguji fallback default config.
     */
    public function test_default_session_lifetime_adalah_30_menit(): void
    {
        $this->assertSame(30, (int) config('session.lifetime'));
    }

    /**
     * Pengaman regresi: sesi request INI yang sudah basi tetap ditendang
     * (idle timeout masih ditegakkan setelah query di-scope per sesi).
     */
    public function test_sesi_saat_ini_yang_basi_tetap_ditendang(): void
    {
        config(['session.driver' => 'database']);

        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $sessionId = \Illuminate\Support\Str::random(40);
        $store = app('session.store');
        $store->setId($sessionId);

        DB::table('sessions')->insert([
            'id' => $sessionId,
            'user_id' => $user->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode(serialize([])),
            'last_activity' => time() - ((int) config('session.lifetime') * 60) - 300,
        ]);

        $request = \Illuminate\Http\Request::create('/admin/dashboard', 'GET');
        $request->setLaravelSession($store);

        $response = (new \App\Http\Middleware\SessionTimeout())
            ->handle($request, fn () => response('ok'));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(route('login'), $response->headers->get('Location'));
        $this->assertGuest();
        $this->assertDatabaseMissing('sessions', ['id' => $sessionId]);
    }
}
