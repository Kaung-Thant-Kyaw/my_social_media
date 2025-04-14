<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPost extends Model
{
    protected $fillable = [
        'group_id',
        'user_id',
        'content',
        'image',
        'likes_count',
        'commments_count',
        'approved_at',
    ];
}
