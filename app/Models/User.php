<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'cover',
        'bio',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Model and relationships.
     */
    // a user can have multiple social accounts
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    // a user can have many posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // a user can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Check the current user is following the given user
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()
            ->where('following_id', $user->id)
            ->exists();
    }

    /**
     * Relationship: users that follow this user
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    /**
     * Relationship: users that this user follows
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follower_id',
            'following_id'
        );
    }
}
