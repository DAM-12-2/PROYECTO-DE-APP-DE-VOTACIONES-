<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin {name : Nombre de usuario} {--password= : Contraseña (si se omite, se solicita)}';

    protected $description = 'Crea o actualiza un administrador sin credenciales por defecto';

    public function handle(): int
    {
        $name = $this->argument('name');
        $password = $this->option('password') ?: $this->secret('Contraseña (mínimo 8 caracteres)');

        if (!$password || strlen($password) < 8) {
            $this->error('La contraseña debe tener al menos 8 caracteres.');
            return self::FAILURE;
        }

        $user = User::updateOrCreate(
            ['name' => $name],
            [
                'password' => $password,
                'role' => 'admin',
                'must_change_password' => false,
            ]
        );

        $this->info("Administrador '{$user->name}' listo.");

        return self::SUCCESS;
    }
}
