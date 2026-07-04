<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Supervisi;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_role_constants(): void
    {
        $this->assertEquals('admin', User::ROLE_ADMIN);
        $this->assertEquals('guru', User::ROLE_GURU);
        $this->assertEquals('kepala_sekolah', User::ROLE_KEPALA_SEKOLAH);
    }

    public function test_is_admin_returns_true_for_admin(): void
    {
        $user = User::factory()->admin()->create();
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isGuru());
        $this->assertFalse($user->isKepalaSekolah());
    }

    public function test_is_guru_returns_true_for_guru(): void
    {
        $user = User::factory()->guru()->create();
        $this->assertTrue($user->isGuru());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isKepalaSekolah());
    }

    public function test_is_kepala_sekolah_returns_true_for_kepala(): void
    {
        $user = User::factory()->kepalaSekolah()->create();
        $this->assertTrue($user->isKepalaSekolah());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isGuru());
    }

    public function test_user_has_supervisi_relationship(): void
    {
        $user = User::factory()->guru()->create();
        $supervisi = Supervisi::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->supervisi);
        $this->assertTrue($user->supervisi->first()->is($supervisi));
    }

    public function test_user_has_feedback_given_relationship(): void
    {
        $user = User::factory()->admin()->create();
        $supervisi = Supervisi::factory()->submitted()->create();
        $feedback = Feedback::factory()->create([
            'user_id' => $user->id,
            'supervisi_id' => $supervisi->id,
        ]);

        $this->assertCount(1, $user->feedbackGiven);
    }

    public function test_user_fillable_attributes(): void
    {
        $user = User::factory()->guru()->create([
            'nik' => '1234567890123456',
            'name' => 'Test Guru',
            'email' => 'testguru@example.com',
            'role' => 'guru',
            'tingkat' => 'SD',
            'mata_pelajaran' => 'Matematika',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $this->assertEquals('1234567890123456', $user->nik);
        $this->assertEquals('Test Guru', $user->name);
        $this->assertEquals('testguru@example.com', $user->email);
        $this->assertEquals('guru', $user->role);
        $this->assertEquals('SD', $user->tingkat);
        $this->assertEquals('Matematika', $user->mata_pelajaran);
        $this->assertTrue($user->is_active);
        $this->assertFalse($user->must_change_password);
    }

    public function test_is_active_is_cast_to_boolean(): void
    {
        $user = User::factory()->create(['is_active' => 1]);
        $this->assertIsBool($user->is_active);
    }

    public function test_must_change_password_is_cast_to_boolean(): void
    {
        $user = User::factory()->create(['must_change_password' => 1]);
        $this->assertIsBool($user->must_change_password);
    }

    public function test_password_is_hidden(): void
    {
        $user = User::factory()->create();
        $this->assertArrayNotHasKey('password', $user->toArray());
    }

    public function test_inactive_factory_state(): void
    {
        $user = User::factory()->inactive()->create();
        $this->assertFalse($user->is_active);
    }
}
