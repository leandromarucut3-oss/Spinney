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
        $middleware->alias([
            'suspended' => \App\Http\Middleware\CheckSuspended::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'verified.user' => \App\Http\Middleware\EnsureUserIsVerified::class,
            'pin.verified' => \App\Http\Middleware\CheckPinVerification::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\CheckSuspended::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'register',
            'register/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
