<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class IndonesianDateFormatTest extends TestCase
{
    /**
     * V8: tanggal di view tidak boleh memakai format() dengan nama bulan
     * Inggris (F/M) — wajib translatedFormat() agar mengikuti APP_LOCALE=id.
     */
    public function test_views_use_translated_date_format(): void
    {
        $offenders = [];

        foreach (File::allFiles(resource_path('views')) as $file) {
            if (! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }
            $content = file_get_contents($file->getPathname());
            if (preg_match("/->format\\('[^']*[FM][^']*'\\)|date\\('[^']*F[^']*'\\)/", $content)) {
                $offenders[] = str_replace(resource_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
            }
        }

        $this->assertSame([], $offenders, 'View masih memakai nama bulan Inggris via format()/date(): '.implode(', ', $offenders));
    }
}
