<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class BlockPadronDuringVoting
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
            return $next($request);
        }

        $abierta = Setting::where('nombre', 'eleccion_abierta')->value('detalle') ?? '0';

        if ($abierta === '1') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El padrón está bloqueado mientras la elección está abierta. Ciérrela primero.',
                ], 423);
            }

            return back()->with('error', 'El padrón está bloqueado mientras la elección está abierta. Ciérrela primero.');
        }

        return $next($request);
    }
}
