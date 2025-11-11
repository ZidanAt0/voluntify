<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

// --- Auth + Profile (Breeze) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Dashboards (cek peran) ---
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', fn () => 'User Dashboard')->name('dashboard');
});

Route::middleware(['auth','verified','role:admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn () => 'Admin Dashboard')->name('dashboard');
    });

Route::middleware(['auth','verified','role:organizer'])
    ->prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/dashboard', fn () => 'Organizer Dashboard')->name('dashboard');
    });

// --- Wajib: route auth Breeze (login/register/forgot/etc.) ---
require __DIR__.'/auth.php';