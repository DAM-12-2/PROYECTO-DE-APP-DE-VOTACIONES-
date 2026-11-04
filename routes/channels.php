<?php

use App\Models\Urna;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('mesa.{mesaId}', function ($user, $mesaId) {
    if (in_array($user->role, ['admin', 'tee'], true)) {
        return true;
    }

    return (int) $user->mesa_id === (int) $mesaId;
});

Broadcast::channel('urna.{urnaId}', function ($user, $urnaId) {
    if (in_array($user->role, ['admin', 'tee'], true)) {
        return true;
    }

    $urna = Urna::find($urnaId);

    return $urna && (int) $urna->id_mesa === (int) $user->mesa_id;
});

