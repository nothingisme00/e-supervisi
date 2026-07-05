<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DarkModeCoverageTest extends TestCase
{
    /**
     * V10: setiap view yang memakai kartu terang (bg-white) wajib punya
     * varian dark: — tanpa itu halaman tampil putih di tema gelap
     * (ditemukan di admin/supervisi/detail saat QA screenshot).
     */
    public function test_views_with_light_cards_support_dark_mode(): void
    {
        $offenders = [];

        foreach (File::allFiles(resource_path('views')) as $file) {
            if (! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }
            $content = file_get_contents($file->getPathname());
            if (str_contains($content, 'bg-white') && ! str_contains($content, 'dark:')) {
                $offenders[] = str_replace(resource_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
            }
        }

        $this->assertSame([], $offenders, 'View terang tanpa dukungan dark mode: '.implode(', ', $offenders));
    }
}
