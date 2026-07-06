<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Pengaman: bila bootstrap/cache/config.php ada, config cache dev
     * menimpa .env.testing dan RefreshDatabase menjalankan migrate:fresh
     * ke MySQL dev (insiden 2026-07-06: database dev terhapus).
     * Pengecekan WAJIB sebelum parent::setUp() — trait RefreshDatabase
     * dijalankan di dalamnya, jadi memeriksa sesudahnya sudah terlambat.
     */
    protected function setUp(): void
    {
        if (! $this->app) {
            $this->refreshApplication();
        }

        if (config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:') {
            self::fail(
                'Test dibatalkan SEBELUM menyentuh database: koneksi bukan sqlite :memory: ('
                .config('database.default').'). Jalankan "php artisan config:clear" '
                .'lalu ulangi — config cache dev sedang menimpa .env.testing.'
            );
        }

        parent::setUp();
    }
}
