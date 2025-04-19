<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    // user profile page
    public function show($user)
    {
        $user = User::findOrFail($user);
        return view('user.profile.show', compact('user'));
    }

    // user profile edit page
    public function edit($user)
    {
        $user = User::findOrFail($user);
        return view('user.profile.edit', compact('user'));
    }
}
