<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password && !$request->routeIs('password.change*')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe cambiar su contraseña antes de continuar.',
                ], 409);
            }

            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
