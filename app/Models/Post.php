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

    // visibility query scope for home page
    // this scope will be used to filter posts based on visibility
    public function scopeVisibility($query)
    {
        $user = auth()->user();

        return $query->where(function ($query) use ($user) {
            $query->where('visibility', 'public')
                ->orWhere(function ($query) use ($user) {
                    $query->where('visibility', 'private')
                        ->where('user_id', $user->id);
                });
        });
    }

    // scope filter for profile page
    public function scopeUserProfile($query, User $profileUser)
    {
        $viewer = auth()->user();
        // if the viewer is the profile owner, show all posts
        if ($viewer && $viewer->is($profileUser)) {
            return $query;
        }
        // if the viewer isn't the profile owner, show only public posts
        return $query->where('visibility', 'public');
    }


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
