<?php

namespace App\Services;

use App\Models\Urna;
use Exception;
use Illuminate\Support\Facades\DB;

class UrnaService
{
    public function activar(string $codigo): array
    {
        try {
            $urna = Urna::where('codigo', $codigo)->firstOrFail();

            DB::transaction(function () use ($urna) {
                $urna->estado = 1;
                $urna->horaactivacion = now();
                $urna->save();
            });

            return ['success' => true, 'message' => 'La urna se ha activado correctamente', 'data' => $urna];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al activar la urna: ' . $e->getMessage()];
        }
    }

    public function desactivar(string $codigo): array
    {
        try {
            $urna = Urna::where('codigo', $codigo)->firstOrFail();

            DB::transaction(function () use ($urna) {
                $urna->estado = 0;
                $urna->save();
            });

            return ['success' => true, 'message' => 'La urna se ha desactivado correctamente', 'data' => $urna];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al desactivar la urna: ' . $e->getMessage()];
        }
    }
}
