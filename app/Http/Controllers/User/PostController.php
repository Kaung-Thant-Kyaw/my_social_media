<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // create form
    public function create()
    {
        return view('user.posts.create');
    }

    // upload post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'nullable|string|max:500',
            'images' => 'required_without:content|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,avif|max:2048',
            'visibility' => 'required|in:public,friend,private',
        ], [
            'content.required_without' => 'Text or image is required',
            'images.*.image' => 'The file must be an image',
            'images.*.max' => 'The image size must be less than 2MB',
        ]);

        /** @var App\Models\User $user */
        $user = Auth::user();
        $post = $user->posts()->create([
            'content' => $validated['content'] ?? null,
            'visibility' => $validated['visibility'],
            'user_id' => Auth::id(),
        ]);

        $images = $request->file('images');
        if ($images) {
            foreach ($images as $index => $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('posts', $imageName, 'public');
                $post->media()->create([
                    'file_path' => $imageName,
                    'order' => $index,
                    'post_id' => $post->id,
                ]);
            }
        }
        return to_route('home')->with('success', 'Post created successfully');
    }
}
