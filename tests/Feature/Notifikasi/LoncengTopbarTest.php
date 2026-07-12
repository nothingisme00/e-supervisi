<?php

namespace Tests\Feature\Notifikasi;

use App\Models\User;
use App\Notifications\PengingatSupervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoncengTopbarTest extends TestCase
{
    use RefreshDatabase;

    public function test_lonceng_tampil_di_topbar_guru(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $this->actingAs($guru)->get(route('guru.home'))
            ->assertStatus(200)
            ->assertSee('id="notif-dropdown-btn"', false);
    }

    public function test_badge_menampilkan_jumlah_belum_dibaca(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);
        $guru->notify(new PengingatSupervisi());
        $guru->notify(new PengingatSupervisi());

        $this->actingAs($guru)->get(route('guru.home'))
            ->assertSee('data-notif-badge', false)
            ->assertSee('Pengingat pengisian supervisi');
    }

    /**
     * V-shell: dropdown notifikasi harus punya transisi buka/tutup halus
     * (opacity+scale) seragam dengan dropdown profil, bukan cuma toggle
     * `hidden` instan.
     */
    public function test_notif_dropdown_has_smooth_open_close_transition(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $expectedToggleScript = <<<'JS'
        if (notifDropdownBtn && notifDropdownMenu) {
            notifDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();

                if (notifDropdownMenu.classList.contains('hidden')) {
                    // Show dropdown
                    notifDropdownMenu.classList.remove('hidden');
                    setTimeout(() => {
                        notifDropdownMenu.classList.remove('scale-95', 'opacity-0');
                        notifDropdownMenu.classList.add('scale-100', 'opacity-100');
                    }, 10);
                } else {
                    // Hide dropdown
                    notifDropdownMenu.classList.remove('scale-100', 'opacity-100');
                    notifDropdownMenu.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        notifDropdownMenu.classList.add('hidden');
                    }, 200);
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!notifDropdownBtn.contains(e.target) && !notifDropdownMenu.contains(e.target)) {
                    if (!notifDropdownMenu.classList.contains('hidden')) {
                        notifDropdownMenu.classList.remove('scale-100', 'opacity-100');
                        notifDropdownMenu.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => {
                            notifDropdownMenu.classList.add('hidden');
                        }, 200);
                    }
                }
            });
        }
JS;

        $this->actingAs($guru)->get(route('guru.home'))
            ->assertSee('id="notif-dropdown-menu" class="hidden absolute right-0 mt-2 w-80 max-w-[calc(100vw-1.5rem)] bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50 origin-top-right transform transition-all duration-200 ease-out scale-95 opacity-0"', false)
            ->assertSee($expectedToggleScript, false);
    }
}
