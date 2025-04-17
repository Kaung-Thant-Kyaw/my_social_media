<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Social Login
Route::prefix('auth')->group(function () {
    Route::get('/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('social.login.redirect');
    Route::get('/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.login.callback');
});
