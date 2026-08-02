<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'identificacion';
    public $incrementing = false;
    protected $keyType = 'string';

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
        return $this->hasOne(Urna::class, 'idUrna', 'id');
    }
}