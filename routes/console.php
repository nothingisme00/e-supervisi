<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Pengingat pengisian supervisi: Senin (1) & Kamis (4) pukul 07:00
Schedule::command('notifikasi:pengingat-supervisi')->days(1, 4)->at('07:00');
