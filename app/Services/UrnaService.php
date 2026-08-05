<?php

namespace App\Services;

use App\Models\Urna;
use Exception;

class UrnaService
{
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
