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
        // Append session timeout middleware to web group
        $middleware->web(append: [
            \App\Http\Middleware\SessionTimeout::class,
        ]);

        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'prevent.back' => \App\Http\Middleware\PreventBackHistory::class,
            'must.change.password' => \App\Http\Middleware\MustChangePassword::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom handling for rate limit exceeded
        $exceptions->renderable(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Terlalu banyak percobaan. Silakan coba lagi dalam beberapa saat.',
                    'retry_after' => $e->getHeaders()['Retry-After'] ?? 60
                ], 429);
            }
            
            return back()->withErrors([
                'throttle' => 'Terlalu banyak percobaan login. Silakan tunggu ' . ($e->getHeaders()['Retry-After'] ?? 60) . ' detik sebelum mencoba lagi.',
            ])->onlyInput('nik', 'role');
        });
    })->create();
