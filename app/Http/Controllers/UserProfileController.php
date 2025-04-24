<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    // user profile page
    public function show($user)
    {
        $user = User::findOrFail($user);
        $posts = $user->posts()->UserProfile($user)->latest()->get();
        return view('user.profile.show', compact('user', 'posts'));
    }

    // user profile edit page
    public function edit($user)
    {
        $user = User::findOrFail($user);
        return view('user.profile.edit', compact('user'));
    }

    public function changeProfilePicture(Request $request)
    {
        return $this->handleImageUpdate($request, 'avatar', 'profile_pictures');
    }

    public function changeCoverPicture(Request $request)
    {
        return $this->handleImageUpdate($request, 'cover', 'cover_pictures');
    }

    /**
     * Handle user image updates (profile or cover).
     */
    protected function handleImageUpdate(Request $request, string $field, string $folder)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            $field => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ensure folder exists
        $folderPath = public_path($folder);
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true);
        }

        // Delete old file if exists
        if ($user->$field) {
            $oldPath = $folderPath . '/' . $user->$field;
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        // Save new file
        $image = $request->file($field);
        $fileName = time() . '_' . $image->getClientOriginalName();
        $image->move($folderPath, $fileName);

        // Update user field
        $user->$field = $fileName;
        $user->save();

        return back()->with('status', ucfirst($field) . ' photo updated successfully.');
    }

    // update user profile
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
        ]);
        $user->update($validated);
        return redirect()->route('user.profile.show', $user)->with('status', 'Profile updated successfully.');
    }

    // change password page
    public function changePasswordPage(User $user)
    {
        return view('user.profile.changePassword', compact('user'));
    }

    // change password
    public function changePassword(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */

        $user = Auth::user();
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect()->route('user.profile.show', $user)->with('status', 'Password updated successfully.');
        } else {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
    }
}
