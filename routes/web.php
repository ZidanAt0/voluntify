<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Organizer\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Organizer\DashboardController;

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

Route::middleware(['auth'])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // ✅ FIX: CRUD EVENT ORGANIZER
        Route::resource('/events', EventController::class);

    });

    Route::middleware(['auth', 'role:organizer'])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {
        Route::post('/events/{event}/publish', [EventController::class, 'publish'])
    ->name('events.publish');

Route::post('/events/{event}/unpublish', [EventController::class, 'unpublish'])
    ->name('events.unpublish');


    Route::get('/dashboard', [\App\Http\Controllers\Organizer\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('/events', \App\Http\Controllers\Organizer\EventController::class);
});
Route::get('/organizer/events/{event}/participants',
    [\App\Http\Controllers\Organizer\ParticipantController::class, 'index']
)->name('organizer.events.participants');

Route::post('/organizer/registrations/{registration}/approve',
    [\App\Http\Controllers\Organizer\ParticipantController::class, 'approve']
)->name('organizer.registrations.approve');

Route::post('/organizer/registrations/{registration}/reject',
    [\App\Http\Controllers\Organizer\ParticipantController::class, 'reject']
)->name('organizer.registrations.reject');


require __DIR__.'/auth.php';
