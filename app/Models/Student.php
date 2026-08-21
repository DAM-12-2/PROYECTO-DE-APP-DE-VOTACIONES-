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
        'estado'
    ];

    protected $casts = [
        'voto'    => 'boolean',
        'estado'  => 'boolean',
    ];
}