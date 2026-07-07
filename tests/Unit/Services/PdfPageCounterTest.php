<?php

namespace Tests\Unit\Services;

use App\Services\PdfPageCounter;
use Tests\Support\MakesPdfFixture;
use Tests\TestCase;

class PdfPageCounterTest extends TestCase
{
    use MakesPdfFixture;

    private function tempFile(string $content): string
    {
        $path = tempnam(sys_get_temp_dir(), 'pdftest');
        file_put_contents($path, $content);

        return $path;
    }

    public function test_menghitung_jumlah_halaman_pdf(): void
    {
        $path = $this->tempFile($this->pdfContent(3));

        $this->assertSame(3, (new PdfPageCounter())->count($path));

        unlink($path);
    }

    public function test_melempar_exception_untuk_file_bukan_pdf(): void
    {
        $path = $this->tempFile('ini bukan pdf');

        $this->expectException(\InvalidArgumentException::class);

        try {
            (new PdfPageCounter())->count($path);
        } finally {
            unlink($path);
        }
    }

    public function test_require_pages_melempar_exception_saat_nol(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new PdfPageCounter())->requirePages(0);
    }

    public function test_require_pages_mengembalikan_jumlah_saat_valid(): void
    {
        $this->assertSame(3, (new PdfPageCounter())->requirePages(3));
    }
}
