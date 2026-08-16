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
    ];

    protected $casts = [
        'estado' => 'integer',
    ];

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class, 'id_mesa', 'id');
    }
}
