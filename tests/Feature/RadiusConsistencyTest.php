<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class RadiusConsistencyTest extends TestCase
{
    /**
     * Closeout overhaul visual: skala radius kanonik — kartu/panel/dropdown = xl,
     * tombol/input/select = lg, badge/pill/avatar = full, hero maks 2xl.
     * `rounded-md` dan `rounded-3xl` berada di luar skala dan dilarang di view
     * (dinormalisasi repo-wide: md -> lg, 3xl -> 2xl).
     */
    public function test_views_do_not_use_rounded_md_or_rounded_3xl(): void
    {
        $offenders = [];

        foreach (File::allFiles(resource_path('views')) as $file) {
            if (! preg_match('/\.blade\.php$/', $file->getFilename())) {
                continue;
            }

            $content = file_get_contents($file->getPathname());
            if (preg_match('/\brounded(?:-[trbl]{1,2})?-(?:md|3xl)\b/', $content)) {
                $offenders[] = str_replace(resource_path('views').DIRECTORY_SEPARATOR, '', $file->getPathname());
            }
        }

        $this->assertSame([], $offenders, 'View masih memakai rounded-md/rounded-3xl di luar skala radius: '.implode(', ', $offenders));
    }
}
