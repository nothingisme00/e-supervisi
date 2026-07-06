<?php

namespace Tests\Unit\Models;

use App\Models\Modul;
use App\Models\ModulKategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_active_excludes_inactive_modul(): void
    {
        Modul::factory()->create(['is_active' => true]);
        Modul::factory()->create(['is_active' => false]);

        $this->assertSame(1, Modul::active()->count());
    }

    public function test_modul_belongs_to_kategori(): void
    {
        $kategori = ModulKategori::factory()->create(['nama' => 'Pedagogik']);
        $modul = Modul::factory()->create(['modul_kategori_id' => $kategori->id]);

        $this->assertSame('Pedagogik', $modul->kategori->nama);
    }

    public function test_kategori_scope_active_excludes_inactive(): void
    {
        ModulKategori::factory()->create(['is_active' => true]);
        ModulKategori::factory()->create(['is_active' => false]);

        $this->assertSame(1, ModulKategori::active()->count());
    }
}
