<?php

namespace Tests\Support;

use Barryvdh\DomPDF\Facade\Pdf;

trait MakesPdfFixture
{
    /** Hasilkan konten biner PDF valid dengan jumlah halaman tertentu (via dompdf). */
    protected function pdfContent(int $pages = 2): string
    {
        $html = implode(
            '<div style="page-break-after: always;"></div>',
            array_fill(0, $pages, '<p>Halaman uji</p>')
        );

        return Pdf::loadHTML($html)->output();
    }
}
