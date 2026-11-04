<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetRlsContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return $next($request);
        }

        $user = $request->user();

        $this->set('app.role', $user?->role ?? 'anon');
        $this->set('app.user_id', (string) ($user?->id ?? 0));
        $this->set('app.mesa_id', (string) ($user?->mesa_id ?? 0));

        try {
            $response = $next($request);

            if (config('app.debug')) {
                $response->headers->set('X-Rls-Role', $user?->role ?? 'anon');
                $response->headers->set('X-Rls-Mesa', (string) ($user?->mesa_id ?? 0));
            }

            return $response;
        } finally {
            $this->set('app.role', 'anon');
            $this->set('app.user_id', '0');
            $this->set('app.mesa_id', '0');
        }
    }

    private function set(string $key, string $value): void
    {
        DB::selectOne('SELECT set_config(?, ?, false)', [$key, $value]);
    }
}
