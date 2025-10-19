<?php

use App\Http\Controllers\Web\App\CustomerController;
use Illuminate\Support\Facades\Route;

// Authenticated Routes (only accessible when logged in)
Route::middleware('auth')->group(function () {
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
        Route::put('/{id}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('toggleStatus');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-destroy', [CustomerController::class, 'bulkDestroy'])->name('bulkDestroy');
    });
});
