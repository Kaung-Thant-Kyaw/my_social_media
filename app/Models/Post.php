<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'visibility',
    ];

    protected $casts = [
        'visibility' => 'string',
    ];


    // relationships

    // a post belongs to auser
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // a post can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // a post can have many images
    public function media()
    {
        return $this->hasMany(PostMedia::class);
    }

    //  a post can have many reactions
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
}
