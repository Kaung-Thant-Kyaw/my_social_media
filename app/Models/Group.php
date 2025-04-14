<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
        'cover_image',
        'creator_id',
        'privacy',
    ];

    // relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
