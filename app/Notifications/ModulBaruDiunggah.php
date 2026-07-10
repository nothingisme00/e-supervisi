<?php

namespace App\Notifications;

use App\Models\Modul;
use Illuminate\Notifications\Notification;

class ModulBaruDiunggah extends Notification
{
    public function __construct(public Modul $modul)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul' => 'Modul ajar baru',
            'pesan' => 'Modul baru "' . $this->modul->judul . '" telah tersedia untuk dibaca.',
            'ikon' => 'modul',
            'url' => route('guru.modul.index'),
        ];
    }
}
