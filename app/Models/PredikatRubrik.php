<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PredikatRubrik extends Model
{
    protected $table = 'predikat_rubrik';

    protected $fillable = ['kode', 'label', 'batas_minimal', 'urutan'];
}
