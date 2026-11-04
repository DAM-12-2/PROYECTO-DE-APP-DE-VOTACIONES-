<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TallySeal extends Model
{
    protected $fillable = [
        'mesa_id',
        'hash',
        'total',
        'blancos',
        'nulos',
        'sealed_at',
    ];

    protected $casts = [
        'sealed_at' => 'datetime',
    ];

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class);
    }
}
