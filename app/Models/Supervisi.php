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

        // Clear cache when supervisi is created
        static::created(function () {
            \App\Helpers\CacheHelper::clearSupervisiCache();
        });

        // Only clear cache when status changes (performance optimization)
        static::updated(function ($supervisi) {
            if ($supervisi->isDirty('status')) {
                \App\Helpers\CacheHelper::clearSupervisiCache();
            }
        });

        static::deleting(function ($supervisi) {
            // Delete related files before records
            foreach ($supervisi->dokumenEvaluasi as $dokumen) {
                if ($dokumen->path_file) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($dokumen->path_file);
                }
            }
            
            // Delete related records
            $supervisi->dokumenEvaluasi()->delete();
            $supervisi->prosesPembelajaran()->delete();
            $supervisi->feedback()->delete();
            
            // Clear cache
            \App\Helpers\CacheHelper::clearSupervisiCache();
        });
    }

    // Query Scopes for efficient filtering
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeExcludeDrafts($query)
    {
        return $query->whereNotIn('status', [self::STATUS_DRAFT]);
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