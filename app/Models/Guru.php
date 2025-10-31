<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','mata_pelajaran','tingkat','sekolah'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisis()
    {
        return $this->hasMany(Supervisi::class);
    }
}
