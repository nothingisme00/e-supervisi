<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Komentar extends Model
{
    use HasFactory;
    protected $fillable = ['supervisi_id','kepala_id','komentar','status_approval'];

    public function supervisi()
    {
        return $this->belongsTo(Supervisi::class);
    }

    public function kepala()
    {
        return $this->belongsTo(User::class, 'kepala_id');
    }
}
