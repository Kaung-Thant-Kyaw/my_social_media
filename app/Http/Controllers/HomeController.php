<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // get posts from users the current user follows & own posts
        $posts = Post::Visibility()
            ->where(function ($query) {
                // get posts from user the current user follows
                $query->whereIn('user_id', function ($query) {
                    $query->select('following_id')
                        ->from('follows')
                        ->where('follower_id', auth()->user()->id);
                })
                    ->orWhere('user_id', auth()->user()->id);
            })
            ->with('user')
            ->latest()
            ->paginate(10);
        // dd($posts->toArray());

        // get suggested users to follow (exclude current user and users already followed)
        $suggestedUsers = User::whereNotIn('id', function ($query) {
            $query->select('following_id')
                ->from('follows')
                ->where('follower_id', auth()->user()->id);
        })
            ->where('id', '!=', auth()->user()->id)
            ->inRandomOrder()
            ->limit(5)
            ->get();
        // dd($suggestedUsers->toArray());

        // get the current user's followings
        $followings = auth()->user()->following()->get();
        return view('home.index', compact('posts', 'suggestedUsers', 'followings'));
    }
}
