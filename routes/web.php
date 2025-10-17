<?php

use Illuminate\Support\Facades\Route;

// WEB Route
require __DIR__ . '/web/web.php';

// Redirect root to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
