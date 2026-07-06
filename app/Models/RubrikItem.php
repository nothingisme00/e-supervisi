<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RubrikItem extends Model
{
    protected $fillable = [
        'kode', 'section', 'section_label', 'kelompok_nomor', 'kelompok_label', 'sub_label', 'urutan', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
