<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test halaman login dapat diakses
     */
    public function test_login_page_can_be_accessed(): void
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test admin dapat login dengan kredensial yang benar
     */
    public function test_admin_can_login_with_correct_credentials(): void
    {
        // Buat user admin
        $admin = User::create([
            'nik' => '1234567890123456',
            'name' => 'Administrator',
            'email' => 'admin@esupervisi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test guru dapat login dengan kredensial yang benar
     */
    public function test_guru_can_login_with_correct_credentials(): void
    {
        $guru = User::create([
            'nik' => '1234567890123457',
            'name' => 'Guru Test',
            'email' => 'guru@test.com',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123457',
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $response->assertRedirect(route('guru.home'));
        $this->assertAuthenticatedAs($guru);
    }

    /**
     * Test kepala sekolah dapat login dengan kredensial yang benar
     */
    public function test_kepala_sekolah_can_login_with_correct_credentials(): void
    {
        $kepala = User::create([
            'nik' => '1234567890123458',
            'name' => 'Kepala Sekolah Test',
            'email' => 'kepala@test.com',
            'password' => Hash::make('password123'),
            'role' => 'kepala_sekolah',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123458',
            'password' => 'password123',
            'role' => 'kepala_sekolah',
        ]);

        $response->assertRedirect(route('kepala.dashboard'));
        $this->assertAuthenticatedAs($kepala);
    }

    /**
     * Test login gagal dengan password salah
     */
    public function test_login_fails_with_incorrect_password(): void
    {
        User::create([
            'nik' => '1234567890123456',
            'name' => 'Administrator',
            'email' => 'admin@esupervisi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'wrongpassword',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('nik');
        $this->assertGuest();
    }

    /**
     * Test login gagal dengan role yang tidak sesuai
     */
    public function test_login_fails_with_incorrect_role(): void
    {
        User::create([
            'nik' => '1234567890123456',
            'name' => 'Administrator',
            'email' => 'admin@esupervisi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'admin123',
            'role' => 'guru', // Role salah
        ]);

        $response->assertSessionHasErrors('nik');
        $this->assertGuest();
    }

    /**
     * Test login gagal jika user tidak aktif
     */
    public function test_login_fails_if_user_is_inactive(): void
    {
        User::create([
            'nik' => '1234567890123456',
            'name' => 'Administrator',
            'email' => 'admin@esupervisi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => false, // User tidak aktif
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('nik');
        $this->assertGuest();
    }

    /**
     * Test user harus ganti password diredirect ke halaman change password
     */
    public function test_user_must_change_password_is_redirected(): void
    {
        $admin = User::create([
            'nik' => '1234567890123456',
            'name' => 'Administrator',
            'email' => 'admin@esupervisi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => true,
        ]);

        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('change-password'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test validasi NIK wajib diisi
     */
    public function test_nik_is_required(): void
    {
        $response = $this->post('/login', [
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('nik');
    }

    /**
     * Test validasi password wajib diisi
     */
    public function test_password_is_required(): void
    {
        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test validasi role wajib diisi
     */
    public function test_role_is_required(): void
    {
        $response = $this->post('/login', [
            'nik' => '1234567890123456',
            'password' => 'admin123',
        ]);

        $response->assertSessionHasErrors('role');
    }
}
