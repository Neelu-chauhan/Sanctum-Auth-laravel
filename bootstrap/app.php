<?php

use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $exceptions) {
            $returnData = [
                'message' => 'User is not authenticated!',
                'error' => $exceptions->getMessage()
            ];

            if (true) {
                $returnData['debug'] = $exceptions->getMessage();
            }

            return response()->json($returnData, 401);
        });
    })->create();

