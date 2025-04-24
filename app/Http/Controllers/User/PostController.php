<?php

namespace App\Http\Controllers\User;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // show post
    public function show(Post $post) {}

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
        ]);

        // logger("File Keys" . json_encode(array_keys($request->file('images'))));
        if ($request->hasFile('images')) {
            $images = array_values($request->file('images'));
            foreach ($images as $index => $image) {
                // logger("Processing image {$index}");
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('posts'), $imageName);
                $post->media()->create([
                    'file_path' => $imageName,
                    'order' => $index, // Order based on the current index
                ]);
            }
            // logger('created with media order' . $post->media()->pluck('order'));
        }

        return redirect()->route('home')->with('success', 'Post created successfully');
    }

    // edit form
    public function edit(Post $post)
    {
        return view('user.posts.edit', compact('post'));
    }


    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'nullable|string|max:500',
            'visibility' => 'required|in:public,friend,private',
            'images.*' => 'image|mimes:png,jpg,webp,avif|max:2048',
            'deleted_images' => 'nullable|string',
            'media_order' => 'nullable|array',
            'media_order.*' => 'string', // could be id or "new_0"
        ]);

        // Update post fields
        $post->update([
            'content' => $validated['content'],
            'visibility' => $validated['visibility'],
        ]);

        // Handle image deletions
        if (!empty($validated['deleted_images'])) {
            $idsToDelete = collect(explode(',', $validated['deleted_images']))->filter()->unique();
            foreach ($idsToDelete as $id) {
                $media = $post->media()->find($id);
                if ($media) {
                    @unlink(public_path('posts/' . $media->file_path));
                    $media->delete();
                }
            }
        }

        // Handle new image uploads
        $newMediaMap = []; // e.g., 'new_0' => 24
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $img) {
                $fileName = time() . '.' . $img->getClientOriginalExtension();
                $img->move(public_path('posts'), $fileName);
                $created = $post->media()->create([
                    'file_path' => $fileName,
                    'order' => 999 // temporary order
                ]);
                $newMediaMap["new_$i"] = $created->id;
            }
        }

        // Handle final media ordering
        if (!empty($validated['media_order'])) {
            foreach ($validated['media_order'] as $index => $ref) {
                $mediaId = is_numeric($ref) ? $ref : ($newMediaMap[$ref] ?? null);
                if ($mediaId) {
                    $post->media()->where('id', $mediaId)->update(['order' => $index]);
                }
            }
        }
        // logger('media order' . $post->media()->pluck('order'));
        return redirect()->route('home')->with('success', 'Post updated successfully.');
    }

    // post delete
    public function destroy(Post $post)
    {
        // Delete associated media files
        foreach ($post->media as $media) {
            $filePath = public_path('posts/' . $media->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $media->delete();
        }

        // Delete the post
        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted');
    }
}
