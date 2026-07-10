<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiRubrikScore extends Model
{
    protected $table = 'evaluasi_rubrik_scores';

    protected $fillable = ['evaluasi_rubrik_id', 'rubrik_item_id', 'skor'];

    public function rubrikItem()
    {
        return $this->belongsTo(RubrikItem::class);
    }
}
