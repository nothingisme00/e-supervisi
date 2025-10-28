<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supervisi extends Model
{
    use HasFactory;

    protected $fillable = ['guru_id','judul','deskripsi','link_youtube','link_meet','status'];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function files()
    {
        return $this->hasMany(SupervisiFile::class);
    }

    public function refleksis()
    {
        return $this->hasMany(Refleksi::class);
    }

    public function komentar()
    {
        return $this->hasOne(Komentar::class);
    }
}
