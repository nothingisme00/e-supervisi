<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutStickyFooterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * V6: sticky footer cukup satu definisi (kelas Tailwind di elemen),
     * tanpa duplikat inline style / blok <style> custom di layout.
     */
    public function test_sticky_footer_has_single_definition(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $this->actingAs($guru)->get(route('guru.home'))
            ->assertDontSee('style="min-height:100vh', false)
            ->assertDontSee('style="margin-top:auto', false)
            ->assertDontSee('style="flex:1 1 auto', false)
            ->assertDontSee('#main-content > footer', false);
    }
}
