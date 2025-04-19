<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UserProfileController;

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [UserController::class, 'home'])->name('home');

    // profile
    Route::get('/profile/{user}', [UserProfileController::class, 'show'])->name('user.profile.show');
    Route::get('/profile/{user}/edit', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile/{user}', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/profile/{user}/edit-password', [UserProfileController::class, 'editPassword'])->name('user.profile.edit-password');
    Route::put('/profile/{user}/update-password', [UserProfileController::class, 'updatePassword'])->name('user.profile.update-password');
});
