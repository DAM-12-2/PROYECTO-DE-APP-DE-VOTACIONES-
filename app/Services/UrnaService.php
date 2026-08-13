<?php

namespace App\Services;

use App\Models\Urna;
use Illuminate\Support\Facades\DB;

class UrnaService
{
    public function activar(string $codigo): Urna
    {
        $urna = Urna::where('codigo', $codigo)->firstOrFail();

        DB::transaction(function () use ($urna) {
            $urna->estado = 1;
            $urna->horaactivacion = now();
            $urna->save();
        });

        return $urna;
    }

    public function desactivar(string $codigo): Urna
    {
        $urna = Urna::where('codigo', $codigo)->firstOrFail();

        DB::transaction(function () use ($urna) {
            $urna->estado = 0;
            $urna->save();
        });

        return $urna;
    }
}
