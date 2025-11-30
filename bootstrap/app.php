<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register your aliases here
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'twofactor' => \App\Http\Middleware\TwoFactorMiddleware::class,
            'shift' => \App\Http\Middleware\EnsureShiftIsOpen::class, // <--- ADD THIS LINE
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();