<?php

namespace App\Notifications;

use App\Models\Supervisi;
use Illuminate\Notifications\Notification;

class SupervisiPerluDireview extends Notification
{
    public function __construct(public Supervisi $supervisi)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul' => 'Supervisi perlu direview',
            'pesan' => $this->supervisi->user->name . ' mengirim supervisi untuk ditinjau.',
            'ikon' => 'review',
            'url' => route('kepala.evaluasi.show', $this->supervisi->id),
        ];
    }
}
