<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik',
        'nip', 
        'name',
        'email',
        'password',
        'role',
        'mata_pelajaran',
        'tingkatan',
        'jabatan',
        'unit_kerja'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the guru profile for this user (if role is guru)
     */
    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class);
    }

    /**
     * Get all supervisi records for this guru
     */
    public function supervisis(): HasMany
    {
        return $this->hasMany(Supervisi::class, 'guru_id');
    }

    /**
     * Get all evaluasi records for this guru
     */
    public function evaluasis(): HasMany
    {
        return $this->hasMany(Evaluasi::class, 'guru_id');
    }

    /**
     * Get all komentar made by this kepala sekolah
     */
    public function komentarsAsKepala(): HasMany
    {
        return $this->hasMany(Komentar::class, 'kepala_id');
    }

    /**
     * Get all laporan for this guru
     */
    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class, 'guru_id');
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a guru
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Check if user is kepala sekolah
     */
    public function isKepalaSekolah(): bool
    {
        return $this->role === 'kepala';
    }

    /**
     * Scope a query to only include guru users.
     */
    public function scopeGuru($query)
    {
        return $query->where('role', 'guru');
    }

    /**
     * Scope a query to only include kepala users.
     */
    public function scopeKepala($query)
    {
        return $query->where('role', 'kepala');
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Get the user's full name with title if available
     */
    public function getFullNameAttribute(): string
    {
        $name = $this->name;
        if ($this->jabatan) {
            $name = $this->jabatan . ' ' . $name;
        }
        return $name;
    }

    /**
     * Get the user's role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'guru' => 'Guru',
            'kepala' => 'Kepala Sekolah',
            default => 'User'
        };
    }

    /**
     * Get dashboard route based on role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            'guru' => 'guru.dashboard',
            'kepala' => 'kepala.dashboard',
            default => 'dashboard'
        };
    }
}