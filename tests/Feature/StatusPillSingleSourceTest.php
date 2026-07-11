<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class StatusPillSingleSourceTest extends TestCase
{
    /**
     * R9: satu sumber kebenaran untuk pill status (draft/submitted/under_review/
     * revision/completed/aktif/nonaktif) adalah <x-status-badge>. Guard ini
     * mencegah view lain menghardcode ulang kombinasi warna + label status.
     *
     * Deteksi (pola pragmatis, line-based — mirip ColorTokenMigrationTest):
     * - "warna pill bespoke": kelas bg-(green|amber|blue|emerald|rose)-100 pada
     *   suatu baris.
     * - "kata status bespoke": salah satu dari Aktif/Nonaktif/Disubmit/Ditinjau/
     *   Draft/Revisi/Selesai/Review (mencakup varian huruf besar REVIEW/DITINJAU),
     *   dan kata itu harus berdiri sendiri sebagai isi elemen (diikuti langsung
     *   oleh penutup tag/kutip, bukan bagian dari frasa lain seperti
     *   "Revisi Diminta") — supaya tidak menangkap label tak terkait yang
     *   kebetulan memuat salah satu kata tsb.
     * - Kedua pola harus muncul berdekatan (baris yang sama atau baris
     *   berikutnya) supaya dihitung sebagai satu elemen pill yang sama.
     *
     * Sengaja TIDAK menangkap: components/status-badge.blade.php sendiri
     * (dikecualikan eksplisit), pemanggilan <x-status-badge>, atau pemakaian
     * warna bg-*-100 yang tak berdekatan dengan kata status (mis. ikon polos).
     */
    public function test_no_bespoke_status_pill_outside_status_badge_component(): void
    {
        // TODO Task 11: hapus setelah admin/dashboard.blade.php pakai <x-status-badge>
        // TODO Task 16: hapus setelah kepala/evaluasi/index.blade.php pakai <x-status-badge>
        // TODO Task 21: hapus setelah admin/modul/index.blade.php pakai <x-status-badge>
        $whitelist = [
            'admin/dashboard.blade.php',
            'kepala/evaluasi/index.blade.php',
            'admin/modul/index.blade.php',
        ];

        $colorPattern = '/bg-(?:green|amber|blue|emerald|rose)-100\b/i';
        $statusWordPattern = '/\b(?:Aktif|Nonaktif|Disubmit|Ditinjau|Draft|Revisi|Selesai|Review)\b(?=\s*(?:[<\'"]|$))/i';

        $offenders = [];

        foreach (File::allFiles(resource_path('views')) as $file) {
            if (! preg_match('/\.blade\.php$/', $file->getFilename())) {
                continue;
            }

            $relative = str_replace('\\', '/', str_replace(resource_path('views').DIRECTORY_SEPARATOR, '', $file->getPathname()));

            if ($relative === 'components/status-badge.blade.php') {
                continue;
            }

            $lines = file($file->getPathname());
            $lineCount = count($lines);

            for ($i = 0; $i < $lineCount; $i++) {
                if (! preg_match($colorPattern, $lines[$i])) {
                    continue;
                }

                $window = $lines[$i].($lines[$i + 1] ?? '');

                if (preg_match($statusWordPattern, $window)) {
                    $offenders[$relative] = true;
                    break;
                }
            }
        }

        $offenders = array_keys($offenders);
        $unexpected = array_values(array_diff($offenders, $whitelist));

        $this->assertSame([], $unexpected, 'View berikut menghardcode pill status di luar <x-status-badge>: '.implode(', ', $unexpected));
    }
}
