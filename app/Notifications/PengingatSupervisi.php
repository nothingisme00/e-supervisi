<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class PengingatSupervisi extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul' => 'Pengingat pengisian supervisi',
            'pesan' => 'Jangan lupa melengkapi dan mengirim supervisi Anda.',
            'ikon' => 'pengingat',
            'url' => route('guru.home'),
        ];
    }
}
