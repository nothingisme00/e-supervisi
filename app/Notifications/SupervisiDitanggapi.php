<?php

namespace App\Notifications;

use App\Models\Supervisi;
use Illuminate\Notifications\Notification;

class SupervisiDitanggapi extends Notification
{
    /** @param string $jenis feedback|revisi|selesai */
    public function __construct(public Supervisi $supervisi, public string $jenis)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $map = [
            'feedback' => ['Ada tanggapan pada supervisi Anda', 'Supervisi Anda mendapat tanggapan dari peninjau.', 'feedback'],
            'revisi' => ['Supervisi Anda perlu direvisi', 'Peninjau meminta revisi pada supervisi Anda.', 'revisi'],
            'selesai' => ['Supervisi Anda telah dinilai', 'Penilaian supervisi Anda sudah selesai.', 'nilai'],
        ];
        [$judul, $pesan, $ikon] = $map[$this->jenis];

        return [
            'judul' => $judul,
            'pesan' => $pesan,
            'ikon' => $ikon,
            'url' => route('guru.supervisi.detail', $this->supervisi->id),
        ];
    }
}
