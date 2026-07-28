<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class BlockReportsDuringVoting
{
    public function handle(Request $request, Closure $next): Response
    {
        $eleccionAbierta = Setting::where('nombre', 'eleccion_abierta')->value('detalle') ?? '0';

        if ($eleccionAbierta === '1') {
            $routeName = $request->route()->getName();

            $bloqueados = [
                'admin.reportes.acta_cierre',
                'admin.reportes.acta_resultados',
                'admin.reportes.resultados',
                'admin.reportes.padron_votos',
            ];

            if (in_array($routeName, $bloqueados)) {
                return redirect('/admin/reportes')
                    ->with('error', 'Los reportes de resultados no están disponibles mientras el proceso electoral está abierto. Cierre las votaciones primero.');
            }
        }

        return $next($request);
    }
}
