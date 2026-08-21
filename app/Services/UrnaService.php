<?php

namespace App\Services;

use App\Models\Urna;
use Exception;
use Illuminate\Support\Facades\DB;

class UrnaService
{
    public function getAllUrnas(): array
    {
        try {
            $urnas = Urna::with('mesa')->get()->toArray();
            return ['success' => true, 'data' => $urnas];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al cargar las urnas: ' . $e->getMessage()];
        }
    }

    public function createUrna(array $data): array
    {
        try {
            $urna = Urna::create($data);
            return ['success' => true, 'data' => $urna, 'message' => 'Urna creada correctamente'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al crear la urna: ' . $e->getMessage()];
        }
    }

    public function actualizarUrna(int $id, array $data): array
    {
        try {
            $urna = Urna::findOrFail($id);
            $urna->update($data);
            return ['success' => true, 'data' => $urna, 'message' => 'Urna actualizada correctamente'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al actualizar la urna: ' . $e->getMessage()];
        }
    }

    public function eliminarUrna(int $id): array
    {
        try {
            $urna = Urna::findOrFail($id);
            $urna->delete();
            return ['success' => true, 'message' => 'Urna eliminada correctamente'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al eliminar la urna: ' . $e->getMessage()];
        }
    }

    public function activar(int $idUrna): array
    {
        try {
            $urna = Urna::findOrFail($idUrna);

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

    public function desactivar(int $idUrna): array
    {
        try {
            $urna = Urna::findOrFail($idUrna);

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
