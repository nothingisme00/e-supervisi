<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutDivBalanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Markup tak seimbang (</div> berlebih) membuat browser menutup elemen
     * lebih awal dan "melempar" sisa konten ke <body> — footer modal panduan
     * admin pernah bocor jadi bar permanen di bawah footer halaman.
     * Jumlah <div dan </div> pada halaman ter-render harus sama persis.
     */
    public function test_admin_dashboard_has_balanced_div_tags(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $html = $this->actingAs($admin)->get(route('admin.dashboard'))->getContent();

        $this->assertDivTagsBalanced($html, 'admin.dashboard');
    }

    public function test_kepala_dashboard_has_balanced_div_tags(): void
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $html = $this->actingAs($kepala)->get(route('kepala.dashboard'))->getContent();

        $this->assertDivTagsBalanced($html, 'kepala.dashboard');
    }

    public function test_guru_home_has_balanced_div_tags(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $html = $this->actingAs($guru)->get(route('guru.home'))->getContent();

        $this->assertDivTagsBalanced($html, 'guru.home');
    }

    /**
     * Hitung tag <div> vs </div> di luar blok <script> (template literal JS
     * berisi string "<div" yang bukan tag sungguhan).
     */
    private function assertDivTagsBalanced(string $html, string $pageName): void
    {
        $htmlWithoutScripts = preg_replace('#<script\b[^>]*>.*?</script>#si', '', $html);

        $this->assertSame(
            preg_match_all('/<div\b/', $htmlWithoutScripts),
            preg_match_all('#</div>#', $htmlWithoutScripts),
            "Halaman {$pageName} punya <div>/</div> yang tidak seimbang"
        );
    }
}
