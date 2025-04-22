<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    protected $fillable = [
        'user_id',
        'file_path',
        'order',
    ];

    // post media belongs to a post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
