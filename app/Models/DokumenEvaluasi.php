<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'dokumen_evaluasi';

    protected $fillable = [
        'supervisi_id',
        'jenis_dokumen',
        'nama_file',
        'path_file',
        'tipe_file',
        'ukuran_file'
    ];

    // Relationships
    public function supervisi()
    {
        return $this->belongsTo(Supervisi::class);
    }
}
