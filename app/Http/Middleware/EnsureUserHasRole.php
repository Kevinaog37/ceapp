<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Verifica que el usuario autenticado tenga alguno de los roles indicados.
     * Uso en rutas: ->middleware('role:administrador,conductor')
     *
     * Esta capa es un filtro rápido a nivel de ruta/grupo. La autorización fina
     * por acción (view, create, update, delete) se resuelve siempre en las Policies.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->rol, $roles, true)) {
            return response()->json([
                'message' => 'No tiene permisos para acceder a este recurso.',
                'errors' => [],
            ], 403);
        }

        return $next($request);
    }
}
