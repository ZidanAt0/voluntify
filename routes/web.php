<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

// Landing
Route::view('/', 'landing')->name('home');
Route::redirect('/home', '/');

// Auth + Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard per role (user/organizer/admin)
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:organizer')->prefix('organizer')->name('organizer.')->group(function () {
        Route::view('/dashboard', 'organizer.dashboard')->name('dashboard'); // nanti diganti controller
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard'); // nanti diganti controller
    });
});

// Catalog events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// Apply form & submit (JANGAN duplikat)
Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/apply', [RegistrationController::class, 'create'])->name('registrations.apply.form');
    Route::post('/events/{event}/apply', [RegistrationController::class, 'store'])->name('registrations.apply');
});

// My Registrations
Route::middleware('auth')->group(function () {
    Route::get('/me/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/me/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::delete('/me/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.cancel');
});

// Bookmarks
Route::middleware('auth')->group(function () {
    Route::get('/me/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/events/{event}/bookmark', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/events/{event}/bookmark', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
});

require __DIR__.'/auth.php';
