<?php

use App\Facades\Cart;
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
        $middleware->trustProxies('*');
        $middleware->validateCsrfTokens(['/midtrans/*']);
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo(function () {
            $role = request()->user()->role;

            if ($role === 'admin') {
                return '/admin/beranda';
            } else {
                return '/';
            }
        });
        $middleware->trustProxies('*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSingletons([
        'Cart' => Cart::class
    ])
    ->create();
