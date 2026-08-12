<?php

namespace App\Services;

use App\Models\Urna;
use Exception;

class UrnaService
{

    private BitacoraService $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }

    public function getAllUrnas()
    {
        return Urna::all();
    }

    public function getUrnaById(int $id)
    {
        $urna = Urna::find($id);
        if (!$urna) {
            throw new Exception("Urna no encontrada");
        }

        return $urna;
    }

    public function createUrna(array $data)
    {
        $urna = Urna::create($data);

        $this->bitacoraService->registrar('Creación de urna', 'Se creó una nueva urna ID: ' . $urna->id);

        return $urna;
    }

    public function actualizarUrna(int $id, array $data)
    {
        $urna = Urna::find($id);
        if (!$urna) {
            throw new Exception("Urna no encontrada");
        }

        $urna->update($data);

        $this->bitacoraService->registrar('Actualización de urna', 'Se actualizó la urna ID: ' . $id);

        return $urna;
    }

    public function eliminarUrna(int $id)
    {
        $urna = Urna::find($id);
        if (!$urna) {
            throw new Exception("Urna no encontrada");
        }

        $urna->delete();

        $this->bitacoraService->registrar('Eliminación de urna', 'Se eliminó la urna ID: ' . $id);

        return true;
    }

    public function activar(int $idUrna, int $idEstudiante): array
    {
        try{
            $urna=Urna::find($idUrna);
            if(!$urna){
                return [
                    'success'=>false,
                    'message'=>'La urna no existe'
                ];
        }

        $urna->estado = 1;
        $urna->estudiante_id = $idEstudiante;
        $urna->save();

        return [
            'success'=>true,
            'message'=>'La urna se ha activado correctamente',
            'data'=>$urna
        ];
    } catch(Exception $e){
        return [
            'success'=>false,
            'message'=>'Error al activar la urna: '.$e->getMessage()
        ];
    }
    }

    public function desactivar(int $idUrna): array
    {
        try{
            $urna=Urna::find($idUrna);
            if(!$urna){
                return [
                    'success'=>false,
                    'message'=>'La urna no existe'
                ];
        }

        $urna->estado = 0;
        $urna->save();

        return [
            'success'=>true,
            'message'=>'La urna se ha desactivado correctamente',
            'data'=>$urna
        ];
    } catch(Exception $e){
        return [
            'success'=>false,
            'message'=>'Error al desactivar la urna: '.$e->getMessage()
        ];
    }
    }
}
