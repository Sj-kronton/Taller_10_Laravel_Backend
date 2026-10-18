<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

class EventoPolicy
{
    // RF 2.3: solo el creador del evento puede modificarlo
    public function update(User $user, Evento $evento): bool
    {
        return (int) $user->id === (int) $evento->user_id;
    }
}