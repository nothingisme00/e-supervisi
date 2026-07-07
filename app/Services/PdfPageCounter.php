<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class PdfPageCounter
{
    /**
     * Hitung jumlah halaman PDF di path absolut.
     *
     * @throws \InvalidArgumentException bila file bukan PDF valid atau 0 halaman
     */
    public function count(string $absolutePath): int
    {
        try {
            $document = (new Parser())->parseFile($absolutePath);
            $pages = count($document->getPages());
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException('PDF tidak dapat dibaca: ' . $e->getMessage(), 0, $e);
        }

        if ($pages < 1) {
            throw new \InvalidArgumentException('PDF tidak memiliki halaman.');
        }

        return $pages;
    }
}
