<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Modul extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'deskripsi', 'modul_kategori_id', 'file_path', 'thumbnail_path', 'jumlah_halaman', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jumlah_halaman' => 'integer',
    ];

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? Storage::disk('public')->url($this->thumbnail_path) : null;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(ModulKategori::class, 'modul_kategori_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ModulVideo::class);
    }
}
