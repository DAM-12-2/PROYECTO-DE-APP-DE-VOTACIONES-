<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'password',
        'role',
        'mesa_id',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verifica la contraseña y migra el hash al algoritmo configurado.
     */
    public function verificarPassword(string $plain): bool
    {
        try {
            if (\Illuminate\Support\Facades\Hash::check($plain, $this->password)) {
                return true;
            }
        } catch (\Throwable $e) {
            // Hash de otro algoritmo (p. ej. bcrypt con HASH_DRIVER=argon2id)
        }

        if (password_verify($plain, $this->password)) {
            $this->password = $plain;
            $this->save();

            return true;
        }

        return false;
    }

    public function isTee(): bool
    {
        return $this->role === 'tee';
    }

    public function isJrv(): bool
    {
        return $this->role === 'jrv';
    }

    public function canAccessAdmin(): bool
    {
        return in_array($this->role, ['admin', 'tee']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrador',
            'tee' => 'Tribunal Electoral',
            'jrv' => 'Junta Receptora',
            default => 'Sin rol',
        };
    }
}
