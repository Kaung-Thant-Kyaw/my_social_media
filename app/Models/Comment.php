<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'post-id',
        'body',
    ];

    // relationships

    // a comment belongs to a post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // a comment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // a comment has many reactions
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
}
