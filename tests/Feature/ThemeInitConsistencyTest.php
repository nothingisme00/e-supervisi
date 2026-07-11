<?php

namespace Tests\Feature;

use Tests\TestCase;

class ThemeInitConsistencyTest extends TestCase
{
    /**
     * Handler livewire:navigated di layout me-re-apply tema setelah navigasi
     * SPA. Logikanya WAJIB sama dengan partials/theme-init.blade.php:
     * localStorage menang, tanpa preferensi tersimpan fallback ke
     * prefers-color-scheme — BUKAN default 'light'. Regresi yang dijaga:
     * user OS-dark tanpa localStorage.theme dapat dark saat first paint
     * (via theme-init) lalu terlempar ke light pada klik navigasi pertama.
     */
    public function test_livewire_navigated_theme_reapply_follows_prefers_color_scheme(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/modern.blade.php'));

        $this->assertStringNotContainsString(
            "localStorage.getItem('theme') || 'light'",
            $layout,
            "Handler livewire:navigated masih default 'light' — harus fallback prefers-color-scheme seperti theme-init."
        );

        $this->assertStringContainsString(
            'prefers-color-scheme: dark',
            $layout,
            'Re-apply tema saat navigasi harus konsultasi prefers-color-scheme.'
        );
    }
}
