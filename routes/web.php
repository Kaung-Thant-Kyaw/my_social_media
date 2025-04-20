<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
require __DIR__ . '/auth.php';

Route::redirect('/', 'login', 301);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Social Accounts
Route::prefix('auth')->group(function () {
    Route::get('/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('social.login.redirect');
    Route::get('/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.login.callback');
    Route::delete('/social-account/{socialAccount}', [SocialLoginController::class, 'destroy'])->middleware('auth')->name('social.accounts.destroy');
});

Route::post('/test-posts', function() {
    return response()->json(['status' => 'success']);
})->name('test-posts');
