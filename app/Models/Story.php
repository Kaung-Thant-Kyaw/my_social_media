<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'file_path',
        'expired_at',
    ];

    // relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
