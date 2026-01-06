<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisi extends Model
{
    use HasFactory;

    protected $table = 'supervisi';

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REVISION = 'revision';

    protected $fillable = [
        'user_id',
        'status',
        'tanggal_supervisi',
        'catatan',
        'reviewed_by',
        'reviewed_at',
        'needs_revision',
        'revision_notes'
    ];

    protected $casts = [
        'tanggal_supervisi' => 'date',
        'reviewed_at' => 'datetime',
        'needs_revision' => 'boolean'
    ];

    // Helper method untuk list semua status
    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_SUBMITTED,
            self::STATUS_UNDER_REVIEW,
            self::STATUS_COMPLETED,
            self::STATUS_REVISION,
        ];
    }

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