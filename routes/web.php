<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// landing
Route::view('/', 'landing')->name('home');
Route::redirect('/home', '/');

// auth+profile breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// dashboard
Route::middleware(['auth','verified'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return view('admin.dashboard');
    }
    if ($user->hasRole('organizer')) {
        return view('organizer.dashboard');
    }
    return view('user.dashboard'); // default
})->name('dashboard');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// Auth routes (login/register/forgot/etc.)
require __DIR__.'/auth.php';
