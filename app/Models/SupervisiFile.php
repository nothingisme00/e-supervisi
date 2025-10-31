<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupervisiFile extends Model
{
    use HasFactory;

    protected $fillable = ['supervisi_id','nama_berkas','file_path'];

    public function supervisi()
    {
        return $this->belongsTo(Supervisi::class);
    }
}
