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

        return $this->requirePages($pages);
    }

    /**
     * Pastikan jumlah halaman minimal 1. Dipisah agar guard "tanpa halaman"
     * bisa diuji langsung — Document::getPages() melempar untuk dokumen
     * tanpa halaman sehingga path ini tak terjangkau lewat parse file.
     *
     * @throws \InvalidArgumentException bila $pageCount < 1
     */
    public function requirePages(int $pageCount): int
    {
        if ($pageCount < 1) {
            throw new \InvalidArgumentException('PDF tidak memiliki halaman.');
        }

        return $pageCount;
    }
}
