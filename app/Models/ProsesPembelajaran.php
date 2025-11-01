<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsesPembelajaran extends Model
{
    use HasFactory;

    protected $table = 'proses_pembelajaran';

    protected $fillable = [
        'supervisi_id',
        'link_video',
        'link_meeting',
        'refleksi_1',
        'refleksi_2',
        'refleksi_3',
        'refleksi_4',
        'refleksi_5'
    ];

    // Relationships
    public function supervisi()
    {
        return $this->belongsTo(Supervisi::class);
    }
}
