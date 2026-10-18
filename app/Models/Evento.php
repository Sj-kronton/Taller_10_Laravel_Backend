<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evento extends Model
{
    protected $table = 'eventos';

    // user_id NO es asignable en masa: nadie puede cambiar el dueño de un evento.
    protected $fillable = ['titulo', 'descripcion', 'cupo_maximo'];

    public function organizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}