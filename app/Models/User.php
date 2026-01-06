<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_GURU = 'guru';
    const ROLE_KEPALA_SEKOLAH = 'kepala_sekolah';

    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
        'role',
        'tingkat',
        'mata_pelajaran',
        'is_active',
        'must_change_password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'must_change_password' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Boot method for cache clearing
    protected static function boot()
    {
        parent::boot();

        // Clear cache when user is created, updated, or deleted
        static::created(function () {
            \App\Helpers\CacheHelper::clearUserCache();
        });

        static::updated(function () {
            \App\Helpers\CacheHelper::clearUserCache();
        });

        static::deleted(function () {
            \App\Helpers\CacheHelper::clearUserCache();
        });
    }

    // Relationships
    public function supervisi()
    {
        return $this->hasMany(Supervisi::class);
    }

    public function feedbackGiven()
    {
        return $this->hasMany(Feedback::class);
    }

    // Helper Methods
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isGuru()
    {
        return $this->role === self::ROLE_GURU;
    }

    public function isKepalaSekolah()
    {
        return $this->role === self::ROLE_KEPALA_SEKOLAH;
    }
}