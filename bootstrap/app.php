<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'messages' => ['Tidak terautentikasi.'],
                ], 401);
            }
        });

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                $messages = [];
                foreach ($e->errors() as $fieldErrors) {
                    $messages = array_merge($messages, $fieldErrors);
                }
                return response()->json([
                    'success' => false,
                    'messages' => array_values(array_unique($messages)),
                ], 422);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'messages' => ['Data tidak ditemukan.'],
                ], 404);
            }
        });

        $exceptions->render(function (\Illuminate\Database\QueryException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                $errorCode = $e->errorInfo[1] ?? 0;
                if ($errorCode == 1451) {
                    return response()->json([
                        'success' => false,
                        'messages' => ['Data tidak dapat dihapus karena masih digunakan oleh data lain.'],
                    ], 409);
                } elseif ($errorCode == 1062) {
                    return response()->json([
                        'success' => false,
                        'messages' => ['Data sudah ada (duplikat).'],
                    ], 409);
                }

                return response()->json([
                    'success' => false,
                    'messages' => ['Terjadi kesalahan pada database.'],
                ], 500);
            }
        });

        $exceptions->render(function (\Exception $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                // Don't override other specific HTTP exceptions already handled above or by Laravel natively if we don't want to,
                // but we can catch general 500s or other uncaught exceptions.
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                $message = $e->getMessage();
                
                // Optional: Hide detailed error in production, but since they want Indonesian errors, we can give a general one
                if ($statusCode === 500 && !config('app.debug')) {
                    $message = 'Terjadi kesalahan pada server.';
                }

                // If message is empty or default english HTTP phrases
                if (empty($message) || $message === 'Server Error') {
                    $message = 'Terjadi kesalahan pada server.';
                }

                return response()->json([
                    'success' => false,
                    'messages' => [$message],
                ], $statusCode);
            }
        });
    })->create();
