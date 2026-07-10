<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\PengingatSupervisi;
use Illuminate\Console\Command;

class KirimPengingatSupervisi extends Command
{
    protected $signature = 'notifikasi:pengingat-supervisi';

    protected $description = 'Kirim pengingat pengisian supervisi ke guru yang belum mulai atau draftnya mangkrak';

    public function handle(): int
    {
        $guru = User::where('role', 'guru')
            ->where('is_active', true)
            ->whereDoesntHave('supervisi', function ($q) {
                $q->whereIn('status', ['submitted', 'under_review', 'completed']);
            })
            ->get();

        $terkirim = 0;
        foreach ($guru as $g) {
            $sudahAda = $g->unreadNotifications()
                ->where('type', PengingatSupervisi::class)
                ->exists();
            if ($sudahAda) {
                continue;
            }
            $g->notify(new PengingatSupervisi());
            $terkirim++;
        }

        $this->info("Pengingat terkirim ke {$terkirim} guru.");

        return self::SUCCESS;
    }
}
