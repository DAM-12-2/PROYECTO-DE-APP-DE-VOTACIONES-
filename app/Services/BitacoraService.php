<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BitacoraService
{
    public function registrar(string $accion, string $detalle = null)
    {
        try {
            DB::table('bitacoras')->insert([
                'user_id' => Auth::check() ? Auth::id() : null,
                'mesa_id' => null,
                'detalle' => "{$accion} - {$detalle}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error("Bitácora falló: {$accion} | {$detalle} | Error: {$e->getMessage()}");
        }
    }
}
