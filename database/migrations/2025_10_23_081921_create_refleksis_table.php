<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refleksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisi_id',
        'pertanyaan',
        'jawaban',
    ];

    public function supervisi()
    {
        return $this->belongsTo(Supervisi::class);
    }
}
