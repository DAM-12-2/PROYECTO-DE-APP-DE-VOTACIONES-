<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'siglas',
        'nombre',
        'fotopresidente',
        'bandera',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function candidatos(): HasMany
    {
        return $this->hasMany(Candidato::class, 'party_id');
    }

    public function miembrosJrv(): HasMany
    {
        return $this->hasMany(MiembroJrv::class, 'party_id');
    }
}
