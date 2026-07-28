<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeeMember extends Model
{
    protected $fillable = ['student_id', 'puesto', 'estado'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
