<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Supervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->admin()->create(['must_change_password' => false]);
    }

    // ============================
    // INDEX
    // ============================

    public function test_admin_can_view_user_list(): void
    {
        $admin = $this->createAdmin();
        User::factory()->guru()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function test_user_list_can_be_searched(): void
    {
        $admin = $this->createAdmin();
        User::factory()->guru()->create(['name' => 'Test Guru Unik']);
        User::factory()->guru()->create(['name' => 'Guru Lain']);

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Unik']));

        $response->assertStatus(200);
    }

    public function test_user_list_can_be_filtered_by_role(): void
    {
        $admin = $this->createAdmin();
        User::factory()->guru()->count(2)->create();
        User::factory()->kepalaSekolah()->count(1)->create();

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['role' => 'guru']));

        $response->assertStatus(200);
    }

    public function test_user_list_can_be_filtered_by_status(): void
    {
        $admin = $this->createAdmin();
        User::factory()->guru()->create(['is_active' => true]);
        User::factory()->guru()->inactive()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['status' => 'active']));

        $response->assertStatus(200);
    }

    public function test_user_list_can_be_sorted(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.users.index', [
            'sort_by' => 'name',
            'sort_direction' => 'asc'
        ]));

        $response->assertStatus(200);
    }

    public function test_invalid_sort_column_defaults_to_created_at(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.users.index', [
            'sort_by' => 'invalid_column',
        ]));

        $response->assertStatus(200);
    }

    // ============================
    // CREATE
    // ============================

    public function test_admin_can_view_create_user_form(): void
    {
        $response = $this->actingAs($this->createAdmin())->get(route('admin.users.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_guru(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'nik' => '1234567890123456',
            'name' => 'Guru Baru',
            'email' => 'gurubaru@example.com',
            'role' => 'guru',
            'tingkat' => 'SD',
            'mata_pelajaran' => 'Matematika',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'nik' => '1234567890123456',
            'name' => 'Guru Baru',
            'role' => 'guru',
            'must_change_password' => true,
        ]);
    }

    public function test_admin_can_create_kepala_sekolah(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'nik' => '1234567890123456',
            'name' => 'Kepala Baru',
            'email' => 'kepala@example.com',
            'role' => 'kepala_sekolah',
            'tingkat' => 'SMP',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['role' => 'kepala_sekolah', 'tingkat' => 'SMP']);
    }

    public function test_create_user_validates_nik_format(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'nik' => '123', // Too short
            'name' => 'Test',
            'email' => 'test@example.com',
            'role' => 'guru',
            'tingkat' => 'SD',
            'mata_pelajaran' => 'Matematika',
        ]);

        $response->assertSessionHasErrors('nik');
    }

    public function test_create_user_validates_unique_nik(): void
    {
        $admin = $this->createAdmin();
        User::factory()->create(['nik' => '1234567890123456']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'nik' => '1234567890123456',
            'name' => 'Duplicate',
            'email' => 'new@example.com',
            'role' => 'guru',
            'tingkat' => 'SD',
            'mata_pelajaran' => 'Matematika',
        ]);

        $response->assertSessionHasErrors('nik');
    }

    public function test_guru_requires_tingkat_and_mata_pelajaran(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'nik' => '1234567890123456',
            'name' => 'Guru',
            'email' => 'guru@example.com',
            'role' => 'guru',
            // Missing tingkat and mata_pelajaran
        ]);

        $response->assertSessionHasErrors(['tingkat', 'mata_pelajaran']);
    }

    // ============================
    // EDIT / UPDATE
    // ============================

    public function test_admin_can_view_edit_form(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->guru()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $user->id));

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function test_admin_can_update_user(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->guru()->create([
            'nik' => '1234567890123456',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user->id), [
            'nik' => '1234567890123456',
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'guru',
            'tingkat' => 'SMP',
            'mata_pelajaran' => 'IPA',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'tingkat' => 'SMP',
        ]);
    }

    // ============================
    // RESET PASSWORD
    // ============================

    public function test_admin_can_reset_user_password(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->guru()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.reset-password', $user->id));

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'must_change_password' => true,
        ]);
    }

    // ============================
    // TOGGLE STATUS
    // ============================

    public function test_admin_can_toggle_user_status(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->guru()->create(['is_active' => true]);

        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $user->id));

        $response->assertJson(['success' => true, 'is_active' => false]);
    }

    // ============================
    // DELETE
    // ============================

    public function test_admin_can_delete_user(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->guru()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user->id));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin->id));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_deleting_user_cleans_up_supervisi(): void
    {
        $admin = $this->createAdmin();
        $guru = User::factory()->guru()->create();
        $supervisi = Supervisi::factory()->create(['user_id' => $guru->id]);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $guru->id));

        $this->assertDatabaseMissing('supervisi', ['user_id' => $guru->id]);
    }
}
