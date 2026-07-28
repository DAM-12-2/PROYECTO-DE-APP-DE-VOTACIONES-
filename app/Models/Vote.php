<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'encrypted_party',
        'id_mesa'
    ];

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class, 'id_mesa');
    }

    /**
     * Accesor útil para obtener el ID real del partido descifrándolo al vuelo (solo cuando es necesario para el conteo).
     */
    public function getDecryptedPartyAttribute()
    {
        try {
            return Crypt::decryptString($this->encrypted_party);
        } catch (\Exception $e) {
            return null; // Voto corrupto o manipulado directamente en la DB
        }
    }
}
