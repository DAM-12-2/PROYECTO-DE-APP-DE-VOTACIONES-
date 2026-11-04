<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VoteRequest;

class VoteRequestPolicy
{
    public function view(User $user, VoteRequest $solicitud): bool
    {
        return $this->mismaMesa($user, $solicitud);
    }

    public function approve(User $user, VoteRequest $solicitud): bool
    {
        return $this->mismaMesa($user, $solicitud);
    }

    public function reject(User $user, VoteRequest $solicitud): bool
    {
        return $this->mismaMesa($user, $solicitud);
    }

    private function mismaMesa(User $user, VoteRequest $solicitud): bool
    {
        if (in_array($user->role, ['admin', 'tee'], true)) {
            return true;
        }

        return $user->role === 'jrv'
            && (int) $user->mesa_id === (int) $solicitud->mesa_id;
    }
}
