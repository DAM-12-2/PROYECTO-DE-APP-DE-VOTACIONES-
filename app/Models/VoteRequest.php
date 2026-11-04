<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteRequest extends Model
{
    public const PENDIENTE = 'pendiente';
    public const APROBADA = 'aprobada';
    public const VOTANDO = 'votando';
    public const VOTADA = 'votada';
    public const RECHAZADA = 'rechazada';

    protected $fillable = [
        'student_id',
        'mesa_id',
        'urna_id',
        'estado',
        'created_by',
        'approved_by',
        'approved_at',
        'voted_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'voted_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class);
    }

    public function urna(): BelongsTo
    {
        return $this->belongsTo(Urna::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
