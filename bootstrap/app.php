<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\UserAuthMiddleware;
use App\Http\Middleware\AdminAuthMiddleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.admin' => AdminAuthMiddleware::class,
            'auth.customer' => UserAuthMiddleware::class,
        ]);

        // Configure CORS
        $middleware->validateCsrfTokens(except: [
            'sanctum/csrf-cookie',
            'api/*',
        ]);

        $middleware->trustProxies(at: [
            '*',
        ]);

        // CORS configuration
        $middleware->api(append: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY
                ]);
            }
        });
    })->create();
