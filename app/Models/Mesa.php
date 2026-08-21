<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mesa extends Model
{
    protected $table = 'mesas';

    protected $fillable = [
        'nombre',
        'ubicacion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'integer',
    ];

    public function secciones(): HasMany
    {
        return $this->hasMany(SeccionMesa::class, 'mesa_id');
    }

    public function miembros(): HasMany
    {
        return $this->hasMany(MiembroJrv::class, 'mesa_id');
    }

    public function incidentes(): HasMany
    {
        return $this->hasMany(Incidente::class, 'mesa_id');
    }
}
