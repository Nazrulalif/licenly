<?php

use App\Http\Controllers\Web\App\LicenseController;
use Illuminate\Support\Facades\Route;

// Authenticated Routes (only accessible when logged in)
Route::middleware('auth')->group(function () {
    Route::prefix('license')->name('license.')->group(function () {
        Route::get('/', [LicenseController::class, 'index'])->name('index');
        Route::get('/create', [LicenseController::class, 'create'])->name('create');
        Route::post('/', [LicenseController::class, 'store'])->name('store');
        Route::get('/{id}', [LicenseController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [LicenseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [LicenseController::class, 'update'])->name('update');
        Route::get('/{id}/download', [LicenseController::class, 'download'])->name('download');
        Route::post('/{id}/revoke', [LicenseController::class, 'revoke'])->name('revoke');
        Route::delete('/{id}', [LicenseController::class, 'destroy'])->name('destroy');
    });
});
