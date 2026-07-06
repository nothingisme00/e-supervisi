<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ColorTokenMigrationTest extends TestCase
{
    /**
     * V2: seluruh view memakai skala token primary (teal) dari @theme,
     * tidak ada lagi kelas hue indigo/purple/violet hardcode.
     */
    public function test_views_use_primary_tokens_not_indigo_purple(): void
    {
        $offenders = [];
        $files = File::allFiles(resource_path('views'));
        $files[] = new \SplFileInfo(resource_path('css/app.css'));

        foreach ($files as $file) {
            if (! preg_match('/\.(blade\.php|css)$/', $file->getFilename())) {
                continue;
            }
            $content = file_get_contents($file->getPathname());
            if (preg_match('/indigo|purple|violet|#6366f1|#4f46e5|#4338ca|#a855f7|#8b5cf6|#7c3aed|#a78bfa|99,\s*102,\s*241/i', $content)) {
                $offenders[] = str_replace(resource_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
            }
        }

        $this->assertSame([], $offenders, 'File masih memakai hue indigo/purple/violet: '.implode(', ', $offenders));
    }
}
