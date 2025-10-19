<?php

use App\Http\Controllers\Web\App\RsaKeyController;
use Illuminate\Support\Facades\Route;

// Authenticated Routes (only accessible when logged in)
Route::middleware('auth')->group(function () {
    Route::prefix('rsakey')->name('rsakey.')->group(function () {
        Route::get('/', [RsaKeyController::class, 'index'])->name('index');
        Route::post('/generate', [RsaKeyController::class, 'generate'])->name('generate');
        Route::get('/{rsaKey}', [RsaKeyController::class, 'show'])->name('show');
        Route::get('/{rsaKey}/public-key', [RsaKeyController::class, 'publicKey'])->name('publicKey');
        Route::get('/{rsaKey}/download-public-key', [RsaKeyController::class, 'downloadPublicKey'])->name('downloadPublicKey');
        Route::post('/{rsaKey}/activate', [RsaKeyController::class, 'activate'])->name('activate');
        Route::delete('/{rsaKey}', [RsaKeyController::class, 'destroy'])->name('destroy');
    });
});
