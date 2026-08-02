<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // Proyecto API-only: no se registran rutas web/Blade, solo la API versionada.
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
        ]);

        // No hay sesión/CSRF de tipo web ya que el backend no sirve vistas Blade.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo de errores centralizado: siempre JSON, nunca stack traces en producción.
        $exceptions->shouldRenderJsonWhen(function ($request, Throwable $e) {
            return true;
        });

        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'message' => 'Los datos enviados no son válidos.',
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'message' => 'No autenticado.',
                'errors' => [],
            ], 401);
        });

        $exceptions->render(function (AuthorizationException $e, $request) {
            return response()->json([
                'message' => $e->getMessage() ?: 'No tiene permisos para realizar esta acción.',
                'errors' => [],
            ], 403);
        });

        $exceptions->render(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'message' => 'El recurso solicitado no existe.',
                'errors' => [],
            ], 404);
        });

        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'message' => 'Ruta no encontrada.',
                'errors' => [],
            ], 404);
        });

        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            return response()->json([
                'message' => $e->getMessage() ?: 'Error en la solicitud.',
                'errors' => [],
            ], $e->getStatusCode());
        });

        $exceptions->render(function (Throwable $e, $request) {
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Error interno del servidor.',
                    'errors' => [],
                ], 500);
            }

            // En entornos no productivos se expone el detalle para depuración,
            // pero nunca se renderiza HTML/stack trace de Blade (proyecto API-only).
            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        });
    })->create();
