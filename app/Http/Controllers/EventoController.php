<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventoController extends Controller
{
    // RF 2.1: listado público (sin autenticación)
    public function index(): JsonResponse
    {
        $eventos = Evento::with('organizador:id,name')->get();

        return response()->json($eventos, 200);
    }

    // RF 2.2 y RF 2.3: actualización de un evento
    public function update(Request $request, Evento $evento): JsonResponse
    {
        // 1) Autorización primero: lanza AuthorizationException (403)
        //    si EventoPolicy@update devuelve false
        Gate::authorize('update', $evento);

        // 2) Validación después: responde 422 si algún campo es inválido
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
            'cupo_maximo' => ['required', 'integer', 'min:1'],
        ]);

        $evento->update($datos);

        return response()->json($evento->load('organizador:id,name'), 200);
    }
}