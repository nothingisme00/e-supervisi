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
        'parent_id',
        'komentar',
        'rating',
        'is_revision_request'
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

    // Parent comment (for replies)
    public function parent()
    {
        return $this->belongsTo(Feedback::class, 'parent_id');
    }

    // Child comments (replies) - without recursive eager loading to prevent N+1
    public function replies()
    {
        return $this->hasMany(Feedback::class, 'parent_id')->latest();
    }

    // Get replies with user (for controlled eager loading)
    public function repliesWithUser()
    {
        return $this->hasMany(Feedback::class, 'parent_id')->with('user:id,name,role')->latest();
    }
}
