<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Urna extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'horaactivacion',
        'estado',
        'id_mesa',
<<<<<<< HEAD
=======
        'id_estudiante',
>>>>>>> 3e045c4 (Cambios en la base de datos, ya está al 100%)
    ];

    protected $casts = [
        'estado' => 'integer',
    ];

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class, 'id_mesa', 'id');
<<<<<<< HEAD
=======
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'id_estudiante', 'id');
>>>>>>> 3e045c4 (Cambios en la base de datos, ya está al 100%)
    }
}
