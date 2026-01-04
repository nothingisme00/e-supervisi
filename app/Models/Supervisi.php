<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisi extends Model
{
    use HasFactory;

    protected $table = 'supervisi';

    protected $fillable = [
        'user_id',
        'status',
        'tanggal_supervisi',
        'catatan',
        'reviewed_by',
        'reviewed_at'
    ];

    protected $casts = [
        'tanggal_supervisi' => 'date',
        'reviewed_at' => 'datetime'
    ];

    // Boot method for cascade delete and cache clearing
    protected static function boot()
    {
        parent::boot();

        // Clear cache when supervisi is created, updated, or deleted
        static::created(function () {
            \App\Helpers\CacheHelper::clearSupervisiCache();
        });

        static::updated(function () {
            \App\Helpers\CacheHelper::clearSupervisiCache();
        });

        static::deleting(function ($supervisi) {
            // Delete related records
            $supervisi->dokumenEvaluasi()->delete();
            $supervisi->prosesPembelajaran()->delete();
            $supervisi->feedback()->delete();
            
            // Clear cache
            \App\Helpers\CacheHelper::clearSupervisiCache();
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function dokumenEvaluasi()
    {
        return $this->hasMany(DokumenEvaluasi::class);
    }

    public function prosesPembelajaran()
    {
        return $this->hasOne(ProsesPembelajaran::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }
}