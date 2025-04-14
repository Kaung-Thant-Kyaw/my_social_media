<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'token',
        'refresh_token',
        'expired_at'
    ];
    protected $casts = [
        'expired_at' => 'datetime',
    ];

    // Relationship

    // A social account belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
