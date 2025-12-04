<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            // ... existing aliases ...
            'force.change.password' => \App\Http\Middleware\ForcePasswordChange::class, // <--- ADD THIS
        ]);
    })
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // <--- THIS WAS MISSING! ADD THIS LINE.
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register your aliases here
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'twofactor' => \App\Http\Middleware\TwoFactorMiddleware::class,
            'shift' => \App\Http\Middleware\EnsureShiftIsOpen::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
    