<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'identificacion',
        'nombre',
        'apellidos',
        'seccion',
        'foto',
        'huella',
        'voto',
        'idUrna',
        'estado'
    ];

    protected $casts = [
        'voto'    => 'boolean',
        'estado'  => 'boolean',
        'idUrna'  => 'integer',
    ];

    public function urna()
    {
        return $this->hasOne(Urna::class, 'id_estudiante');
    }
}