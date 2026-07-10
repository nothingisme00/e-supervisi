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
        return \App\Support\VideoEmbed::youtubeEmbedUrl($this->youtube_url);
    }
}
