<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $e, Request $request) {

            if ($request->is('api/*'))
            {
                return true;
            }

            if ($request->ajax())
            {
                return true;
            }

            if (!$request->expectsJson() && !$request->is('api/*'))
            {
                return;
            }

            if ($e instanceof AuthenticationException)
            {
                return response()->json([
                    'message' => 'Unauthenticated',
                    'status' => Response::HTTP_UNAUTHORIZED,
                ], status: Response::HTTP_UNAUTHORIZED);
            }

            if ($e instanceof AuthorizationException)
            {
                return response()->json([
                    'errors' => 'You are not authorized',
                    'status' => Response::HTTP_FORBIDDEN,
                ], status: Response::HTTP_FORBIDDEN);
            }

            if ($e instanceof ModelNotFoundException)
            {
                $model = str($e->getModel())->afterLast('\\')->toString();
                return response()->json([
                    'errors' => "{$model} not found",
                    'status' => Response::HTTP_NOT_FOUND,
                ], status: Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof AccessDeniedHttpException)
            {
                return response()->json([
                    'message' => 'Access denied',
                    'status' => Response::HTTP_FORBIDDEN,
                ], status: Response::HTTP_FORBIDDEN);
            }

            if ($e instanceof ValidationException)
            {
                return response()->json([
                    'message' => 'The given data was invalid',
                    'errors' => $e->errors(),
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                ], status: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($e instanceof QueryException)
            {
                return response()->json([
                    'message' => 'Database operations failed',
                    'errors' => 'Database error',
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ], status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $previous = $e->getPrevious();

            if ($previous instanceof ModelNotFoundException)
            {
                return response()->json([
                    'errors' => str($previous->getModel())->afterLast(search: '\\') . ' not found',
                    'status' => Response::HTTP_NOT_FOUND,
                ], status: Response::HTTP_NOT_FOUND);
            }

            Log::error('Unhandled exception: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return response()->json([
                'message' => 'An unexpected error occurred',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'details' => config('app.debug') ? $e->getMessage() : null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    })->create();
