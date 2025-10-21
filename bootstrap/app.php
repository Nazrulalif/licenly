<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.access' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // Configure guest middleware to redirect authenticated users to dashboard
        $middleware->redirectGuestsTo(fn() => route('login'));
        $middleware->redirectUsersTo(fn() => route('dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
