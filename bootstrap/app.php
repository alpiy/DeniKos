<?php

use App\Http\Middleware\Cors;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
          $middleware->alias([
            'role' => CheckRole::class,
            'cors' => Cors::class,
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'prevent.back' => PreventBackHistory::class,
            // Tambahkan middleware lain jika ada
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// **Tambahkan binding Console Kernel di sini**
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

return $app;