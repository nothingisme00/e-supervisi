<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'supervisi_id',
        'user_id',
        'komentar',
        'rating'
    ];

    // Relationships
    public function supervisi()
    {
        return $this->belongsTo(Supervisi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
