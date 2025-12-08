<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController; // ✅ UNTUK VOLUNTEER
use App\Http\Controllers\Organizer\EventController as OrganizerEventController; // ✅ UNTUK ORGANIZER
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Organizer\DashboardController;
use App\Http\Controllers\Organizer\CheckinHistoryController;
use App\Http\Controllers\Organizer\StatisticController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminEventModerationController;

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
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:organizer', 'organizer_verified'])->prefix('organizer')->name('organizer.')->group(function () {
        Route::view('/dashboard', 'organizer.dashboard')->name('dashboard'); // nanti diganti controller
    });
});

Route::middleware(['auth','role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', AdminCategoryController::class)->except(['show']);

        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('users/{user}/verify-organizer', [AdminUserController::class, 'verifyOrganizer'])->name('users.verifyOrganizer');
        Route::patch('users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
        Route::patch('users/{user}/unsuspend', [AdminUserController::class, 'unsuspend'])->name('users.unsuspend');
        Route::patch('users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');

        Route::get('events/moderation', [AdminEventModerationController::class, 'index'])->name('events.moderation.index');
        Route::get('events/{event}/moderation', [AdminEventModerationController::class, 'show'])->name('events.moderation.show');
        Route::post('events/{event}/approve', [AdminEventModerationController::class, 'approve'])->name('events.approve');
        Route::post('events/{event}/reject',  [AdminEventModerationController::class, 'reject'])->name('events.reject');
        Route::post('events/{event}/close',   [AdminEventModerationController::class, 'close'])->name('events.close');
        Route::post('events/{event}/open',    [AdminEventModerationController::class, 'open'])->name('events.open');
        Route::post('events/{event}/cancel',  [AdminEventModerationController::class, 'cancel'])->name('events.cancel');
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
Route::middleware('auth')->get(
    '/me/registrations/{registration}/certificate',
    [\App\Http\Controllers\RegistrationController::class, 'downloadCertificate']
)->name('registrations.certificate');


Route::middleware(['auth', 'role:organizer', 'organizer_verified'])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('/events', OrganizerEventController::class);

        Route::post('/events/{event}/publish', [OrganizerEventController::class, 'publish'])
            ->name('events.publish');

        Route::post('/events/{event}/unpublish', [OrganizerEventController::class, 'unpublish'])
            ->name('events.unpublish');

        // Peserta
        Route::get(
            '/events/{event}/participants',
            [\App\Http\Controllers\Organizer\ParticipantController::class, 'index']
        )->name('events.participants');

        Route::post(
            '/registrations/{registration}/approve',
            [\App\Http\Controllers\Organizer\ParticipantController::class, 'approve']
        )->name('registrations.approve');

        Route::post(
            '/registrations/{registration}/reject',
            [\App\Http\Controllers\Organizer\ParticipantController::class, 'reject']
        )->name('registrations.reject');

        // Check-in
        Route::get(
            '/checkin',
            [\App\Http\Controllers\Organizer\CheckinController::class, 'index']
        )->name('checkin.index');

        Route::post(
            '/checkin',
            [\App\Http\Controllers\Organizer\CheckinController::class, 'store']
        )->name('checkin.store');
        Route::get(
            '/checkin-history',
            [CheckinHistoryController::class, 'index']
        )->name('checkin.history');
        Route::get(
            '/statistics',
            [StatisticController::class, 'index']
        )->name('statistics');
        Route::post(
            '/registrations/{registration}/complete',
            [\App\Http\Controllers\Organizer\ParticipantController::class, 'complete']
        )->name('registrations.complete');
    });

require __DIR__ . '/auth.php';
