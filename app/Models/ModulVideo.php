<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModulVideo extends Model
{
    protected $fillable = ['modul_id', 'judul', 'youtube_url'];

    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([A-Za-z0-9_-]{11})#', $this->youtube_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        return null;
    }
}
