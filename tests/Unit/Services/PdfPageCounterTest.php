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

    public function test_melempar_exception_untuk_pdf_tanpa_halaman(): void
    {
        // PDF minimal valid secara struktur tapi pohon halamannya kosong (Count 0)
        $pdfKosong = "%PDF-1.4\n"
            . "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n"
            . "2 0 obj\n<< /Type /Pages /Kids [] /Count 0 >>\nendobj\n"
            . "xref\n0 3\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n"
            . "trailer\n<< /Size 3 /Root 1 0 R >>\nstartxref\n115\n%%EOF";
        $path = $this->tempFile($pdfKosong);

        $this->expectException(\InvalidArgumentException::class);

        try {
            (new PdfPageCounter())->count($path);
        } finally {
            unlink($path);
        }
    }
}
