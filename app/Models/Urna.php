<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Urna extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_urna';

    protected $fillable = [
        'codigo',
        'horaactivacion',
        'estado',
        'id_mesa',
        'id_estudiante'
    ];

    protected $casts = [
        'estado' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'id_estudiante', 'identificacion');
    }

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class, 'id_mesa' , 'id');
    }
}
