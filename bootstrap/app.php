<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tangani khusus NotFoundHttpException (Error 404)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            // Jika request berupa API/JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Resource not found.'], 404);
            }

            // Kembalikan komponen Error404.vue via Inertia
            return Inertia::render('Errors/Error404')
                ->toResponse($request)
                ->setStatusCode(404);
        });
        // 2. Tangani Error 503 / Maintenance Mode
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() === 503) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Service Unavailable.'], 503);
                }
                return Inertia::render('Errors/Error503')
                    ->toResponse($request)
                    ->setStatusCode(503);
            }
        });
    })->create();
