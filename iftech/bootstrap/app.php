<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PrefeituraMiddleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
   ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
           
            $middleware->api(prepend: [
                \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            ]);
            $middleware->alias([
            'prefeitura' => PrefeituraMiddleware::class,
        ]);
            $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('api/*')) {
                return null;
            }

            if ($request->is('empreendedor/*') || $request->is('logado-empreendedor')) {
                return route('login-empreendedor');
            }

            return route('login');
        });
            
            $middleware->validateCsrfTokens(except: [
                'api/*',
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();