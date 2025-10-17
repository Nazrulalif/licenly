<?php

use App\Http\Controllers\Web\App\DashboardController;
use App\Http\Controllers\Web\App\GlobalSearchController;

// Include authentication routes
require __DIR__ . '/auth.php';
require __DIR__ . '/user.php';

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Global Search
    Route::get('/global-search', [GlobalSearchController::class, 'search'])->middleware(['auth'])->name('global.search');
});