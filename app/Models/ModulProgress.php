<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModulProgress extends Model
{
    protected $table = 'modul_progress';

    protected $fillable = ['user_id', 'modul_id', 'halaman_terjauh', 'terakhir_dibuka_at'];

    protected $casts = [
        'halaman_terjauh' => 'integer',
        'terakhir_dibuka_at' => 'datetime',
    ];

    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Persen baca 0-100, dihitung saat ditampilkan (tahan pergantian PDF). */
    public function persen(): int
    {
        $total = $this->modul->jumlah_halaman;

        if ($total < 1) {
            return 0;
        }

        return max(0, min(100, (int) round($this->halaman_terjauh / $total * 100)));
    }
}
