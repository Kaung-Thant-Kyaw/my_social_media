<?php

use App\Http\Controllers\User\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UserProfileController;

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [UserController::class, 'home'])->name('home');

    // profile
    Route::prefix('profile')->group(function () {
        Route::get('{user}', [UserProfileController::class, 'show'])->name('user.profile.show');
        Route::get('{user}/edit', [UserProfileController::class, 'edit'])->name('user.profile.edit');
        Route::put('/', [UserProfileController::class, 'update'])->name('user.profile.update');
        Route::get('{user}/edit-password', [UserProfileController::class, 'editPassword'])->name('user.profile.edit-password');
        Route::put('{user}/update-password', [UserProfileController::class, 'updatePassword'])->name('user.profile.update-password');

        // change profile picture
        Route::post('/change-profile-picture', [UserProfileController::class, 'changeProfilePicture'])->name('user.profile.change-profile-picture');

        // change cover picture
        Route::post('/change-cover-picture', [UserProfileController::class, 'changeCoverPicture'])->name('user.profile.change-cover-picture');

        // change password
        Route::get('change-password/{user}', [UserProfileController::class, 'changePasswordPage'])->name('user.profile.change-password-page');
        Route::post('change-password', [UserProfileController::class, 'changePassword'])->name('user.profile.change-password');
    });

    // posts 
    Route::prefix('post')->group(function () {
        Route::get('/create', [PostController::class, 'create'])->name('post.create');
        Route::post('', [PostController::class, 'store'])->name('post.store');
        Route::get('{post}', [PostController::class, 'edit'])->name('post.edit');
        Route::put('{post}', [PostController::class, 'update'])->name('post.update');
        Route::delete('{post}', [PostController::class, 'destroy'])->name('post.destroy');
    });
});
