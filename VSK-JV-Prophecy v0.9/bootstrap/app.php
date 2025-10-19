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
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware for Unicode support and session handling
        $middleware->web(append: [
            \App\Http\Middleware\UnicodeMiddleware::class,
            \App\Http\Middleware\HandleSessionExpiry::class,
        ]);
        
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'unicode' => \App\Http\Middleware\UnicodeMiddleware::class,
            'session.expiry' => \App\Http\Middleware\HandleSessionExpiry::class,
            'recaptcha' => \App\Http\Middleware\VerifyRecaptcha::class,
            'auth.download' => \App\Http\Middleware\CheckAuthForDownload::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
