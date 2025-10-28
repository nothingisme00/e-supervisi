<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nik', 'name', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // relasi bila role = guru
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    // komentar jika kepala
    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'kepala_id');
    }
}
