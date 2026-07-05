<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndonesianUiTextTest extends TestCase
{
    use RefreshDatabase;

    /**
     * R11: tidak ada sisa label status/teks Inggris di UI berbahasa Indonesia
     * (dashboard admin + modal panduan layout + home guru).
     */
    public function test_ui_has_no_leftover_english_status_labels(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertDontSee('Submitted')
            ->assertDontSee('Completed')
            ->assertDontSee('In Progress')
            ->assertDontSee('Active Users');

        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $this->actingAs($guru)->get(route('guru.home'))
            ->assertDontSee('Quick Navigation')
            ->assertDontSee('Status: Submitted')
            ->assertDontSee('Revision Requested')
            ->assertDontSee('Status: Approved');
    }
}
