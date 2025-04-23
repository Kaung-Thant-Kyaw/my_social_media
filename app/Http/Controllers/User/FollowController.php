<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // follow a user
    public function follow(User $user)
    {

        // prevent self-following
        if ((Auth::id() !== $user->id) && !Auth::user()->isFollowing($user)) {
            Auth::user()->following()->attach($user->id);
        }
        return back();
    }

    // unfollow a user
    public function unfollow(User $user)
    {
        // prevent self-unfollowing
        if ((Auth::id() !== $user->id) && Auth::user()->isFollowing($user)) {
            Auth::user()->following()->detach($user->id);
        }
        return back();
    }
}
