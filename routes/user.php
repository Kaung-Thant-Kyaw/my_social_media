<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [UserController::class, 'home'])->name('home');
});
