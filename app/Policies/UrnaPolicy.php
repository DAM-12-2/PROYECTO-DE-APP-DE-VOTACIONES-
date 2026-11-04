<?php

namespace App\Policies;

use App\Models\Urna;
use App\Models\User;

class UrnaPolicy
{
    public function view(User $user, Urna $urna): bool
    {
        if (in_array($user->role, ['admin', 'tee'], true)) {
            return true;
        }

        return $user->role === 'jrv' && (int) $user->mesa_id === (int) $urna->id_mesa;
    }

    public function operate(User $user, Urna $urna): bool
    {
        return $this->view($user, $urna);
    }
}
