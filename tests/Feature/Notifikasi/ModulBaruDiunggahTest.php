<?php

namespace Tests\Feature\Notifikasi;

use App\Models\ModulKategori;
use App\Models\User;
use App\Notifications\ModulBaruDiunggah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\Support\MakesPdfFixture;
use Tests\TestCase;

class ModulBaruDiunggahTest extends TestCase
{
    use RefreshDatabase;
    use MakesPdfFixture;

    private function fakePdfUpload(int $pages = 2): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('modul.pdf', $this->pdfContent($pages));
    }

    public function test_unggah_modul_mengirim_notifikasi_ke_semua_guru_aktif(): void
    {
        Notification::fake();
        Storage::fake('local');

        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $guruA = User::factory()->guru()->create(['is_active' => true]);
        $guruB = User::factory()->guru()->create(['is_active' => true]);
        $guruNonaktif = User::factory()->guru()->create(['is_active' => false]);
        $kepala = User::factory()->kepalaSekolah()->create();
        $kategori = ModulKategori::factory()->create();

        $this->actingAs($admin)->post(route('admin.modul.store'), [
            'judul' => 'Modul Notifikasi',
            'modul_kategori_id' => $kategori->id,
            'file' => $this->fakePdfUpload(),
        ]);

        Notification::assertSentTo($guruA, ModulBaruDiunggah::class);
        Notification::assertSentTo($guruB, ModulBaruDiunggah::class);
        Notification::assertNotSentTo($guruNonaktif, ModulBaruDiunggah::class);
        Notification::assertNotSentTo($kepala, ModulBaruDiunggah::class);
    }

    public function test_data_notifikasi_berisi_judul_pesan_ikon_dan_url(): void
    {
        $kategori = ModulKategori::factory()->create();
        $modul = \App\Models\Modul::factory()->create([
            'judul' => 'Modul Belajar TDD',
            'modul_kategori_id' => $kategori->id,
        ]);
        $guru = User::factory()->guru()->create();

        $data = (new ModulBaruDiunggah($modul))->toArray($guru);

        $this->assertSame('Modul ajar baru', $data['judul']);
        $this->assertStringContainsString('Modul Belajar TDD', $data['pesan']);
        $this->assertSame('modul', $data['ikon']);
        $this->assertSame(route('guru.modul.index'), $data['url']);
    }
}
