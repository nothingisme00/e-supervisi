<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        return User::factory()->guru()->create([
            'must_change_password' => false,
            'password' => Hash::make('oldpassword123'),
        ]);
    }

    public function test_authenticated_user_can_view_settings(): void
    {
        $response = $this->actingAs($this->createUser())->get(route('settings.index'));
        $response->assertStatus(200);
    }

    public function test_user_can_update_profile(): void
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->post(route('settings.profile.update'), [
            'name' => 'Nama Baru',
            'email' => 'emailbaru@example.com',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Nama Baru']);
    }

    public function test_user_can_update_password(): void
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->post(route('settings.password.update'), [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_password_update_fails_with_wrong_current(): void
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->post(route('settings.password.update'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);
        $response->assertSessionHasErrors('current_password');
    }

    public function test_password_update_fails_with_same_password(): void
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->post(route('settings.password.update'), [
            'current_password' => 'oldpassword123',
            'password' => 'oldpassword123',
            'password_confirmation' => 'oldpassword123',
        ]);
        $response->assertSessionHasErrors('password');
    }

    public function test_profile_update_validates_name(): void
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->post(route('settings.profile.update'), [
            'name' => '',
        ]);
        $response->assertSessionHasErrors('name');
    }

    public function test_unauthenticated_user_redirected(): void
    {
        $response = $this->get(route('settings.index'));
        $response->assertRedirect('/login');
    }
}
